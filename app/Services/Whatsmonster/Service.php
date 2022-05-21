<?php

namespace App\Services\Whatsmonster;

use App\Models\WhatsAppMessage;
use App\Models\WhatsmonsterInstance;
use App\Models\WhatsmonsterLog;
use Illuminate\Http\Request;

class Service
{
	private Client $client;

	public function __construct(Client $client)
	{
		$this->client = $client;
	}

	public function handleMessages(Request $request)
	{
		$this->client->onMessage($request, function (Message $message) {
			$instance = WhatsmonsterInstance::firstOrCreate([
				'whatsmonster_id' => $message->instanceId
			]);

			WhatsAppMessage::updateOrCreate([
				'whatsapp_id' => $message->id
			], [
				'text' => $message->text,
				'from_me' => $message->fromMe,
				'phone' => $message->phone,
				'created_at' => $message->timestamp,
				'media_mime_type' => $message->mediaMimeType,
				'instance_id' => $instance->id
			]);
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
}
