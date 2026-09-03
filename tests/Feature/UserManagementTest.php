<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_users_list(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertSee('Usuarios del Sistema');
    }

    public function test_editor_cannot_access_user_management(): void
    {
        $editor = User::factory()->editor()->create();

        $response = $this->actingAs($editor)->get(route('users.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_create_new_editor_user(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Carlos Editor',
            'email' => 'carlos@example.com',
            'role' => User::ROLE_EDITOR,
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Carlos Editor',
            'email' => 'carlos@example.com',
            'role' => User::ROLE_EDITOR,
        ]);
    }

    public function test_admin_can_update_user_role(): void
    {
        $admin = User::factory()->admin()->create();
        $targetUser = User::factory()->editor()->create();

        $response = $this->actingAs($admin)->put(route('users.update', $targetUser), [
            'name' => 'Usuario Promovido',
            'email' => $targetUser->email,
            'role' => User::ROLE_ADMIN,
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'role' => User::ROLE_ADMIN,
        ]);
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->delete(route('users.destroy', $admin));

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }
}
