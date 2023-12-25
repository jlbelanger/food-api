<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Runs the migrations.
	 *
	 * @return void
	 */
	public function up() : void
	{
		Schema::create('weights', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->references('id')->on('users')->constrained()->onDelete('cascade');
			$table->double('weight', 4, 1);
			$table->date('date');
			$table->timestamps();

			$table->unique(['date', 'user_id']);
		});
	}

	/**
	 * Reverses the migrations.
	 *
	 * @return void
	 */
	public function down() : void
	{
		Schema::dropIfExists('weights');
	}
};
