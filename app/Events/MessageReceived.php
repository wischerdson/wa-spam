<?php

namespace App\Events;

use App\Models\WhatsAppMessage;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageReceived
{
	use Dispatchable, SerializesModels;

	public readonly WhatsAppMessage $message;

	public function __construct(WhatsAppMessage $message)
	{
		$this->message = $message;
	}
}
