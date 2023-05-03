<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Models\Extra;
use App\Models\Weight;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Jlbelanger\Tapioca\Helpers\JsonApiRequest;

class DateController extends Controller
{
	public function show(Request $request) : JsonResponse
	{
		$model = new Entry;
		$entries = new JsonApiRequest('index', $request, $model, $model->newQuery());

		$model = new Extra;
		$extras = new JsonApiRequest('index', $request, $model, $model->newQuery());

		$model = new Weight;
		$weights = new JsonApiRequest('index', $request, $model, $model->newQuery());

		return response()->json([
			'entries' => $entries->output(),
			'extras' => $extras->output(),
			'weights' => $weights->output(),
		]);
	}
}
