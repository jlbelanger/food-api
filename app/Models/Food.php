<?php

namespace App\Models;

use App\Helpers\Image;
use App\Models\Entry;
use App\Models\FoodMeal;
use App\Models\Meal;
use App\Models\User;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Jlbelanger\Tapioca\Traits\Resource;

class Food extends Model
{
	use HasFactory, Resource, SoftDeletes;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'name',
		'slug',
		'user_id',
		'serving_size',
		'serving_units',
		'front_image',
		'info_image',
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
		'serving_size' => 'float',
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
	 * @param  User $user
	 * @return void
	 */
	public function addFavourite(User $user) : void
	{
		DB::table('food_user')->insert([
			'food_id' => $this->id,
			'user_id' => $user->id,
			'created_at' => date('Y-m-d H:i:s'),
		]);
		$user->clearFavouritesCache();
	}

	/**
	 * @return array
	 */
	public function additionalAttributes() : array
	{
		return ['deleteable', 'is_favourite', 'is_verified'];
	}

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
	 * @return HasMany
	 */
	public function entries() : HasMany
	{
		return $this->hasMany(Entry::class, 'food_id');
	}

	/**
	 * @return boolean
	 */
	public function getDeleteableAttribute() : bool
	{
		return !$this->entries()->exists() && !$this->meals()->exists();
	}

	/**
	 * @return boolean
	 */
	public function getIsFavouriteAttribute() : bool
	{
		$user = Auth::guard('sanctum')->user();
		if (!$user) {
			return false;
		}
		return in_array($this->id, $user->favouriteFoodIds());
	}

	/**
	 * @return boolean
	 */
	public function getIsVerifiedAttribute() : bool
	{
		return empty($this->user_id);
	}

	/**
	 * @return HasMany
	 */
	public function meals() : HasMany
	{
		return $this->hasMany(FoodMeal::class, 'food_id');
	}

	/**
	 * @return array
	 */
	public function multiRelationships() : array
	{
		return ['user_entries', 'user_meals'];
	}

	/**
	 * @param  string $key
	 * @param  string $filename
	 * @return void
	 */
	public function processFile(string $key, string $filename) : void // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassBeforeLastUsed
	{
		if (!$filename) {
			return;
		}

		$path = public_path($filename);
		list($oldWidth, $oldHeight, $fileType) = getimagesize($path);
		Image::resize($oldWidth, $oldHeight, 500, $path, $path, $fileType);
	}

	/**
	 * @return array
	 */
	public function rules() : array
	{
		$rules = [
			'data.attributes.name' => [$this->requiredOnCreate(), 'max:255'],
			'data.attributes.slug' => [$this->requiredOnCreate(), 'max:255', $this->unique('slug')],
			'data.attributes.serving_size' => [$this->requiredOnCreate(), 'regex:/(\d+ )?\d+(\/\d+|\.\d+)?/'],
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

		if (!Auth::guard('sanctum')->user()->is_admin) {
			$rules['data.relationships.user'] = ['prohibited'];
		}

		return $rules;
	}

	/**
	 * @param  string $value
	 * @return void
	 */
	public function setServingSizeAttribute($value) // phpcs:ignore Squiz.Commenting.FunctionComment.ScalarTypeHintMissing
	{
		if (strpos($value, '/') !== false) {
			if (strpos($value, ' ') !== false) {
				$whole = preg_replace('/^([^ ]+) .*$/', '$1', $value);
				$value = preg_replace('/^([^ ]+) /', '', $value);
			} elseif (strpos($value, '-') !== false) {
				$whole = preg_replace('/^([^-]+)-.*$/', '$1', $value);
				$value = preg_replace('/^([^-]+)-/', '', $value);
			} else {
				$whole = 0;
			}

			$value = explode('/', $value);

			if (empty($value[1])) {
				$value = $whole;
			} else {
				$value = $whole + round($value[0] / $value[1], 2);
			}
		}

		$this->attributes['serving_size'] = $value;
	}

	/**
	 * @param  string $value
	 * @return void
	 */
	public function setServingUnitsAttribute($value) // phpcs:ignore Squiz.Commenting.FunctionComment.ScalarTypeHintMissing
	{
		if (!$value) {
			$this->attributes['serving_units'] = null;
			return;
		}

		$value = strtolower($value);

		$replace = [
			'boxes' => 'box',
			'cherries' => 'cherry',
			'dashes' => 'dash',
			'dishes' => 'dish',
			'gr' => 'g',
			'gram' => 'g',
			'grams' => 'g',
			'grs' => 'g',
			'inches' => 'inch',
			'leaves' => 'leaf',
			'ounce' => 'oz',
			'ounces' => 'oz',
			'pastries' => 'pastry',
			'patties' => 'patty',
			'pc' => 'piece',
			'pcs' => 'piece',
			'potatoes' => 'potato',
			'pouches' => 'pouch',
			'sandwiches' => 'sandwich',
			'tablespoon' => 'tbsp',
			'tablespoons' => 'tbsp',
			'tblsp' => 'tbsp',
			'tblsps' => 'tbsp',
			'teaspoon' => 'tsp',
			'teaspoons' => 'tsp',
			'tomatoes' => 'tomato',
		];
		if (!empty($replace[$value])) {
			$value = $replace[$value];
		}

		$value = preg_replace('/s$/', '', $value);

		$this->attributes['serving_units'] = $value;
	}

	/**
	 * @return array
	 */
	public function singularRelationships() : array
	{
		return ['user'];
	}

	/**
	 * @param  array $meta
	 * @return void
	 */
	public function updateMeta(array $meta) : void
	{
		if (!empty($meta['is_favourite'])) {
			$this->addFavourite(Auth::guard('sanctum')->user());
		}
	}

	/**
	 * @param  string $key
	 * @param  string $filename
	 * @param  array  $data
	 * @return string
	 */
	public function uploadedFilename(string $key, string $filename, array $data = []) : string
	{
		$slug = !empty($data['attributes']['slug']) ? $data['attributes']['slug'] : $this->slug;
		$pathInfo = pathinfo($filename);
		$extension = strtolower($pathInfo['extension']);
		if ($extension === 'jpeg') {
			$extension = 'jpg';
		}
		return '/uploads/food/' . $key . '/' . strtolower(Str::random(16)) . '/' . $slug . '.' . $extension;
	}

	/**
	 * @return BelongsTo
	 */
	public function user() : BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * @return HasMany
	 */
	public function userEntries() : HasMany
	{
		$user = Auth::guard('sanctum')->user();
		$output = $this->hasMany(Entry::class, 'food_id');
		if (!$user->is_admin) {
			$output = $output->where('entries.user_id', '=', $user->id);
		} else {
			$output = $output->with('user');
		}
		$output = $output->orderBy('entries.date', 'desc');
		return $output;
	}

	/**
	 * @return HasManyThrough
	 */
	public function userMeals() : HasManyThrough
	{
		$user = Auth::guard('sanctum')->user();
		$output = $this->hasManyThrough(Meal::class, FoodMeal::class, 'food_id', 'id', 'id', 'meal_id');
		if (!$user->is_admin) {
			$output = $output->where('meals.user_id', '=', $user->id);
		} else {
			$output = $output->with('user');
		}
		$output = $output->orderBy('meals.name');
		return $output;
	}
}
