<?php

namespace App\Models;

use App\Models\Food;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;
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
	 * The attributes that should be cast.
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
	 * @return BelongsTo
	 */
	public function meal() : BelongsTo
	{
		return $this->belongsTo(Meal::class);
	}

	/**
	 * @return array
	 */
	public function rules() : array
	{
		$rules = [
			'data.relationships.food' => [$this->requiredOnCreate()],
			'data.relationships.meal' => [$this->requiredOnCreate()],
			'data.attributes.user_serving_size' => [$this->requiredOnCreate(), 'numeric'],
		];

		$mealId = request('data.relationships.meal.data.id', $this->meal_id);
		$unique = Rule::unique($this->getTable(), 'food_id')->where(function ($query) use ($mealId) {
			return $query->where('meal_id', '=', $mealId);
		});
		if ($this->id) {
			$unique->ignore($this->id);
		}
		$rules['data.relationships.food'][] = $unique;

		return $rules;
	}

	/**
	 * @return array
	 */
	public function singularRelationships() : array
	{
		return ['food', 'meal'];
	}
}
