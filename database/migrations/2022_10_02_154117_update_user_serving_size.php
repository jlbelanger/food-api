<?php

use Illuminate\Database\Migrations\Migration;

class UpdateUserServingSize extends Migration
{
	/**
	 * Runs the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::unprepared('ALTER TABLE `entries` MODIFY COLUMN `user_serving_size` double(10,6) NOT NULL');
		DB::unprepared('ALTER TABLE `food_meal` MODIFY COLUMN `user_serving_size` double(10,6) NOT NULL');
	}

	/**
	 * Reverses the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::unprepared('ALTER TABLE `entries` MODIFY COLUMN `user_serving_size` double(9,6) NOT NULL');
		DB::unprepared('ALTER TABLE `food_meal` MODIFY COLUMN `user_serving_size` double(9,6) NOT NULL');
	}
}
