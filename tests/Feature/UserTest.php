<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\{
    User
};


class UserTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_user()
    {
        $response = $this->withHeaders([
            'x-api-key' => env('API_KEY'),
        ])->postJson('/api/v1/register', [
            'email' => 'test2@gmail.com',
            'password' => '123456',
            'name' => 'test'
        ]); 

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'User registered successfully.'
            ]);
    }

    public function test_login_user()
    {

        $user = User::factory()->create();
        $response = $this->withHeaders([
            'x-api-key' => env('API_KEY'),
        ])->postJson('/api/v1/login', [
            'email' => $user->email,        
            'password' => 'password'
        ]);

        return  $response
            ->assertStatus(200);
    }


    public function test_logout_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withHeaders([
            'x-api-key' => env('API_KEY'),
        ])->postJson('/api/v1/logout');

        return  $response
                    ->assertStatus(200);
    }


}
