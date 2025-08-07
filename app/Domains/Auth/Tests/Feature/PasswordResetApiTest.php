<?php

namespace App\Domains\Auth\Tests\Feature;

use App\Domains\User\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PasswordResetApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    public function test_forgot_password_sends_reset_link(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/auth/forgot-password', [
            'email' => 'test@example.com'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Link de redefinição de senha enviado para seu email.'
                 ]);
    }

    public function test_forgot_password_with_inactive_user(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'is_active' => false,
        ]);

        $response = $this->postJson('/api/auth/forgot-password', [
            'email' => 'test@example.com'
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Esta conta está inativa. Entre em contato com o administrador.'
                 ]);
    }

    public function test_forgot_password_with_nonexistent_email(): void
    {
        $response = $this->postJson('/api/auth/forgot-password', [
            'email' => 'nonexistent@example.com'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Se o email estiver cadastrado, você receberá um link para redefinir sua senha.'
                 ]);
    }

    public function test_forgot_password_validation_errors(): void
    {
        $response = $this->postJson('/api/auth/forgot-password', [
            'email' => 'invalid-email'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_forgot_password_rate_limiting(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'is_active' => true,
        ]);

        // Make 4 requests (limit is 3)
        for ($i = 0; $i < 4; $i++) {
            $response = $this->postJson('/api/auth/forgot-password', [
                'email' => 'test@example.com'
            ]);
        }

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
        
        $errorMessage = $response->json('errors.email.0');
        $this->assertStringContainsString('Muitas tentativas', $errorMessage);
    }

    public function test_validate_token_with_valid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com'
        ]);

        $token = Password::createToken($user);

        $response = $this->postJson('/api/auth/validate-reset-token', [
            'token' => $token,
            'email' => 'test@example.com'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Token válido.',
                     'data' => [
                         'email' => 'test@example.com',
                         'name' => $user->name,
                     ]
                 ]);
    }

    public function test_validate_token_with_invalid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com'
        ]);

        $response = $this->postJson('/api/auth/validate-reset-token', [
            'token' => 'invalid-token',
            'email' => 'test@example.com'
        ]);

        $response->assertStatus(400)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Token inválido ou expirado.'
                 ]);
    }

    public function test_validate_token_with_nonexistent_user(): void
    {
        $response = $this->postJson('/api/auth/validate-reset-token', [
            'token' => 'some-token',
            'email' => 'nonexistent@example.com'
        ]);

        $response->assertStatus(400)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Token inválido ou expirado.'
                 ]);
    }

    public function test_reset_password_with_valid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('old-password')
        ]);

        $token = Password::createToken($user);

        $response = $this->postJson('/api/auth/reset-password', [
            'token' => $token,
            'email' => 'test@example.com',
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Senha redefinida com sucesso. Faça login com sua nova senha.'
                 ]);

        // Verify password was changed
        $user->refresh();
        $this->assertTrue(Hash::check('new-password123', $user->password));

        // Verify all tokens were revoked
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id
        ]);
    }

    public function test_reset_password_with_invalid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com'
        ]);

        $response = $this->postJson('/api/auth/reset-password', [
            'token' => 'invalid-token',
            'email' => 'test@example.com',
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123'
        ]);

        $response->assertStatus(400)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Token inválido ou expirado.'
                 ]);
    }

    public function test_reset_password_validation_errors(): void
    {
        $response = $this->postJson('/api/auth/reset-password', [
            'token' => 'some-token',
            'email' => 'invalid-email',
            'password' => '123', // Too short
            'password_confirmation' => 'different'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_change_password_with_valid_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('current-password')
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/auth/change-password', [
            'current_password' => 'current-password',
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Senha alterada com sucesso.'
                 ]);

        // Verify password was changed
        $user->refresh();
        $this->assertTrue(Hash::check('new-password123', $user->password));
    }

    public function test_change_password_with_invalid_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('current-password')
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/auth/change-password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123'
        ]);

        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Senha atual incorreta.',
                     'errors' => [
                         'current_password' => ['Senha atual incorreta.']
                     ]
                 ]);
    }

    public function test_change_password_without_authentication(): void
    {
        $response = $this->postJson('/api/auth/change-password', [
            'current_password' => 'current-password',
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123'
        ]);

        $response->assertStatus(401);
    }

    public function test_change_password_validation_errors(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/auth/change-password', [
            'current_password' => '',
            'password' => '123', // Too short
            'password_confirmation' => 'different'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['current_password', 'password']);
    }

    public function test_change_password_with_logout_other_devices(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('current-password')
        ]);

        // Create multiple tokens
        $token1 = $user->createToken('device1')->plainTextToken;
        $token2 = $user->createToken('device2')->plainTextToken;

        // Use the first token to make the request
        $response = $this->postJson('/api/auth/change-password', [
            'current_password' => 'current-password',
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123',
            'logout_other_devices' => true
        ], [
            'Authorization' => 'Bearer ' . $token1
        ]);

        $response->assertStatus(200);

        // Verify only one token remains (the current one)
        $this->assertEquals(1, $user->tokens()->count());
    }

    public function test_password_reset_revokes_existing_tokens(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('old-password')
        ]);

        // Create some tokens
        $user->createToken('device1');
        $user->createToken('device2');

        $this->assertEquals(2, $user->tokens()->count());

        $token = Password::createToken($user);

        $response = $this->postJson('/api/auth/reset-password', [
            'token' => $token,
            'email' => 'test@example.com',
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123'
        ]);

        $response->assertStatus(200);

        // Verify all tokens were revoked
        $this->assertEquals(0, $user->tokens()->count());
    }
}

