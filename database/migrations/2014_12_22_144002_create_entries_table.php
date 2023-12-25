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
		Schema::create('entries', function (Blueprint $table) {
			$table->id();
			$table->foreignId('food_id')->references('id')->on('food')->constrained()->onDelete('restrict');
			$table->foreignId('user_id')->references('id')->on('users')->constrained()->onDelete('cascade');
			$table->double('user_serving_size', 10, 6);
			$table->date('date');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverses the migrations.
	 *
	 * @return void
	 */
	public function down() : void
	{
		Schema::dropIfExists('entries');
	}
};
