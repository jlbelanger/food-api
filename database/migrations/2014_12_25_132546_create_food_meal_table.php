<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodMealTable extends Migration
{
	/**
	 * Runs the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('food_meal', function (Blueprint $table) {
			$table->id();
			$table->foreignId('food_id')->references('id')->on('food')->constrained()->onDelete('restrict');
			$table->foreignId('meal_id')->references('id')->on('meals')->constrained()->onDelete('cascade');
			$table->double('user_serving_size', 9, 6);
			$table->timestamps();

			$table->unique(['food_id', 'meal_id']);
		});
	}

	/**
	 * Reverses the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('food_meal');
	}
}
