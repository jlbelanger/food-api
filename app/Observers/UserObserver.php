<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
	/**
	 * @param  User $user
	 * @return void
	 */
	public function deleted(User $user)
	{
		$user->username = 'deleted-' . strtotime('now') . '-' . $user->username;
		$user->email = 'deleted-' . strtotime('now') . '-' . $user->email;
		$user->save();
	}
}
