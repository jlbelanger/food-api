<?php

namespace Tests\Feature;

use App\Models\Entry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class EntryTest extends TestCase
{
	use RefreshDatabase;

	protected $path = '/entries';

	protected $user;

	protected $otherUser;

	protected $food;

	protected $entry;

	protected $otherEntry;

	protected function setUp() : void
	{
		parent::setUp();
		$this->user = User::factory()->create();
		$this->otherUser = User::factory()->create(['username' => 'bar', 'email' => 'bar@example.com']);
		$this->food = \App\Models\Food::factory()->create();
		$this->entry = Entry::factory()->create(['food_id' => $this->food->id, 'user_id' => $this->user->id]);
		$this->otherEntry = Entry::factory()->create(['food_id' => $this->food->id, 'user_id' => $this->otherUser->id]);
	}

	public function testIndex() : void
	{
		$response = $this->actingAs($this->entry->user)->json('GET', $this->path);
		$response->assertExactJson([
			'data' => [
				[
					'id' => (string) $this->entry->id,
					'type' => 'entries',
					'attributes' => [
						'user_serving_size' => 1,
						'date' => '2001-02-03',
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
						'type' => 'entries',
						'attributes' => [
							'date' => '2004-05-06',
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
				],
				'response' => [
					'data' => [
						'id' => '%id%',
						'type' => 'entries',
						'attributes' => [
							'user_serving_size' => 1.5,
							'date' => '2004-05-06',
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
		$args['body'] = $this->replaceToken('%food_id%', (string) $this->food->id, $args['body']);
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
				'key' => 'entry',
				'response' => [
					'data' => [
						'id' => '%id%',
						'type' => 'entries',
						'attributes' => [
							'user_serving_size' => 1,
							'date' => '2001-02-03',
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
		$args['response'] = $this->replaceToken('%id%', (string) $this->entry->id, $args['response']);
		$response = $this->actingAs($this->user)->json('GET', $this->path . '/' . $this->{$args['key']}->id);
		$response->assertExactJson($args['response']);
		$response->assertStatus($args['code']);
	}

	public static function updateProvider() : array
	{
		return [
			[[
				'key' => 'entry',
				'body' => [
					'data' => [
						'id' => '%id%',
						'type' => 'entries',
						'attributes' => [
							'user_serving_size' => 2,
							'date' => '2004-05-06',
						],
					],
				],
				'response' => [
					'data' => [
						'id' => '%id%',
						'type' => 'entries',
						'attributes' => [
							'user_serving_size' => 2,
							'date' => '2004-05-06',
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
		$args['body'] = $this->replaceToken('%id%', (string) $this->entry->id, $args['body']);
		$args['response'] = $this->replaceToken('%id%', (string) $this->entry->id, $args['response']);
		$response = $this->actingAs($this->user)->json('PUT', $this->path . '/' . $this->{$args['key']}->id, $args['body']);
		$response->assertExactJson($args['response']);
		$response->assertStatus($args['code']);
	}

	public static function destroyProvider() : array
	{
		return [
			[[
				'key' => 'entry',
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
