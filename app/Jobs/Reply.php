<?php

namespace App\Jobs;

use App\Models\MessageQueueToSend;
use App\Models\Settings;
use App\Services\Whatsmonster\Client as WhatsmonsterClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class Reply implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	private MessageQueueToSend $queuedMessage;

	public function __construct(MessageQueueToSend $queuedMessage)
	{
		$this->queuedMessage = $queuedMessage;

	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$whatsmonsterInstanceId = $this->queuedMessage->instance->whatsmonster_id;
		$phone = $this->queuedMessage->phone;
		$delay = $this->queuedMessage->delay_before_send;

		sleep($delay);

		$settings = Settings::all()->pluck('value', 'key');

		\Log::debug(Storage::url($settings['message_file']));

		$client = new WhatsmonsterClient();
		$client->sendFileMessage(
			$whatsmonsterInstanceId,
			$phone,
			Storage::url($settings['message_file']),
			$settings['reply_message']
		);
	}
}
