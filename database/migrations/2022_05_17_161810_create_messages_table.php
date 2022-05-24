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
		Schema::create('messages', function (Blueprint $table) {
			$table->id();
			$table->foreignId('instance_id')
				->constrained('whatsmonster_instances')
				->cascadeOnUpdate()->cascadeOnDelete();
			$table->string('external_id');
			$table->text('text')->nullable();
			$table->boolean('from_me');
			$table->string('phone');
			$table->string('media_mime_type')->nullable();
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
		Schema::dropIfExists('whatsapp_messages');
	}
};
