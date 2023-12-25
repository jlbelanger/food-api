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
		Schema::create('extras', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->references('id')->on('users')->constrained()->onDelete('cascade');
			$table->string('note');
			$table->date('date');
			$table->smallInteger('calories')->unsigned()->nullable();
			$table->double('fat', 8, 3)->unsigned()->nullable();
			$table->double('saturated_fat', 8, 3)->unsigned()->nullable();
			$table->double('trans_fat', 8, 3)->unsigned()->nullable();
			$table->double('polyunsaturated_fat', 8, 3)->unsigned()->nullable();
			$table->double('omega_6', 8, 3)->unsigned()->nullable();
			$table->double('omega_3', 8, 3)->unsigned()->nullable();
			$table->double('monounsaturated_fat', 8, 3)->unsigned()->nullable();
			$table->double('cholesterol', 8, 3)->unsigned()->nullable();
			$table->double('sodium', 8, 3)->unsigned()->nullable();
			$table->double('potassium', 8, 3)->unsigned()->nullable();
			$table->double('carbohydrate', 8, 3)->unsigned()->nullable();
			$table->double('fibre', 8, 3)->unsigned()->nullable();
			$table->double('sugars', 8, 3)->unsigned()->nullable();
			$table->double('protein', 8, 3)->unsigned()->nullable();
			$table->smallInteger('vitamin_a')->unsigned()->nullable();
			$table->smallInteger('vitamin_c')->unsigned()->nullable();
			$table->smallInteger('calcium')->unsigned()->nullable();
			$table->smallInteger('iron')->unsigned()->nullable();
			$table->smallInteger('vitamin_d')->unsigned()->nullable();
			$table->smallInteger('vitamin_e')->unsigned()->nullable();
			$table->smallInteger('vitamin_k')->unsigned()->nullable();
			$table->smallInteger('thiamin')->unsigned()->nullable();
			$table->smallInteger('riboflavin')->unsigned()->nullable();
			$table->smallInteger('niacin')->unsigned()->nullable();
			$table->smallInteger('vitamin_b6')->unsigned()->nullable();
			$table->smallInteger('folate')->unsigned()->nullable();
			$table->smallInteger('vitamin_b12')->unsigned()->nullable();
			$table->smallInteger('biotin')->unsigned()->nullable();
			$table->smallInteger('pantothenate')->unsigned()->nullable();
			$table->smallInteger('phosphorus')->unsigned()->nullable();
			$table->smallInteger('iodine')->unsigned()->nullable();
			$table->smallInteger('magnesium')->unsigned()->nullable();
			$table->smallInteger('zinc')->unsigned()->nullable();
			$table->smallInteger('selenium')->unsigned()->nullable();
			$table->smallInteger('copper')->unsigned()->nullable();
			$table->smallInteger('manganese')->unsigned()->nullable();
			$table->smallInteger('chromium')->unsigned()->nullable();
			$table->smallInteger('molybdenum')->unsigned()->nullable();
			$table->smallInteger('chloride')->unsigned()->nullable();
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
		Schema::dropIfExists('extras');
	}
};
