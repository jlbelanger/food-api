<?php

namespace App\Models;

use App\Models\Food;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jlbelanger\Tapioca\Traits\Resource;

class FoodMeal extends Model
{
	use HasFactory, Resource;

	protected $table = 'food_meal';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'food_id',
		'meal_id',
		'user_serving_size',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'food_id' => 'integer',
		'meal_id' => 'integer',
		'user_serving_size' => 'float',
	];

	/**
	 * @return BelongsTo
	 */
	public function food() : BelongsTo
	{
		return $this->belongsTo(Food::class);
	}

	/**
	 * @param  array  $data
	 * @param  string $method
	 * @return array
	 */
	protected function rules(array $data, string $method) : array // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassBeforeLastUsed
	{
		$required = $method === 'POST' ? 'required' : 'filled';
		return [
			'attributes.food_id' => [$required],
			'attributes.meal_id' => [$required],
			'attributes.user_serving_size' => [$required, 'numeric'],
		];
	}

	/**
	 * @return array
	 */
	public function singularRelationships() : array
	{
		return ['food', 'meal'];
	}
}
