<?php

namespace Tests\Feature;

use App\Models\Trackable;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TrackableTest extends TestCase
{
	use RefreshDatabase;

	protected $path = '/trackables';

	protected $user;

	protected $trackable;

	protected function setUp() : void
	{
		parent::setUp();
		$this->user = User::factory()->create(['is_admin' => true]);
		$this->trackable = Trackable::factory()->create();
	}

	public function testIndex() : void
	{
		$response = $this->actingAs($this->user)->json('GET', $this->path);
		$response->assertExactJson([
			'data' => [
				[
					'id' => (string) $this->trackable->id,
					'type' => 'trackables',
					'attributes' => [
						'name' => 'Calories',
						'slug' => 'calories',
						'units' => null,
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
						'type' => 'trackables',
						'attributes' => [
							'name' => 'Fat',
							'slug' => 'fat',
							'units' => null,
						],
					],
				],
				'response' => [
					'data' => [
						'id' => '%id%',
						'type' => 'trackables',
						'attributes' => [
							'name' => 'Fat',
							'slug' => 'fat',
							'units' => null,
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
				'key' => 'trackable',
				'response' => [
					'data' => [
						'id' => '%id%',
						'type' => 'trackables',
						'attributes' => [
							'name' => 'Calories',
							'slug' => 'calories',
							'units' => null,
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
		$args['response'] = $this->replaceToken('%id%', (string) $this->trackable->id, $args['response']);
		$response = $this->actingAs($this->user)->json('GET', $this->path . '/' . $this->{$args['key']}->id);
		$response->assertExactJson($args['response']);
		$response->assertStatus($args['code']);
	}

	public static function updateProvider() : array
	{
		return [
			[[
				'key' => 'trackable',
				'body' => [
					'data' => [
						'id' => '%id%',
						'type' => 'trackables',
						'attributes' => [
							'name' => 'Fat',
							'slug' => 'fat',
							'units' => null,
						],
					],
				],
				'response' => [
					'data' => [
						'id' => '%id%',
						'type' => 'trackables',
						'attributes' => [
							'name' => 'Fat',
							'slug' => 'fat',
							'units' => null,
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
		$args['body'] = $this->replaceToken('%id%', (string) $this->trackable->id, $args['body']);
		$args['response'] = $this->replaceToken('%id%', (string) $this->trackable->id, $args['response']);
		$response = $this->actingAs($this->user)->json('PUT', $this->path . '/' . $this->{$args['key']}->id, $args['body']);
		$response->assertExactJson($args['response']);
		$response->assertStatus($args['code']);
	}

	public static function destroyProvider() : array
	{
		return [
			[[
				'key' => 'trackable',
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
