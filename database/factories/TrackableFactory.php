<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TrackableFactory extends Factory
{
	/**
	 * Defines the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition() : array
	{
		return [
			'name' => 'Calories',
			'slug' => 'calories',
		];
	}
}
