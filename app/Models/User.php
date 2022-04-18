<?php

namespace App\Models;

use App\Rules\CannotChange;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
		];

		$unique = Rule::unique($this->getTable(), 'username');
		if ($this->id) {
			$unique->ignore($this->id);
		}
		$rules['attributes.username'][] = $unique;

		return $rules;
	}

	/**
	 * @return array
	 */
	public function whitelistedAttributes() : array
	{
		return array_merge($this->fillable, ['password_confirmation']);
	}
}
