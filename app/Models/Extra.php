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
	 * The attributes that should be cast.
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
	 * @return array
	 */
	public function rules() : array
	{
		return [
			'data.attributes.note' => [$this->requiredOnCreate(), 'max:255'],
			'data.attributes.date' => [$this->requiredOnCreate(), 'date'],
			'data.attributes.calories' => ['nullable', 'integer'],
			'data.attributes.fat' => ['nullable', 'numeric'],
			'data.attributes.saturated_fat' => ['nullable', 'numeric'],
			'data.attributes.trans_fat' => ['nullable', 'numeric'],
			'data.attributes.polyunsaturated_fat' => ['nullable', 'numeric'],
			'data.attributes.omega_6' => ['nullable', 'numeric'],
			'data.attributes.omega_3' => ['nullable', 'numeric'],
			'data.attributes.monounsaturated_fat' => ['nullable', 'numeric'],
			'data.attributes.cholesterol' => ['nullable', 'numeric'],
			'data.attributes.sodium' => ['nullable', 'numeric'],
			'data.attributes.potassium' => ['nullable', 'numeric'],
			'data.attributes.carbohydrate' => ['nullable', 'numeric'],
			'data.attributes.fibre' => ['nullable', 'numeric'],
			'data.attributes.sugars' => ['nullable', 'numeric'],
			'data.attributes.protein' => ['nullable', 'numeric'],
			'data.attributes.vitamin_a' => ['nullable', 'integer'],
			'data.attributes.vitamin_c' => ['nullable', 'integer'],
			'data.attributes.calcium' => ['nullable', 'integer'],
			'data.attributes.iron' => ['nullable', 'integer'],
			'data.attributes.vitamin_d' => ['nullable', 'integer'],
			'data.attributes.vitamin_e' => ['nullable', 'integer'],
			'data.attributes.vitamin_k' => ['nullable', 'integer'],
			'data.attributes.thiamin' => ['nullable', 'integer'],
			'data.attributes.riboflavin' => ['nullable', 'integer'],
			'data.attributes.niacin' => ['nullable', 'integer'],
			'data.attributes.vitamin_b6' => ['nullable', 'integer'],
			'data.attributes.folate' => ['nullable', 'integer'],
			'data.attributes.vitamin_b12' => ['nullable', 'integer'],
			'data.attributes.biotin' => ['nullable', 'integer'],
			'data.attributes.pantothenate' => ['nullable', 'integer'],
			'data.attributes.phosphorus' => ['nullable', 'integer'],
			'data.attributes.iodine' => ['nullable', 'integer'],
			'data.attributes.magnesium' => ['nullable', 'integer'],
			'data.attributes.zinc' => ['nullable', 'integer'],
			'data.attributes.selenium' => ['nullable', 'integer'],
			'data.attributes.copper' => ['nullable', 'integer'],
			'data.attributes.manganese' => ['nullable', 'integer'],
			'data.attributes.chromium' => ['nullable', 'integer'],
			'data.attributes.molybdenum' => ['nullable', 'integer'],
			'data.attributes.chloride' => ['nullable', 'integer'],
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
