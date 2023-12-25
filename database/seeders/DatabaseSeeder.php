<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seeds the application's database.
	 *
	 * @return void
	 */
	public function run() : void
	{
		$date = Carbon::now();

		DB::table('users')->insert([
			'username' => 'demo',
			'email' => 'demo@example.com',
			'email_verified_at' => $date,
			'password' => Hash::make('demo'),
			'created_at' => $date,
		]);
	}
}
