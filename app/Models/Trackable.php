<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Jlbelanger\Tapioca\Traits\Resource;

class Trackable extends Model
{
	use HasFactory, Resource;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'name',
		'slug',
		'units',
	];

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var boolean
	 */
	public $timestamps = false;

	/**
	 * @param  array  $data
	 * @param  string $method
	 * @return array
	 */
	protected function rules(array $data, string $method) : array // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInExtendedClassBeforeLastUsed
	{
		$required = $method === 'POST' ? 'required' : 'filled';
		$rules = [
			'attributes.name' => [$required, 'max:255'],
			'attributes.slug' => [$required, 'max:255'],
			'attributes.units' => ['max:2'],
		];

		$unique = Rule::unique($this->getTable(), 'slug');
		if ($this->id) {
			$unique->ignore($this->id);
		}
		$rules['attributes.slug'][] = $unique;

		return $rules;
	}
}
