<?php

namespace App\Policies;

use App\Models\Weight;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WeightPolicy
{
	use HandlesAuthorization;

	/**
	 * Determines if the given weight can be viewed by the user.
	 *
	 * @param  User   $currentUser
	 * @param  Weight $weight
	 * @return boolean
	 */
	public function view(User $currentUser, Weight $weight) : bool
	{
		return $weight->user_id === $currentUser->id;
	}

	/**
	 * Determines if the given weight can be created by the user.
	 *
	 * @param  User   $currentUser
	 * @param  Weight $weight
	 * @return boolean
	 */
	public function create(User $currentUser, Weight $weight) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}

	/**
	 * Determines if the given weight can be deleted by the user.
	 *
	 * @param  User   $currentUser
	 * @param  Weight $weight
	 * @return boolean
	 */
	public function delete(User $currentUser, Weight $weight) : bool
	{
		return $this->view($currentUser, $weight);
	}

	/**
	 * Determines if the given weight can be updated by the user.
	 *
	 * @param  User   $currentUser
	 * @param  Weight $weight
	 * @return boolean
	 */
	public function update(User $currentUser, Weight $weight) : bool
	{
		return $this->view($currentUser, $weight);
	}

	/**
	 * Determines if the given weight can be viewed by the user.
	 *
	 * @param  User   $currentUser
	 * @param  Weight $weight
	 * @return boolean
	 */
	public function viewAny(User $currentUser, Weight $weight) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}
}
