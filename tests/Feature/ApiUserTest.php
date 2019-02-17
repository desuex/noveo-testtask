<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiUserTest extends TestCase
{
    use WithFaker;
    /**
     * Test the users list api route
     *
     * @return void
     */
    public function testList()
    {
        $response = $this->get('/api/users');

        $response->assertStatus(200)->assertJson([]);
    }

    private function createGroup()
    {
        return  $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('POST', '/api/groups', ['name' => $this->faker->unique()->word]);

    }

    private function createUser($groupId)
    {
        return $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('POST', '/api/users',
            ['password' => $this->faker->unique()->password, 'first_name' => $this->faker->unique()->firstName,
                'last_name' => $this->faker->unique()->lastName,
                'email' => $this->faker->unique()->email, 'group_id' => $groupId]);
    }

    private function updateUser($userId, $groupId)
    {
        return $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('PATCH', '/api/users/' . $userId,
            ['password' => $this->faker->unique()->password, 'first_name' => $this->faker->unique()->firstName,
                'last_name' => $this->faker->unique()->lastName,
            'email' => $this->faker->unique()->email, 'group_id' => $groupId]);
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreatingUser()
    {
        $response = $this->createUser($this->createGroup()->json('id'));
        $response
            ->assertStatus(201)
            ->assertJson([
                'created' => true,
            ]);
    }

    public function testUpdatingUser()
    {
        $response = $this->createUser($this->createGroup()->json('id'));
        $response
            ->assertStatus(201)
            ->assertJson([
                'created' => true,
            ]);
        $userId = $response->json('id');
        $this->assertGreaterThan(0,$userId);

        $response = $this->createGroup();
        $newGroupId = $response->json('id');
        $this->assertGreaterThan(0,$newGroupId);

        $response = $this->updateUser($userId,$newGroupId);
        $response
            ->assertStatus(200)
            ->assertJson([
                'updated' => true,
            ]);

        $response = $this->get('/api/users/'.$userId);
        $response->assertStatus(200);
        $response->assertJson(['id' => $userId]);
    }
}
