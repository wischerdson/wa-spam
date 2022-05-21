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
		Schema::create('whatsmonster_logs', function (Blueprint $table) {
			$table->id();
			$table->foreignId('instance_id')
                ->constrained('whatsmonster_instances')
                ->cascadeOnUpdate()->cascadeOnDelete();
			$table->string('event_type');
			$table->json('data');
			$table->timestamp('created_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('whatsmonster_logs');
	}
};
