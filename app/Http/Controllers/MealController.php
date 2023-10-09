<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Food;
use App\Models\Meal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jlbelanger\Tapioca\Controllers\AuthorizedResourceController;

class MealController extends AuthorizedResourceController
{
	/**
	 * @param  Request $request
	 * @param  string  $id
	 * @return JsonResponse
	 */
	public function add(Request $request, string $id) : JsonResponse // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassBeforeLastUsed
	{
		$meal = Meal::find($id);
		$user = Auth::guard('sanctum')->user();
		$date = $request->input('date');
		if (!$meal || !$user || !$date) {
			abort(404);
		}

		$foods = $meal->foods()->get();
		$output = [];
		$included = [];

		foreach ($foods as $mealFood) {
			$entry = new Entry();
			$entry->food_id = $mealFood->food_id;
			$entry->user_id = $user->id;
			$entry->user_serving_size = $mealFood->user_serving_size;
			$entry->date = $date;
			$entry->save();
			$output[] = $entry->data(['food'], ['entries' => ['user_serving_size']]);

			$included[$mealFood->food_id] = $mealFood->food_id;
		}

		$included = Food::whereIn('id', $included)
			->get();
		$fields = $request->input('fields');
		foreach ($included as $i => $food) {
			$included[$i] = $food->data([], ['food' => $fields]);
		}

		return response()->json(['data' => $output, 'included' => $included]);
	}
}
