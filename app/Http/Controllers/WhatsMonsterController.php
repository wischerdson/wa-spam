<?php

namespace App\Http\Controllers;

use App\Services\WhatsmonsterService;
use Illuminate\Http\Request;

class WhatsMonsterController extends Controller
{
	public function callback(Request $request)
	{
		\Log::debug($request->all());
	}

	public function newAccount(Request $request, WhatsmonsterService $service)
	{
		$instance = $service->createInstance($request->instance_id);
		$service->getQRCode($instance);
	}
}
