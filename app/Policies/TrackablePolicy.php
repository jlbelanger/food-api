<?php

namespace App\Policies;

use App\Models\Trackable;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrackablePolicy
{
	use HandlesAuthorization;

	/**
	 * Determines if the given trackable can be viewed by the user.
	 *
	 * @param  User      $currentUser
	 * @param  Trackable $trackable
	 * @return boolean
	 */
	public function view(User $currentUser, Trackable $trackable) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}

	/**
	 * Determines if the given trackable can be created by the user.
	 *
	 * @param  User      $currentUser
	 * @param  Trackable $trackable
	 * @return boolean
	 */
	public function create(User $currentUser, Trackable $trackable) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return !empty($currentUser->is_admin);
	}

	/**
	 * Determines if the given trackable can be deleted by the user.
	 *
	 * @param  User      $currentUser
	 * @param  Trackable $trackable
	 * @return boolean
	 */
	public function delete(User $currentUser, Trackable $trackable) : bool
	{
		return $this->view($currentUser, $trackable);
	}

	/**
	 * Determines if the given trackable can be updated by the user.
	 *
	 * @param  User      $currentUser
	 * @param  Trackable $trackable
	 * @return boolean
	 */
	public function update(User $currentUser, Trackable $trackable) : bool
	{
		return $this->view($currentUser, $trackable);
	}

	/**
	 * Determines if the given trackable can be viewed by the user.
	 *
	 * @param  User      $currentUser
	 * @param  Trackable $trackable
	 * @return boolean
	 */
	public function viewAny(User $currentUser, Trackable $trackable) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}
}
