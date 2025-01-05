<?php

namespace Tests\Feature;

use App\Models\Extra;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ExtraTest extends TestCase
{
	use RefreshDatabase;

	protected $path = '/extras';

	protected $user;

	protected $otherUser;

	protected $extra;

	protected $otherExtra;

	protected function setUp() : void
	{
		parent::setUp();
		$this->user = User::factory()->create();
		$this->otherUser = User::factory()->create(['username' => 'bar', 'email' => 'bar@example.com']);
		$this->extra = Extra::factory()->create(['user_id' => $this->user->id]);
		$this->otherExtra = Extra::factory()->create(['user_id' => $this->otherUser->id]);
	}

	public function testIndex() : void
	{
		$response = $this->actingAs($this->user)->json('GET', $this->path);
		$response->assertExactJson([
			'data' => [
				[
					'id' => (string) $this->extra->id,
					'type' => 'extras',
					'attributes' => [
						'note' => 'Foo',
						'date' => '2001-02-03',
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
						'type' => 'extras',
						'attributes' => [
							'note' => 'Bar',
							'date' => '2004-05-06',
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
						],
					],
				],
				'response' => [
					'data' => [
						'id' => '%id%',
						'type' => 'extras',
						'attributes' => [
							'note' => 'Bar',
							'date' => '2004-05-06',
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
				'key' => 'extra',
				'response' => [
					'data' => [
						'id' => '%id%',
						'type' => 'extras',
						'attributes' => [
							'note' => 'Foo',
							'date' => '2001-02-03',
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
		$args['response'] = $this->replaceToken('%id%', (string) $this->extra->id, $args['response']);
		$response = $this->actingAs($this->user)->json('GET', $this->path . '/' . $this->{$args['key']}->id);
		$response->assertExactJson($args['response']);
		$response->assertStatus($args['code']);
	}

	public static function updateProvider() : array
	{
		return [
			[[
				'key' => 'extra',
				'body' => [
					'data' => [
						'id' => '%id%',
						'type' => 'extras',
						'attributes' => [
							'note' => 'Bar',
							'date' => '2004-05-06',
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
						],
					],
				],
				'response' => [
					'data' => [
						'id' => '%id%',
						'type' => 'extras',
						'attributes' => [
							'note' => 'Bar',
							'date' => '2004-05-06',
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
		$args['body'] = $this->replaceToken('%id%', (string) $this->extra->id, $args['body']);
		$args['response'] = $this->replaceToken('%id%', (string) $this->extra->id, $args['response']);
		$response = $this->actingAs($this->user)->json('PUT', $this->path . '/' . $this->{$args['key']}->id, $args['body']);
		$response->assertExactJson($args['response']);
		$response->assertStatus($args['code']);
	}

	public static function destroyProvider() : array
	{
		return [
			[[
				'key' => 'extra',
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
