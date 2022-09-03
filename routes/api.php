<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Jlbelanger\Tapioca\Exceptions\NotFoundException;

Route::get('/', function () {
	return response()->json(['success' => true]);
});

Route::group(['middleware' => ['api', 'guest']], function () {
	Route::post('/auth/login', [\App\Http\Controllers\AuthController::class, 'login']);
	Route::post('/auth/register', [\App\Http\Controllers\AuthController::class, 'register']);
	Route::post('/auth/forgot-password', [\App\Http\Controllers\AuthController::class, 'forgotPassword']);
	Route::put('/auth/reset-password/{token}', [\App\Http\Controllers\AuthController::class, 'resetPassword']);
});

Route::group(['middleware' => ['api', 'auth:sanctum']], function () {
	Route::delete('/auth/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
	Route::post('/food/{id}/favourite', [\App\Http\Controllers\FoodController::class, 'favourite']);
	Route::post('/meals/{id}/add', [\App\Http\Controllers\MealController::class, 'add']);
	Route::put('/users/{id}/change-email', [\App\Http\Controllers\UserController::class, 'changeEmail']);
	Route::put('/users/{id}/change-password', [\App\Http\Controllers\UserController::class, 'changePassword']);

	Route::get('/calendar/{year}', function ($year) {
		$months = [];

		if (!preg_match('/^[0-9]{4}$/', $year)) {
			return response()->json(['message' => 'Invalid year.'], 422);
		}

		$user = Auth::guard('sanctum')->user();
		$trackables = $user->trackables()->get();
		$select = ['entries.date'];
		foreach ($trackables as $trackable) {
			$select[] = DB::raw('SUM(ROUND((entries.user_serving_size / food.serving_size) * food.' . $trackable->slug . ')) AS ' . $trackable->slug);
		}

		$data = DB::table('entries')
			->select($select)
			->join('food', 'entries.food_id', 'food.id')
			->where('entries.user_id', '=', $user->id)
			->where('entries.date', 'LIKE', $year . '-%')
			->whereNull('entries.deleted_at')
			->groupBy('entries.date')
			->get();
		$dataByDate = [];
		foreach ($data as $d) {
			$dataByDate[$d->date] = $d;
		}

		if ($year === date('Y')) {
			$maxMonth = date('m');
		} else {
			$maxMonth = 12;
		}

		for ($month = 0; $month < $maxMonth; $month++) {
			$date = $year . '-' . str_pad($month + 1, 2, '0', STR_PAD_LEFT);
			$months[$month] = [
				'month' => $date,
				'data' => false,
				'weeks' => [],
			];
			$numDays = date('t', strtotime($date . '-01'));
			$firstWeekday = date('w', strtotime($date . '-01'));
			$week = 0;
			$weekday = 0;

			for ($day = 0; $day < $firstWeekday; $day++) {
				if (empty($months[$month]['weeks'][$week])) {
					$months[$month]['weeks'][$week] = [
						'week' => $week,
						'data' => false,
						'days' => [],
					];
				}
				$months[$month]['weeks'][$week]['days'][] = [
					'date' => null,
					'i' => $day,
				];
				$weekday++;
			}

			for ($day = 1; $day <= $numDays; $day++) {
				$currentDate = $date . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
				$row = [
					'date' => $currentDate,
					'trackables' => !empty($dataByDate[$currentDate]) ? $dataByDate[$currentDate] : null,
				];
				if (empty($months[$month]['weeks'][$week])) {
					$months[$month]['weeks'][$week] = [
						'week' => $week,
						'data' => false,
						'days' => [],
					];
				}
				if (!empty($row['trackables'])) {
					$months[$month]['weeks'][$week]['data'] = true;
					$months[$month]['data'] = true;
				}
				$months[$month]['weeks'][$week]['days'][] = $row;
				$weekday++;
				if ($weekday === 7) {
					$weekday = 0;
					$week++;
				}
			}

			if ($weekday > 0) {
				for ($day = $weekday; $day < 7; $day++) {
					$months[$month]['weeks'][$week]['days'][] = [
						'date' => null,
						'i' => $day,
					];
				}
			}
		}

		$months = array_reverse($months);

		return response()->json($months);
	});

	Route::apiResources([
		'entries' => \App\Http\Controllers\EntryController::class,
		'extras' => \App\Http\Controllers\ExtraController::class,
		'food' => \App\Http\Controllers\FoodController::class,
		'meals' => \App\Http\Controllers\MealController::class,
		'trackables' => \App\Http\Controllers\TrackableController::class,
		'users' => \App\Http\Controllers\UserController::class,
		'weights' => \App\Http\Controllers\WeightController::class,
	]);
});

Route::fallback(function () {
	throw NotFoundException::generate();
});
