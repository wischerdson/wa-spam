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

	}

	/**
	 * Send a media or file with message to a phone number through the app
	 */
	public function sendFileMessage(string $instanceId, string $phone, string $file, string $caption)
	{

	}

	public function onMessage(Request $request, callable $callback)
	{
		if ($request->event == 'message' && $messages = $request->data['messages']) {
			$preparedMessages = [];

			foreach ($messages as $message) {
				if (!isset($message['message'])) {
					continue;
				}

				$preparedMessage = [
					'from_me' => (bool) $message['key']['fromMe'],
					'phone' => explode('@', $message['key']['remoteJid'])[0],
					'timestamp' => $message['messageTimestamp']
				];

				foreach ($message['message'] as $key => $value) {
					if ($key == 'conversation') {
						$preparedMessage['text'] = $value;
						continue;
					}

					if (is_array($value)) {
						if (isset($value['mimetype'])) {
							$preparedMessage['media_mime_type'] = $value['mimetype'];
						}

						if (isset($value['caption'])) {
							$preparedMessage['text'] = $value['caption'];
						}
					}
				}
			}

			if (!empty($preparedMessages)) {
				$request->merge($preparedMessages);
				$callback($request);
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
