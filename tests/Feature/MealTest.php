<?php

namespace Tests\Feature;

use App\Models\Meal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MealTest extends TestCase
{
	use RefreshDatabase;

	protected $path = '/meals';

	protected function setUp() : void
	{
		parent::setUp();
		$this->user = User::factory()->create();
		$this->otherUser = User::factory()->create(['username' => 'bar', 'email' => 'bar@example.com']);
		$this->meal = Meal::factory()->create(['user_id' => $this->user->id]);
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

	public function storeProvider() : array
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

	/**
	 * @dataProvider storeProvider
	 */
	public function testStore(array $args) : void
	{
		$response = $this->actingAs($this->user)->json('POST', $this->path, $args['body']);
		if (!empty($response['data']['id'])) {
			$args['response'] = $this->replaceToken('%id%', $response['data']['id'], $args['response']);
		}
		$response->assertExactJson($args['response']);
		$response->assertStatus($args['code']);
	}

	public function showProvider() : array
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
					],
				],
				'code' => 200,
			]],
		];
	}

	/**
	 * @dataProvider showProvider
	 */
	public function testShow(array $args) : void
	{
		$args['response'] = $this->replaceToken('%id%', $this->meal->id, $args['response']);
		$response = $this->actingAs($this->user)->json('GET', $this->path . '/' . $this->{$args['key']}->id);
		$response->assertExactJson($args['response']);
		$response->assertStatus($args['code']);
	}

	public function updateProvider() : array
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

	/**
	 * @dataProvider updateProvider
	 */
	public function testUpdate(array $args) : void
	{
		$args['body'] = $this->replaceToken('%id%', $this->meal->id, $args['body']);
		$args['response'] = $this->replaceToken('%id%', $this->meal->id, $args['response']);
		$response = $this->actingAs($this->user)->json('PUT', $this->path . '/' . $this->{$args['key']}->id, $args['body']);
		$response->assertExactJson($args['response']);
		$response->assertStatus($args['code']);
	}

	public function destroyProvider() : array
	{
		return [
			[[
				'key' => 'meal',
				'response' => null,
				'code' => 204,
			]],
		];
	}

	/**
	 * @dataProvider destroyProvider
	 */
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
