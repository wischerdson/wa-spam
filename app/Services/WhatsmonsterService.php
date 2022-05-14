<?php

namespace App\Services;

use App\Models\WhatsmonsterInstance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsmonsterService
{
	private Request $request;

	private string $accessToken;

	private string $baseUri = 'https://whatsmonster.ru/api/';

	public function __construct(Request $request)
	{
		$this->request = $request;
		$this->accessToken = config('services.whatsmonster.access_token');
	}

	/**
	 * @throws \App\Exceptions\WhatsmonsterResponseException
	 */
	public function createInstance(?string $instanceId = null): WhatsmonsterInstance
	{
		$response = $this->send('createinstance.php');

		if ($response->status == 'error') {
			throw new \App\Exceptions\WhatsmonsterResponseException($response->message);
		}

		return WhatsmonsterInstance::create([
			'whatsmonster_id' => $response->instance_id
		]);
	}

	/**
	 * Display QR code to login to Whatsapp web. You can get the results returned via Webhook
	 */
	public function getQRCode(WhatsmonsterInstance $instance)
	{
		$response = $this->send('getqrcode.php', [
			'instance_id' => $instance->whatsmonster_id
		]);

		dd($response);
	}

	/**
	 * Get all return values from Whatsapp. Like connection status, Incoming message, Outgoing
	 * message, Disconnected, Change Battery,...
	 */
	public function setWebhook(WhatsmonsterInstance $instance)
	{

	}

	/**
	 * Logout Whatsapp web and do a fresh scan
	 */
	public function reboot(WhatsmonsterInstance $instance)
	{

	}

	/**
	 * This will logout Whatsapp web, Change Instance ID, Delete all old instance data
	 */
	public function reset(WhatsmonsterInstance $instance)
	{

	}

	/**
	 * Re-initiate connection from app to Whatsapp web when lost connection
	 */
	public function reconnect(WhatsmonsterInstance $instance)
	{

	}

	/**
	 * Send a text message to a phone number through the app
	 */
	public function sendTextMessage(WhatsmonsterInstance $instance)
	{

	}

	/**
	 * Send a media or file with message to a phone number through the app
	 */
	public function sendFileMessage(WhatsmonsterInstance $instance)
	{

	}

	public function onReceiveMessage(callable $callback)
	{

	}

	public function onConfirmQRCode(callable $callback)
	{

	}

	public function send(string $method, array $query = [], array $options = []): object
	{
		return Http::withOptions([
			'query' => [
				'access_token' => $this->accessToken,
				...$query
			],
			...$options
		])->post($this->baseUri.$method)->object();
	}
}
