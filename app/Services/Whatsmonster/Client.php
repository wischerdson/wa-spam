<?php

namespace App\Services\Whatsmonster;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Client
{
	private string $accessToken;

	private string $baseUri = 'https://whatsmonster.ru/api/';

	public function __construct()
	{
		$this->accessToken = config('services.whatsmonster.access_token');
	}

	public function createInstance(): string
	{
		$response = $this->send('POST', 'createinstance.php');
		return $response->instance_id;
	}

	/**
	 * Display QR code to login to Whatsapp web. You can get the results returned via Webhook
	 */
	public function getQRCode(string $instanceId): string
	{
		$response = $this->send('POST', 'getqrcode.php', [
			'instance_id' => $instanceId
		]);

		return $response->base64;
	}

	/**
	 * Get all return values from Whatsapp. Like connection status, Incoming message, Outgoing
	 * message, Disconnected, Change Battery,...
	 */
	public function setWebhook(string $instanceId): object
	{
		return $this->send('GET', 'setwebhook.php', [
			'webhook_url' => $this->getWebhookUrl(),
			'enable' => 'true',
			'instance_id' => $instanceId
		]);
	}

	public function getWebhookUrl(): string
	{
		$host = config('services.whatsmonster.webhook_host') ?? config('app.url');
		return Str::finish($host, '/').'api/whatsmonster-callback';
	}

	/**
	 * Logout Whatsapp web and do a fresh scan
	 */
	public function reboot(string $instanceId)
	{

	}

	/**
	 * This will logout Whatsapp web, Change Instance ID, Delete all old instance data
	 */
	public function reset(string $instanceId)
	{

	}

	/**
	 * Re-initiate connection from app to Whatsapp web when lost connection
	 */
	public function reconnect(string $instanceId)
	{

	}

	/**
	 * Send a text message to a phone number through the app
	 */
	public function sendTextMessage(string $instanceId, string $phone, string $text)
	{
		$this->send('POST', 'send.php', [
			'number' => $phone,
			'type' => 'text',
			'message' => $text,
			'instance_id' => $instanceId
		]);
	}

	/**
	 * Send a media or file with message to a phone number through the app
	 */
	public function sendFileMessage(string $instanceId, string $phone, string $file, ?string $caption = null)
	{
		$this->send('POST', 'send.php', [
			'number' => $phone,
			'type' => 'media',
			'message' => $caption,
			'media_url' => $file,
			'instance_id' => $instanceId
		]);
	}

	public function onMessage(Request $request, $callback)
	{
		if ($request->event == 'message' && $messages = $request->data['messages']) {
			foreach ($messages as $rawMessage) {
				$message = new Message($request->instance_id, $rawMessage);

				if ($message->parse()) {
					$callback($message);
				}
			}
		}
	}

	public function onConfirmQRCode(callable $callback)
	{

	}

	/**
	 * @throws \App\Exceptions\WhatsmonsterResponseException
	 */
	public function send(
		string $method,
		string $url,
		array $query = [],
		array $options = []
	): object
	{
		$response = Http::withOptions([
			'query' => [
				'access_token' => $this->accessToken,
				...$query
			],
			...$options
		])->{$method}($this->baseUri.$url)->object();

		if ($response->status == 'error') {
			throw new \App\Exceptions\WhatsmonsterResponseException($response->message);
		}

		return $response;
	}
}
