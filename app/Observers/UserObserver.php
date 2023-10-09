<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Carbon;

class UserObserver
{
	/**
	 * @param  User $user
	 * @return void
	 */
	public function deleted(User $user)
	{
		$user->username = 'deleted-' . Carbon::now() . '-' . $user->username;
		$user->email = 'deleted-' . Carbon::now() . '-' . $user->email;
		$user->save();
	}
}
