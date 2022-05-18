<?php

namespace App\Services\Whatsmonster;

use App\Models\WhatsmonsterInstance;
use Illuminate\Http\Request;

class Service
{
	private Client $client;

	public function __construct(Client $client)
	{
		$this->client = $client;
	}

	public function handleMessages(Request $request)
	{
		$this->client->onMessage($request, function (Request $request) {
			$instance = WhatsmonsterInstance::find($request->instance_id);
		});
	}
}
