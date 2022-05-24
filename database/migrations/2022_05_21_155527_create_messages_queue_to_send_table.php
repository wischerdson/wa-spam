<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
		Schema::create('messages_queue_to_send', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->foreignId('instance_id')
				->constrained('whatsmonster_instances')
				->cascadeOnUpdate()->cascadeOnDelete();
			$table->string('phone');
			$table->integer('delay_before_send');
			$table->timestamp('send_at');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('messages_queue_to_send');
	}
};
