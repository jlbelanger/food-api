<?php

namespace App\Policies;

use App\Models\Entry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EntryPolicy
{
	use HandlesAuthorization;

	/**
	 * Determines if the given entry can be viewed by the user.
	 *
	 * @param  User  $currentUser
	 * @param  Entry $entry
	 * @return boolean
	 */
	public function view(User $currentUser, Entry $entry) : bool
	{
		return $entry->user_id === $currentUser->id;
	}

	/**
	 * Determines if the given entry can be created by the user.
	 *
	 * @param  User  $currentUser
	 * @param  Entry $entry
	 * @return boolean
	 */
	public function create(User $currentUser, Entry $entry) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}

	/**
	 * Determines if the given entry can be deleted by the user.
	 *
	 * @param  User  $currentUser
	 * @param  Entry $entry
	 * @return boolean
	 */
	public function delete(User $currentUser, Entry $entry) : bool
	{
		return $this->view($currentUser, $entry);
	}

	/**
	 * Determines if the given entry can be updated by the user.
	 *
	 * @param  User  $currentUser
	 * @param  Entry $entry
	 * @return boolean
	 */
	public function update(User $currentUser, Entry $entry) : bool
	{
		return $this->view($currentUser, $entry);
	}

	/**
	 * Determines if the given entry can be viewed by the user.
	 *
	 * @param  User  $currentUser
	 * @param  Entry $entry
	 * @return boolean
	 */
	public function viewAny(User $currentUser, Entry $entry) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}
}
