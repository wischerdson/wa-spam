<?php

namespace App\Http\Controllers;

use App\Models\AccountInstance;
use App\Models\Settings;
use App\Services\Whatsmonster\Client as WhatsmonsterClient;
use App\Services\Whatsmonster\Service as WhatsmonsterService;
use Illuminate\Http\Request;

class WhatsmonsterController extends Controller
{
	public function index()
	{
		return AccountInstance::all();
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

	public function uploadMessageFile(Request $request)
	{
		$filename = $request->file('file')->store('message-files', 'public');

		Settings::updateOrCreate(
			['key' => 'message_file'],
			['value' => $filename]
		);
	}

	public function callback(Request $request, WhatsmonsterService $service)
	{
		$service->handleWebhookRequest($request);

		return 'ok';
	}

	public function newAccount(Request $request, WhatsmonsterClient $client)
	{
		AccountInstance::firstOrCreate([
			'whatsmonster_id' => $request->instance_id
		]);

		$client->setWebhook($request->instance_id);

		return 'ok';
	}
}
