<?php

namespace App\Policies;

use App\Models\Extra;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExtraPolicy
{
	use HandlesAuthorization;

	/**
	 * Determines if the given extra can be viewed by the user.
	 *
	 * @param  User  $currentUser
	 * @param  Extra $extra
	 * @return boolean
	 */
	public function view(User $currentUser, Extra $extra) : bool
	{
		return $extra->user_id === $currentUser->id;
	}

	/**
	 * Determines if the given extra can be created by the user.
	 *
	 * @param  User  $currentUser
	 * @param  Extra $extra
	 * @return boolean
	 */
	public function create(User $currentUser, Extra $extra) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}

	/**
	 * Determines if the given extra can be deleted by the user.
	 *
	 * @param  User  $currentUser
	 * @param  Extra $extra
	 * @return boolean
	 */
	public function delete(User $currentUser, Extra $extra) : bool
	{
		return $this->view($currentUser, $extra);
	}

	/**
	 * Determines if the given extra can be updated by the user.
	 *
	 * @param  User  $currentUser
	 * @param  Extra $extra
	 * @return boolean
	 */
	public function update(User $currentUser, Extra $extra) : bool
	{
		return $this->view($currentUser, $extra);
	}

	/**
	 * Determines if the given extra can be viewed by the user.
	 *
	 * @param  User  $currentUser
	 * @param  Extra $extra
	 * @return boolean
	 */
	public function viewAny(User $currentUser, Extra $extra) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}
}
