<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Models\WhatsmonsterInstance;
use App\Services\WhatsmonsterService;
use Illuminate\Http\Request;

class WhatsMonsterController extends Controller
{
	/**
	 * Whatsmonster instances list
	 */
	public function index()
	{
		return WhatsmonsterInstance::all();
	}

	public function getReplyMessage()
	{
		return Settings::find('reply_message')?->value;
	}

	public function setReplyMessage(Request $request)
	{
		Settings::updateOrCreate(
			['key' => 'reply_message'],
			['value' => $request->message]
		);
	}

	public function callback(Request $request, WhatsmonsterService $service)
	{
		$service->onReceiveMessage(function (WhatsmonsterInstance $instance, $message) {

		});
		\Log::debug($request->all());
	}

	public function newAccount(Request $request, WhatsmonsterService $service)
	{
		$instance = $service->createInstance($request->instance_id);
		$service->setWebhook($instance);
		return $service->getQRCode($instance);
	}
}
