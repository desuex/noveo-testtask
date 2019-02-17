<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiGroupTest extends TestCase
{
    use WithFaker;


    private function createGroup()
    {
        return $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('POST', '/api/groups', ['name' => $this->faker->unique()->word]);

    }

    /**
     * Test the group list api route
     *
     * @return void
     */
    public function testList()
    {
        $response = $this->get('/api/groups');

        $response->assertStatus(200)->assertJson([]);
    }

    /**
     * Test group creation
     *
     * @return void
     */
    public function testCreatingGroup()
    {
        $response = $this->createGroup();

        $response
            ->assertStatus(201)
            ->assertJson([
                'created' => true,
            ]);
    }

    public function testUpdatingGroup()
    {
        //Creating group to be modified
        $response = $this->createGroup();
        $insertedId = $response->json('id');
        $this->assertGreaterThan(0, $insertedId);

        $newGroupName = $this->faker->unique()->word;
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('PATCH', '/api/groups/' . $insertedId, ['name' => $newGroupName]);
        $response
            ->assertStatus(200)
            ->assertJson([
                'updated' => true,
            ]);

        $response = $this->get('/api/groups/' . $insertedId);
        $response->assertStatus(200);
        $response->assertJson(['name' => $newGroupName]);

    }

    public function testNameFieldValidation()
    {
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('POST', '/api/groups', ['name' => null]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(["name"]);
    }

    public function testUpdateImpossibleGroup()
    {
        $groupId = -1;

        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('PATCH', '/api/group/' . $groupId,
            []);
        $response
            ->assertStatus(404);
    }
}
