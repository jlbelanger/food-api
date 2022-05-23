<?php

namespace App\Http\Controllers;

use App\Models\Food;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jlbelanger\Tapioca\Controllers\AuthorizedResourceController;
use Jlbelanger\Tapioca\Exceptions\NotFoundException;

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
			throw NotFoundException::generate();
		}

		$favourite = DB::table('food_user')
			->where('food_id', '=', $id)
			->where('user_id', '=', $user->id)
			->first();

		if ($favourite) {
			DB::table('food_user')
				->where('id', '=', $favourite->id)
				->delete();
		} else {
			DB::table('food_user')
				->insert([
					'food_id' => $id,
					'user_id' => $user->id,
					'created_at' => date('Y-m-d H:i:s'),
				]);
		}

		return response()->json(null, 204);
	}
}
