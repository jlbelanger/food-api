<?php

namespace App\Http\Controllers;

use App\Models\Food;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jlbelanger\Tapioca\Controllers\AuthorizedResourceController;

class FoodController extends AuthorizedResourceController
{
	/**
	 * @param  Request $request
	 * @param  string  $id
	 * @return JsonResponse
	 */
	public function favourite(Request $request, string $id) : JsonResponse // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassBeforeLastUsed
	{
		$food = Food::find($id);
		$user = Auth::guard('sanctum')->user();
		if (!$food || !$user) {
			abort(404);
		}

		$favourite = DB::table('food_user')
			->where('food_id', '=', $id)
			->where('user_id', '=', $user->id)
			->first();

		if ($favourite) {
			DB::table('food_user')
				->where('id', '=', $favourite->id)
				->delete();
			$user->clearFavouritesCache();
		} else {
			$food->addFavourite($user);
		}

		return response()->json(null, 204);
	}
}
