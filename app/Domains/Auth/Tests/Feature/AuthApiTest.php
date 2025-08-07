<?php

namespace App\Domains\Auth\Tests\Feature;

use App\Domains\User\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'device_name' => 'Test Device'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'user' => [
                             'id',
                             'name',
                             'email',
                             'role',
                             'role_display',
                             'is_active',
                             'phone',
                             'position',
                             'last_login_at',
                         ],
                         'token',
                         'token_type'
                     ]
                 ])
                 ->assertJson([
                     'success' => true,
                     'message' => 'Login realizado com sucesso',
                     'data' => [
                         'user' => [
                             'id' => $user->id,
                             'email' => $user->email,
                         ],
                         'token_type' => 'Bearer'
                     ]
                 ]);

        $this->assertNotEmpty($response->json('data.token'));
    }

    public function test_login_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Credenciais inválidas',
                 ]);
    }

    public function test_login_with_inactive_user(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'is_active' => false,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Credenciais inválidas',
                 ]);
    }

    public function test_login_validation_errors(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'invalid-email',
            'password' => '123', // Too short
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_me_endpoint_with_authenticated_user(): void
    {
        $user = User::factory()->create([
            'is_active' => true,
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/auth/me');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         'user' => [
                             'id',
                             'name',
                             'email',
                             'role',
                             'role_display',
                             'is_active',
                             'phone',
                             'position',
                             'last_login_at',
                             'email_verified_at',
                         ]
                     ]
                 ])
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'user' => [
                             'id' => $user->id,
                             'email' => $user->email,
                         ]
                     ]
                 ]);
    }

    public function test_me_endpoint_without_authentication(): void
    {
        $response = $this->getJson('/api/auth/me');

        $response->assertStatus(401);
    }

    public function test_logout_endpoint(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->postJson('/api/auth/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Logout realizado com sucesso',
                 ]);

        // Verify token was revoked
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    }

    public function test_logout_all_endpoint(): void
    {
        $user = User::factory()->create();
        
        // Create multiple tokens
        $token1 = $user->createToken('token1')->plainTextToken;
        $token2 = $user->createToken('token2')->plainTextToken;

        $response = $this->postJson('/api/auth/logout-all', [], [
            'Authorization' => 'Bearer ' . $token1,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Logout de todos os dispositivos realizado com sucesso',
                 ]);

        // Verify all tokens were revoked
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    }

    public function test_refresh_token_endpoint(): void
    {
        $user = User::factory()->create();
        $oldToken = $user->createToken('test-token')->plainTextToken;

        $response = $this->postJson('/api/auth/refresh', [], [
            'Authorization' => 'Bearer ' . $oldToken,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'token',
                         'token_type'
                     ]
                 ])
                 ->assertJson([
                     'success' => true,
                     'message' => 'Token renovado com sucesso',
                     'data' => [
                         'token_type' => 'Bearer'
                     ]
                 ]);

        $newToken = $response->json('data.token');
        $this->assertNotEmpty($newToken);
        $this->assertNotEquals($oldToken, $newToken);
    }

    public function test_rate_limiting_on_login(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Make 6 failed login attempts (rate limit is 5)
        for ($i = 0; $i < 6; $i++) {
            $response = $this->postJson('/api/auth/login', [
                'email' => 'test@example.com',
                'password' => 'wrongpassword',
            ]);
        }

        // Rate limiting can return either 401 or 422 depending on implementation
        $this->assertContains($response->getStatusCode(), [401, 422]);
        
        $responseData = $response->json();
        $this->assertFalse($responseData['success']);
        
        // Check that the error message mentions rate limiting
        $errorMessage = $responseData['errors']['email'][0] ?? $responseData['message'] ?? '';
        $this->assertStringContainsString('muitas tentativas', strtolower($errorMessage));
    }

    public function test_login_updates_last_login_timestamp(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'last_login_at' => null,
        ]);

        $this->assertNull($user->fresh()->last_login_at);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        
        $this->assertNotNull($user->fresh()->last_login_at);
    }
}

