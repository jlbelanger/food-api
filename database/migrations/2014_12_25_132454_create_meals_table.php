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
		Schema::create('meals', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->references('id')->on('users')->constrained()->onDelete('cascade');
			$table->string('name');
			$table->boolean('is_favourite')->default(false);
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
		Schema::dropIfExists('meals');
	}
};
