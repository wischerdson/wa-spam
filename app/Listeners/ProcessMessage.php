<?php

namespace App\Listeners;

use App\Events\MessageReceived;
use App\Jobs\Reply;
use App\Models\MessageQueueToSend;
use App\Services\Whatsmonster\Service as WhatsmonsterService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessMessage implements ShouldQueue
{
	/**
	 * Handle the event.
	 *
	 * @param  object  $event
	 * @return void
	 */
	public function handle(MessageReceived $event)
	{
		$message = $event->message;

		if ($message->from_me) {
			return;
		}

		$service = new WhatsmonsterService();
		if ($service->determineWhetherToSendMessage($message->id, $message->phone)) {
			$message = MessageQueueToSend::create([
				'instance_id' => $message->instance_id,
				'phone' => $message->phone,
				'delay_before_send' => random_int(10, 12),
				'send_at' => now()
			]);

			Reply::dispatch($message)->onQueue('replying');
		}
	}
}
