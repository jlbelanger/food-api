<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seeds the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		$date = date('Y-m-d H:i:s');

		DB::table('users')->insert([
			'username' => 'demo',
			'email' => 'demo@example.com',
			'password' => bcrypt('demo'),
			'created_at' => $date,
		]);
		$userId = DB::getPdo()->lastInsertId();
	}
}
