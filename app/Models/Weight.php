<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Jlbelanger\Tapioca\Traits\Resource;

class Weight extends Model
{
	use HasFactory, Resource;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'user_id',
		'weight',
		'date',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'user_id' => 'integer',
		'weight' => 'float',
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
		$rules = [
			'data.attributes.weight' => [$this->requiredOnCreate(), 'numeric'],
			'data.attributes.date' => [$this->requiredOnCreate(), 'date'],
		];

		$userId = request()->input('data.relationships.user.data.id', $this->user_id);
		if (empty($userId)) {
			$userId = Auth::guard('sanctum')->id();
		}
		$unique = Rule::unique($this->getTable(), 'date')->where(function ($query) use ($userId) {
			return $query->where('user_id', '=', $userId);
		});
		if ($this->id) {
			$unique->ignore($this->id);
		}
		$rules['data.attributes.date'][] = $unique;

		return $rules;
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
