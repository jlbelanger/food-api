<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
	return response()->json(['success' => true]);
});

Route::group(['middleware' => ['api', 'guest', 'throttle:auth']], function () {
	Route::post('/auth/login', [\App\Http\Controllers\AuthController::class, 'login']);
	Route::post('/auth/register', [\App\Http\Controllers\AuthController::class, 'register']);
	Route::post('/auth/forgot-password', [\App\Http\Controllers\AuthController::class, 'forgotPassword']);
	Route::put('/auth/reset-password/{token}', [\App\Http\Controllers\AuthController::class, 'resetPassword'])->middleware('signed:relative')->name('password.update');
	Route::post('/auth/verify-email', [\App\Http\Controllers\AuthController::class, 'verifyEmail'])->middleware('signed:relative')->name('verification.verify');
	Route::post('/auth/resend-verification', [\App\Http\Controllers\AuthController::class, 'resendVerification'])->name('verification.send');
});

Route::group(['middleware' => ['api', 'auth:sanctum', 'throttle:api']], function () {
	Route::delete('/auth/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
	Route::put('/auth/change-email', [\App\Http\Controllers\AuthController::class, 'changeEmail']);
	Route::put('/auth/change-password', [\App\Http\Controllers\AuthController::class, 'changePassword']);

	Route::get('/date', [\App\Http\Controllers\DateController::class, 'show']);

	Route::post('/food/{id}/favourite', [\App\Http\Controllers\FoodController::class, 'favourite']);

	Route::post('/meals/{id}/add', [\App\Http\Controllers\MealController::class, 'add']);

	Route::post('/users/delete-data', [\App\Http\Controllers\UserController::class, 'deleteData']);

	Route::get('/calendar/{year}', function ($year) {
		$months = [];

		if (!preg_match('/^[0-9]{4}$/', $year)) {
			return response()->json(['message' => 'Invalid year.'], 422);
		}

		$user = Auth::guard('sanctum')->user();
		$trackables = $user->trackables()->get();
		$dataByDate = $user->getDataByDate($trackables, $year);

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

	Route::get('/charts', function () {
		$user = Auth::guard('sanctum')->user();
		$trackables = $user->trackables()->get();
		$data = $user->getDataByDate($trackables);
		$output = [
			'Weight' => [],
		];

		foreach ($data as $row) {
			if (!empty($row->weight)) {
				$output['Weight'][$row->date] = $row->weight;
			}
			foreach ($trackables as $trackable) {
				if (!empty($row->{$trackable->slug})) {
					$output[$trackable->name][$row->date] = $row->{$trackable->slug};
				}
			}
		}

		foreach ($output as $label => $values) {
			$points = [];
			ksort($values);
			foreach ($values as $x => $y) {
				$points[] = [
					'x' => $x,
					'y' => $y,
				];
			}
			$output[$label] = $points;
		}

		return response()->json($output);
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
