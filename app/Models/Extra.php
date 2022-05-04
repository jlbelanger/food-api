<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Jlbelanger\Tapioca\Traits\Resource;

class Extra extends Model
{
	use HasFactory, Resource, SoftDeletes;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'user_id',
		'note',
		'date',
		'calories',
		'fat',
		'saturated_fat',
		'trans_fat',
		'polyunsaturated_fat',
		'omega_6',
		'omega_3',
		'monounsaturated_fat',
		'cholesterol',
		'sodium',
		'potassium',
		'carbohydrate',
		'fibre',
		'sugars',
		'protein',
		'vitamin_a',
		'vitamin_c',
		'calcium',
		'iron',
		'vitamin_d',
		'vitamin_e',
		'vitamin_k',
		'thiamin',
		'riboflavin',
		'niacin',
		'vitamin_b6',
		'folate',
		'vitamin_b12',
		'biotin',
		'pantothenate',
		'phosphorus',
		'iodine',
		'magnesium',
		'zinc',
		'selenium',
		'copper',
		'manganese',
		'chromium',
		'molybdenum',
		'chloride',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'user_id' => 'integer',
		'calories' => 'integer',
		'fat' => 'float',
		'saturated_fat' => 'float',
		'trans_fat' => 'float',
		'polyunsaturated_fat' => 'float',
		'omega_6' => 'float',
		'omega_3' => 'float',
		'monounsaturated_fat' => 'float',
		'cholesterol' => 'float',
		'sodium' => 'float',
		'potassium' => 'float',
		'carbohydrate' => 'float',
		'fibre' => 'float',
		'sugars' => 'float',
		'protein' => 'float',
		'vitamin_a' => 'integer',
		'vitamin_c' => 'integer',
		'calcium' => 'integer',
		'iron' => 'integer',
		'vitamin_d' => 'integer',
		'vitamin_e' => 'integer',
		'vitamin_k' => 'integer',
		'thiamin' => 'integer',
		'riboflavin' => 'integer',
		'niacin' => 'integer',
		'vitamin_b6' => 'integer',
		'folate' => 'integer',
		'vitamin_b12' => 'integer',
		'biotin' => 'integer',
		'pantothenate' => 'integer',
		'phosphorus' => 'integer',
		'iodine' => 'integer',
		'magnesium' => 'integer',
		'zinc' => 'integer',
		'selenium' => 'integer',
		'copper' => 'integer',
		'manganese' => 'integer',
		'chromium' => 'integer',
		'molybdenum' => 'integer',
		'chloride' => 'integer',
	];

	/**
	 * @param  array $data
	 * @return array
	 */
	public function defaultAttributes(array $data) : array // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClass
	{
		return [
			'user_id' => Auth::guard('sanctum')->id(),
		];
	}

	/**
	 * @return array
	 */
	public function defaultFilter() : array
	{
		return [
			'user_id' => [
				'eq' => Auth::guard('sanctum')->id(),
			],
		];
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
			'attributes.note' => [$required, 'max:255'],
			'attributes.date' => [$required, 'date'],
			'attributes.calories' => ['nullable', 'integer'],
			'attributes.fat' => ['nullable', 'numeric'],
			'attributes.saturated_fat' => ['nullable', 'numeric'],
			'attributes.trans_fat' => ['nullable', 'numeric'],
			'attributes.polyunsaturated_fat' => ['nullable', 'numeric'],
			'attributes.omega_6' => ['nullable', 'numeric'],
			'attributes.omega_3' => ['nullable', 'numeric'],
			'attributes.monounsaturated_fat' => ['nullable', 'numeric'],
			'attributes.cholesterol' => ['nullable', 'numeric'],
			'attributes.sodium' => ['nullable', 'numeric'],
			'attributes.potassium' => ['nullable', 'numeric'],
			'attributes.carbohydrate' => ['nullable', 'numeric'],
			'attributes.fibre' => ['nullable', 'numeric'],
			'attributes.sugars' => ['nullable', 'numeric'],
			'attributes.protein' => ['nullable', 'numeric'],
			'attributes.vitamin_a' => ['nullable', 'integer'],
			'attributes.vitamin_c' => ['nullable', 'integer'],
			'attributes.calcium' => ['nullable', 'integer'],
			'attributes.iron' => ['nullable', 'integer'],
			'attributes.vitamin_d' => ['nullable', 'integer'],
			'attributes.vitamin_e' => ['nullable', 'integer'],
			'attributes.vitamin_k' => ['nullable', 'integer'],
			'attributes.thiamin' => ['nullable', 'integer'],
			'attributes.riboflavin' => ['nullable', 'integer'],
			'attributes.niacin' => ['nullable', 'integer'],
			'attributes.vitamin_b6' => ['nullable', 'integer'],
			'attributes.folate' => ['nullable', 'integer'],
			'attributes.vitamin_b12' => ['nullable', 'integer'],
			'attributes.biotin' => ['nullable', 'integer'],
			'attributes.pantothenate' => ['nullable', 'integer'],
			'attributes.phosphorus' => ['nullable', 'integer'],
			'attributes.iodine' => ['nullable', 'integer'],
			'attributes.magnesium' => ['nullable', 'integer'],
			'attributes.zinc' => ['nullable', 'integer'],
			'attributes.selenium' => ['nullable', 'integer'],
			'attributes.copper' => ['nullable', 'integer'],
			'attributes.manganese' => ['nullable', 'integer'],
			'attributes.chromium' => ['nullable', 'integer'],
			'attributes.molybdenum' => ['nullable', 'integer'],
			'attributes.chloride' => ['nullable', 'integer'],
		];
	}

	/**
	 * @return array
	 */
	public function singularRelationships() : array
	{
		return ['user'];
	}

	/**
	 * @return BelongsTo
	 */
	public function user() : BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}