<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiGroupTest extends TestCase
{
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
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreatingGroup()
    {
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('POST', '/api/groups', ['name' => 'group name']);

        $response
            ->assertStatus(201)
            ->assertJson([
                'created' => true,
            ]);
    }

    public function testUpdatingGroup()
    {
        //Creating group to be modified
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('POST', '/api/groups', ['name' => 'group name to be updated']);
        $insertedId = $response->json('id');
        $this->assertGreaterThan(0,$insertedId);

        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->json('PATCH', '/api/groups/'.$insertedId, ['name' => 'new group name']);
        $response
            ->assertStatus(200)
            ->assertJson([
                'updated' => true,
            ]);

        $response = $this->get('/api/groups/'.$insertedId);
        $response->assertStatus(200);
        $response->assertJson(['name' => 'new group name']);





    }
}
