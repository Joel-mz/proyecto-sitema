<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->admin()->create([
            'email' => 'admin@admin.com',
            'password' => 'secret123',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'admin@admin.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_rate_limiting_locks_out_after_multiple_failed_attempts(): void
    {
        $user = User::factory()->create([
            'email' => 'user@test.com',
            'password' => 'correct-password',
        ]);

        // Attempt 5 failed logins
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post(route('login'), [
                'email' => 'user@test.com',
                'password' => 'wrong-password',
            ]);
            $response->assertSessionHasErrors('email');
        }

        // 6th attempt should be blocked with rate limiting message
        $response = $this->post(route('login'), [
            'email' => 'user@test.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString('Demasiados intentos', session('errors')->first('email'));
    }
}
