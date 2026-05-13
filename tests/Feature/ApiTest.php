<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_login_returns_bearer_token(): void
    {
        $user = User::factory()->admin()->create([
            'password' => 'password',
        ]);

        $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
            'device_name' => 'phpunit',
        ])
            ->assertOk()
            ->assertJsonStructure(['token_type', 'access_token', 'user']);
    }
}
