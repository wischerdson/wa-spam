<?php

namespace App\Services;

use App\Models\WhatsmonsterInstance;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WhatsmonsterService
{
	private string $accessToken;

	private string $baseUri = 'https://whatsmonster.ru/api/';

	public function __construct()
	{
		$this->accessToken = config('services.whatsmonster.access_token');
	}

	public function createInstance(?string $instanceId = null): WhatsmonsterInstance
	{
		if (!$instanceId) {
			$response = $this->send('POST', 'createinstance.php');
			$instanceId = $response->instance_id;
		}

		return WhatsmonsterInstance::firstOrCreate([
			'whatsmonster_id' => $instanceId
		]);
	}

	/**
	 * Display QR code to login to Whatsapp web. You can get the results returned via Webhook
	 */
	public function getQRCode(WhatsmonsterInstance $instance): string
	{
		$response = $this->send('POST', 'getqrcode.php', [
			'instance_id' => $instance->whatsmonster_id
		]);

		return $response->base64;
	}

	/**
	 * Get all return values from Whatsapp. Like connection status, Incoming message, Outgoing
	 * message, Disconnected, Change Battery,...
	 */
	public function setWebhook(WhatsmonsterInstance $instance): object
	{
		return $this->send('GET', 'setwebhook.php', [
			'webhook_url' => $this->getWebhookUrl(),
			'enable' => 'true',
			'instance_id' => $instance->whatsmonster_id
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
