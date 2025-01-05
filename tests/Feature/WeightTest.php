<?php

namespace Tests\Feature;

use App\Models\Weight;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class WeightTest extends TestCase
{
	use RefreshDatabase;

	protected $path = '/weights';

	protected $user;

	protected $otherUser;

	protected $weight;

	protected $otherWeight;

	protected function setUp() : void
	{
		parent::setUp();
		$this->user = User::factory()->create();
		$this->otherUser = User::factory()->create(['username' => 'bar', 'email' => 'bar@example.com']);
		$this->weight = Weight::factory()->create(['user_id' => $this->user->id]);
		$this->otherWeight = Weight::factory()->create(['user_id' => $this->otherUser->id]);
	}

	public function testIndex() : void
	{
		$response = $this->actingAs($this->weight->user)->json('GET', $this->path);
		$response->assertExactJson([
			'data' => [
				[
					'id' => (string) $this->weight->id,
					'type' => 'weights',
					'attributes' => [
						'weight' => 123.4,
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
						'type' => 'weights',
						'attributes' => [
							'weight' => 432.1,
							'date' => '2004-05-05',
						],
					],
				],
				'response' => [
					'data' => [
						'id' => '%id%',
						'type' => 'weights',
						'attributes' => [
							'weight' => 432.1,
							'date' => '2004-05-05',
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
				'key' => 'weight',
				'response' => [
					'data' => [
						'id' => '%id%',
						'type' => 'weights',
						'attributes' => [
							'weight' => 123.4,
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
		$args['response'] = $this->replaceToken('%id%', (string) $this->weight->id, $args['response']);
		$response = $this->actingAs($this->user)->json('GET', $this->path . '/' . $this->{$args['key']}->id);
		$response->assertExactJson($args['response']);
		$response->assertStatus($args['code']);
	}

	public static function updateProvider() : array
	{
		return [
			[[
				'key' => 'weight',
				'body' => [
					'data' => [
						'id' => '%id%',
						'type' => 'weights',
						'attributes' => [
							'weight' => 432.1,
							'date' => '2004-05-05',
						],
					],
				],
				'response' => [
					'data' => [
						'id' => '%id%',
						'type' => 'weights',
						'attributes' => [
							'weight' => 432.1,
							'date' => '2004-05-05',
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
		$args['body'] = $this->replaceToken('%id%', (string) $this->weight->id, $args['body']);
		$args['response'] = $this->replaceToken('%id%', (string) $this->weight->id, $args['response']);
		$response = $this->actingAs($this->user)->json('PUT', $this->path . '/' . $this->{$args['key']}->id, $args['body']);
		$response->assertExactJson($args['response']);
		$response->assertStatus($args['code']);
	}

	public static function destroyProvider() : array
	{
		return [
			[[
				'key' => 'weight',
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
