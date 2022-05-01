<?php

namespace App\Policies;

use App\Models\Meal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MealPolicy
{
	use HandlesAuthorization;

	/**
	 * Determines if the given meal can be viewed by the user.
	 *
	 * @param  User $currentUser
	 * @param  Meal $meal
	 * @return boolean
	 */
	public function view(User $currentUser, Meal $meal) : bool
	{
		return $meal->user_id === $currentUser->id;
	}

	/**
	 * Determines if the given meal can be created by the user.
	 *
	 * @param  User $currentUser
	 * @param  Meal $meal
	 * @return boolean
	 */
	public function create(User $currentUser, Meal $meal) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}

	/**
	 * Determines if the given meal can be deleted by the user.
	 *
	 * @param  User $currentUser
	 * @param  Meal $meal
	 * @return boolean
	 */
	public function delete(User $currentUser, Meal $meal) : bool
	{
		return $this->view($currentUser, $meal);
	}

	/**
	 * Determines if the given meal can be updated by the user.
	 *
	 * @param  User $currentUser
	 * @param  Meal $meal
	 * @return boolean
	 */
	public function update(User $currentUser, Meal $meal) : bool
	{
		return $this->view($currentUser, $meal);
	}

	/**
	 * Determines if the given meal can be viewed by the user.
	 *
	 * @param  User $currentUser
	 * @param  Meal $meal
	 * @return boolean
	 */
	public function viewAny(User $currentUser, Meal $meal) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}
}
