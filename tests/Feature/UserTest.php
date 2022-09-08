<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function testListUser()
    {
        $response = User::factory()->create();

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testShowUserReturnNotFoundWhenDoesntExists()
    {
        $response = $this->get('/user/show/4');
        $response->assertStatus(404);
    }
}
