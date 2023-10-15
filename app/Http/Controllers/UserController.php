<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Extra;
use App\Models\Meal;
use App\Models\Weight;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jlbelanger\Tapioca\Controllers\AuthorizedResourceController;

class UserController extends AuthorizedResourceController
{
	/**
	 * @param  Request $request
	 * @return JsonResponse
	 */
	public function deleteData(Request $request) : JsonResponse
	{
		$types = $request->input('types');
		if (empty($types)) {
			return response()->json(['message' => 'Please select at least one type of data to delete.'], 422);
		}

		DB::beginTransaction();
		$user = Auth::guard('sanctum')->user();

		if (in_array('weights', $types)) {
			Weight::where('user_id', '=', $user->getKey())->delete();
		}

		if (in_array('meals', $types)) {
			Meal::where('user_id', '=', $user->getKey())->delete();
		}

		if (in_array('entries', $types)) {
			Entry::where('user_id', '=', $user->getKey())->delete();
			Extra::where('user_id', '=', $user->getKey())->delete();
		}

		DB::commit();

		return response()->json(null, 204);
	}
}
