<?php

namespace App\Services\Whatsmonster;

use App\Events\MessageReceived;
use App\Models\MessageQueueToSend;
use App\Models\WhatsAppMessage;
use App\Models\WhatsmonsterInstance;
use App\Models\WhatsmonsterLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Service
{
	public function handleWebhookRequest(Request $request)
	{

	}

	public function handleMessages(Request $request)
	{
		$client = new Client();
		$client->onMessage($request, function (Message $message) {
			$instance = WhatsmonsterInstance::firstOrCreate([
				'whatsmonster_id' => $message->instanceId
			]);

			$waMessage = WhatsAppMessage::updateOrCreate([
				'whatsapp_id' => $message->id
			], [
				'text' => $message->text,
				'from_me' => $message->fromMe,
				'phone' => $message->phone,
				'created_at' => $message->timestamp,
				'media_mime_type' => $message->mediaMimeType,
				'instance_id' => $instance->id
			]);

			MessageReceived::dispatch($waMessage);
		});
	}

	public function logRequest(Request $request)
	{
		$instance = WhatsmonsterInstance::firstOrCreate([
			'whatsmonster_id' => $request->instance_id
		]);

		WhatsmonsterLog::create([
			'instance_id' => $instance->id,
			'event_type' => $request->event,
			'data' => json_encode($request->data)
		]);
	}

	public function determineWhetherToSendMessage(int $messageId, string $phone): bool
	{
		$disallow = false;

		$disallow = MessageQueueToSend::where('phone', $phone)->count() > 0;
		$disallow = $this->getGroupMessagesCountInConversation($messageId, $phone) > 2;

		return !$disallow;
	}

	/**
	 * Подсчет количества сгруппированных по отправителю сообщений в переписке (без учета
	 * только что пришедшего сообщения).
	 * Например для следующего диалога:
	 *
	 * 🔴 Привет          (1)
	 * 🔴 Как дела?
	 * 🟢 Нормально       (2)
	 * 🟢 У тебя как?
	 * 🔴 Тоже норм       (3) - сообщение, тригернувшее этот метод
	 *
	 * метод вернет число 2
	 */
	private function getGroupMessagesCountInConversation(int $messageId, string $phone): int
	{
		$table = (new WhatsAppMessage())->getTable();

		$groupStartQuery = DB::table($table)
			->select([
				"$table.created_at",
				DB::raw(<<<END
					case when
						abs(lag(from_me, 1) over(order by $table.created_at) - from_me) <> 1
						then 0 else 1
					end as group_start
				END)
			])
			->where('phone', $phone)->whereNot('id', $messageId);

		$groupSequenceQuery = DB::table($groupStartQuery, $table)
			->select(DB::raw("sum(group_start) over(order by $table.created_at) as group_sequence"));

		$groupCount = DB::table($groupSequenceQuery, $table)
			->select(DB::raw("max($table.group_sequence) as group_count"))
			->first();

		return (int) $groupCount->group_count;
	}
}
