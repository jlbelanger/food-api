<?php

namespace Tests\Feature;

use App\Models\Food;
use App\Models\FoodMeal;
use App\Models\Meal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class MealTest extends TestCase
{
	use RefreshDatabase;

	protected $path = '/meals';

	protected $user;

	protected $otherUser;

	protected $food;

	protected $meal;

	protected $foodMeal;

	protected $otherMeal;

	protected function setUp() : void
	{
		parent::setUp();
		$this->user = User::factory()->create();
		$this->otherUser = User::factory()->create(['username' => 'bar', 'email' => 'bar@example.com']);
		$this->food = Food::factory()->create();
		$this->meal = Meal::factory()->create(['user_id' => $this->user->id]);
		$this->foodMeal = FoodMeal::factory()->create(['food_id' => $this->food->id, 'meal_id' => $this->meal->id]);
		$this->otherMeal = Meal::factory()->create(['user_id' => $this->otherUser->id]);
	}

	public function testIndex() : void
	{
		$response = $this->actingAs($this->user)->json('GET', $this->path);
		$response->assertExactJson([
			'data' => [
				[
					'id' => (string) $this->meal->id,
					'type' => 'meals',
					'attributes' => [
						'name' => 'Breakfast',
						'is_favourite' => false,
					],
				],
			],
		]);
		$response->assertStatus(200);
	}

	public static function storeProvider() : array
	{
		return [
			[[
				'body' => [
					'data' => [
						'type' => 'meals',
						'attributes' => [
							'name' => 'Lunch',
							'is_favourite' => false,
						],
					],
				],
				'response' => [
					'data' => [
						'id' => '%id%',
						'type' => 'meals',
						'attributes' => [
							'name' => 'Lunch',
							'is_favourite' => false,
						],
					],
				],
				'code' => 201,
			]],
		];
	}

	#[DataProvider('storeProvider')]
	public function testStore(array $args) : void
	{
		$response = $this->actingAs($this->user)->json('POST', $this->path, $args['body']);
		if (!empty($response['data']['id'])) {
			$args['response'] = $this->replaceToken('%id%', $response['data']['id'], $args['response']);
		}
		$response->assertExactJson($args['response']);
		$response->assertStatus($args['code']);
	}

	public static function showProvider() : array
	{
		return [
			[[
				'key' => 'meal',
				'response' => [
					'data' => [
						'id' => '%id%',
						'type' => 'meals',
						'attributes' => [
							'name' => 'Breakfast',
							'is_favourite' => false,
						],
						'relationships' => [
							'foods' => [
								'data' => [
									[
										'id' => '%food_meal_id%',
										'type' => 'food-meal',
									],
								],
							],
						],
					],
					'included' => [
						[
							'id' => '%food_meal_id%',
							'type' => 'food-meal',
							'attributes' => [
								'user_serving_size' => 1,
							],
							'relationships' => [
								'food' => [
									'data' => [
										'id' => '%food_id%',
										'type' => 'food',
									],
								],
							],
						],
						[
							'id' => '%food_id%',
							'type' => 'food',
							'attributes' => [
								'name' => 'Apple',
								'slug' => 'apple',
								'serving_size' => 1.5,
								'serving_units' => null,
								'front_image' => null,
								'info_image' => null,
								'calories' => null,
								'fat' => null,
								'saturated_fat' => null,
								'trans_fat' => null,
								'polyunsaturated_fat' => null,
								'omega_6' => null,
								'omega_3' => null,
								'monounsaturated_fat' => null,
								'cholesterol' => null,
								'sodium' => null,
								'potassium' => null,
								'carbohydrate' => null,
								'fibre' => null,
								'sugars' => null,
								'protein' => null,
								'vitamin_a' => null,
								'vitamin_c' => null,
								'calcium' => null,
								'iron' => null,
								'vitamin_d' => null,
								'vitamin_e' => null,
								'vitamin_k' => null,
								'thiamin' => null,
								'riboflavin' => null,
								'niacin' => null,
								'vitamin_b6' => null,
								'folate' => null,
								'vitamin_b12' => null,
								'biotin' => null,
								'pantothenate' => null,
								'phosphorus' => null,
								'iodine' => null,
								'magnesium' => null,
								'zinc' => null,
								'selenium' => null,
								'copper' => null,
								'manganese' => null,
								'chromium' => null,
								'molybdenum' => null,
								'chloride' => null,
								'is_favourite' => false,
								'is_verified' => true,
								'deleteable' => false,
							],
						],
					],
				],
				'code' => 200,
			]],
		];
	}

	#[DataProvider('showProvider')]
	public function testShow(array $args) : void
	{
		$args['response'] = $this->replaceToken('%id%', (string) $this->meal->id, $args['response']);
		$args['response'] = $this->replaceToken('%food_id%', (string) $this->food->id, $args['response']);
		$args['response'] = $this->replaceToken('%food_meal_id%', (string) $this->foodMeal->id, $args['response']);
		$response = $this->actingAs($this->user)->json('GET', $this->path . '/' . $this->{$args['key']}->id . '?include=foods,foods.food');
		$response->assertExactJson($args['response']);
		$response->assertStatus($args['code']);
	}

	public static function updateProvider() : array
	{
		return [
			[[
				'key' => 'meal',
				'body' => [
					'data' => [
						'id' => '%id%',
						'type' => 'meals',
						'attributes' => [
							'name' => 'Lunch',
							'is_favourite' => false,
						],
					],
				],
				'response' => [
					'data' => [
						'id' => '%id%',
						'type' => 'meals',
						'attributes' => [
							'name' => 'Lunch',
							'is_favourite' => false,
						],
					],
				],
				'code' => 200,
			]],
		];
	}

	#[DataProvider('updateProvider')]
	public function testUpdate(array $args) : void
	{
		$args['body'] = $this->replaceToken('%id%', (string) $this->meal->id, $args['body']);
		$args['response'] = $this->replaceToken('%id%', (string) $this->meal->id, $args['response']);
		$response = $this->actingAs($this->user)->json('PUT', $this->path . '/' . $this->{$args['key']}->id, $args['body']);
		$response->assertExactJson($args['response']);
		$response->assertStatus($args['code']);
	}

	public static function destroyProvider() : array
	{
		return [
			[[
				'key' => 'meal',
				'response' => null,
				'code' => 204,
			]],
		];
	}

	#[DataProvider('destroyProvider')]
	public function testDestroy(array $args) : void
	{
		$response = $this->actingAs($this->user)->json('DELETE', $this->path . '/' . $this->{$args['key']}->id);
		if ($args['response']) {
			$response->assertExactJson($args['response']);
			$response->assertStatus($args['code']);
		} else {
			$response->assertNoContent($args['code']);
		}
	}
}
