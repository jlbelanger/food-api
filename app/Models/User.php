<?php

namespace App\Models;

use App\Models\Food;
use App\Models\Trackable;
use App\Models\Weight;
use App\Rules\CannotChange;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Jlbelanger\Tapioca\Traits\Resource;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable, Resource, SoftDeletes;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'username',
		'email',
		'password',
		'sex',
		'age',
		'height',
		'activity_level',
		'measurement_units',
		'favourites_only',
		'is_admin',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'age' => 'integer',
		'height' => 'integer',
		'activity_level' => 'integer',
		'favourites_only' => 'boolean',
		'is_admin' => 'boolean',
	];

	/**
	 * @return array
	 */
	public function additionalAttributes() : array
	{
		return ['weight', 'weight_date'];
	}

	/**
	 * @return BelongsToMany
	 */
	public function favourites() : BelongsToMany
	{
		return $this->belongsToMany(Food::class);
	}

	/**
	 * @param  boolean $remember
	 * @return array
	 */
	public function getAuthInfo(bool $remember) : array
	{
		return [
			'id' => $this->id,
			'is_admin' => $this->is_admin,
			'measurement_units' => $this->measurement_units,
			'remember' => $remember,
			'trackables' => $this->trackables()->pluck('trackables.slug')->toArray(),
		];
	}

	/**
	 * @return Weight|null
	 */
	public function getWeightAttribute()
	{
		return $this->weights()->select(['date', 'weight'])->orderBy('date', 'desc')->first();
	}

	/**
	 * @return array
	 */
	public function multiRelationships() : array
	{
		return ['trackables'];
	}

	/**
	 * @param  array  $data
	 * @param  string $method
	 * @return array
	 */
	protected function rules(array $data, string $method) : array // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassBeforeLastUsed
	{
		$required = $method === 'POST' ? 'required' : 'filled';
		$rules = [
			'attributes.username' => [$required, 'alpha_num', 'max:255'],
			'attributes.email' => [new CannotChange()],
			'attributes.password' => [new CannotChange()],
			'attributes.sex' => ['nullable', Rule::in(['f', 'm'])],
			'attributes.age' => ['nullable', 'integer'],
			'attributes.height' => ['nullable', 'integer'],
			'attributes.activity_level' => ['nullable', 'integer'],
			'attributes.measurement_units' => [Rule::in(['i', 'm'])],
			'attributes.favourites_only' => ['boolean'],
			'attributes.is_admin' => [new CannotChange()],
		];

		if (Auth::guard('sanctum')->user()->username === 'demo') {
			$rules['attributes.username'][] = new CannotChange();
		}

		$unique = Rule::unique($this->getTable(), 'username');
		if ($this->id) {
			$unique->ignore($this->id);
		}
		$rules['attributes.username'][] = $unique;

		$unique = Rule::unique($this->getTable(), 'email');
		if ($this->id) {
			$unique->ignore($this->id);
		}
		$rules['attributes.email'][] = $unique;

		return $rules;
	}

	/**
	 * @return BelongsToMany
	 */
	public function trackables() : BelongsToMany
	{
		return $this->belongsToMany(Trackable::class);
	}

	/**
	 * @return HasMany
	 */
	public function weights() : HasMany
	{
		return $this->hasMany(Weight::class);
	}

	/**
	 * @return array
	 */
	public function whitelistedAttributes() : array
	{
		return array_merge($this->fillable, ['password_confirmation']);
	}
}
