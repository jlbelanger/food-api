<?php

namespace App\Policies;

use App\Models\Food;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FoodPolicy
{
	use HandlesAuthorization;

	/**
	 * Determines if the given food can be viewed by the user.
	 *
	 * @param  User $currentUser
	 * @param  Food $food
	 * @return boolean
	 */
	public function view(User $currentUser, Food $food) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}

	/**
	 * Determines if the given food can be created by the user.
	 *
	 * @param  User $currentUser
	 * @param  Food $food
	 * @return boolean
	 */
	public function create(User $currentUser, Food $food) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}

	/**
	 * Determines if the given food can be deleted by the user.
	 *
	 * @param  User $currentUser
	 * @param  Food $food
	 * @return boolean
	 */
	public function delete(User $currentUser, Food $food) : bool
	{
		return $this->view($currentUser, $food) && $food->getDeleteableAttribute();
	}

	/**
	 * Determines if the given food can be updated by the user.
	 *
	 * @param  User $currentUser
	 * @param  Food $food
	 * @return boolean
	 */
	public function update(User $currentUser, Food $food) : bool
	{
		return $this->view($currentUser, $food);
	}

	/**
	 * Determines if the given food can be viewed by the user.
	 *
	 * @param  User $currentUser
	 * @param  Food $food
	 * @return boolean
	 */
	public function viewAny(User $currentUser, Food $food) : bool // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	{
		return true;
	}
}
