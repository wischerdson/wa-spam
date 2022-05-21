<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Models\WhatsmonsterInstance;
use App\Services\Whatsmonster\Client as WhatsmonsterClient;
use App\Services\Whatsmonster\Service as WhatsmonsterService;
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
		$service->logRequest($request);
		$service->handleMessages($request);

		return 'ok';
	}

	public function newAccount(Request $request, WhatsmonsterClient $client)
	{
		WhatsmonsterInstance::firstOrCreate([
			'whatsmonster_id' => $request->instance_id
		]);

		$client->setWebhook($request->instance_id);

		return 'ok';
	}
}
