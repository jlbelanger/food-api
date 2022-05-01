<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackableUserTable extends Migration
{
	/**
	 * Runs the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('trackable_user', function (Blueprint $table) {
			$table->id();
			$table->foreignId('trackable_id')->references('id')->on('trackables')->constrained()->onDelete('cascade');
			$table->foreignId('user_id')->references('id')->on('users')->constrained()->onDelete('cascade');
			$table->timestamps();
		});
	}

	/**
	 * Reverses the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('trackable_user');
	}
}
