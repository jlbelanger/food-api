<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The policy mappings for the application.
	 *
	 * @var array<class-string, class-string>
	 */
	protected $policies = [
		'App\Models\Entry' => \App\Policies\EntryPolicy::class,
		'App\Models\Extra' => \App\Policies\ExtraPolicy::class,
		'App\Models\Food' => \App\Policies\FoodPolicy::class,
		'App\Models\Meal' => \App\Policies\MealPolicy::class,
		'App\Models\Trackable' => \App\Policies\TrackablePolicy::class,
		'App\Models\User' => \App\Policies\UserPolicy::class,
		'App\Models\Weight' => \App\Policies\WeightPolicy::class,
	];

	/**
	 * Registers any authentication / authorization services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerPolicies();
	}
}
