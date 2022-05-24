<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('raw_messages', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->foreignId('instance_id')
				->constrained('whatsmonster_instances')
				->cascadeOnUpdate()->cascadeOnDelete();
			$table->json('json');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('raw_messages');
	}
};
