<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
	 * @return array
	 */
	public function rules() : array
	{
		return [
			'data.attributes.name' => [$this->requiredOnCreate(), 'max:255'],
			'data.attributes.slug' => [$this->requiredOnCreate(), 'max:255', $this->unique('slug')],
			'data.attributes.units' => ['max:2'],
		];
	}
}
