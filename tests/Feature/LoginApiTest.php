<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class LoginApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_login()
    {
        $user = User::factory()->create([
            'email' => 'developer@gmail.com',
            'password' => bcrypt('adminpass'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'developer@gmail.com',
            'password' => 'adminpass',
        ]);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'id',
                         'name',
                         'email',
                         'token',
                     ],
                 ]);

        $this->assertEquals('developer@gmail.com', $response['data']['email']);
    }

    public function test_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'developer@gmail.com',
            'password' => bcrypt('adminpass'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'developer@gmail.com',
            'password' => 'adminpass1',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'Please recheck your credentials.',
                 ]);
    }
}

