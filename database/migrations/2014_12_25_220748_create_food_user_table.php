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
		Schema::create('food_user', function (Blueprint $table) {
			$table->id();
			$table->foreignId('food_id')->references('id')->on('food')->constrained()->onDelete('cascade');
			$table->foreignId('user_id')->references('id')->on('users')->constrained()->onDelete('cascade');
			$table->timestamps();

			$table->unique(['food_id', 'user_id']);
		});
	}

	/**
	 * Reverses the migrations.
	 *
	 * @return void
	 */
	public function down() : void
	{
		Schema::dropIfExists('food_user');
	}
};
