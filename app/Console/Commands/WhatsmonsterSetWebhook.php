<?php

namespace App\Console\Commands;

use App\Models\WhatsmonsterInstance;
use App\Services\WhatsmonsterService;
use Illuminate\Console\Command;

class WhatsmonsterSetWebhook extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'whatsmonster:webhook {whatsmonsterId}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Set whatsmonster webhook for the given instanceID';

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{
		$instance = WhatsmonsterInstance::firstOrCreate([
			'whatsmonster_id' => $this->argument('whatsmonsterId')
		]);

		$service = new WhatsmonsterService();

		$this->info("Set webhook url in {$service->getWebhookUrl()}");

		$response = $service->setWebhook($instance);

		dump($response);

		return 0;
	}
}
