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
		Schema::create('users', function (Blueprint $table) {
			$table->id();
			$table->string('username')->unique();
			$table->string('email')->unique();
			$table->string('password');
			$table->enum('sex', ['f', 'm'])->nullable();
			$table->tinyInteger('age')->unsigned()->nullable();
			$table->tinyInteger('height')->unsigned()->nullable();
			$table->tinyInteger('activity_level')->unsigned()->nullable();
			$table->enum('measurement_units', ['i', 'm'])->nullable();
			$table->boolean('favourites_only')->default(false);
			$table->boolean('is_admin')->default(false);
			$table->rememberToken();
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
		Schema::dropIfExists('users');
	}
};
