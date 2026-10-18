<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\AccessControlSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AdminAccountTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(AccessControlSeeder::class);
    }

    public function test_admin_can_edit_own_profile_without_changing_role(): void
    {
        $admin = User::factory()->create([
            'role_id' => Role::where('slug', 'admin')->value('id'),
            'email_verified_at' => now(),
            'username' => 'admin-profile',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.account.update'), [
                'name' => 'Nama Baru',
                'username' => $admin->username,
                'email' => $admin->email,
                'phone' => '08123456789',
                'role_id' => Role::where('slug', 'super-admin')->value('id'),
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'name' => 'Nama Baru',
            'role_id' => $admin->role_id,
            'phone' => '08123456789',
        ]);
    }

    public function test_changing_account_email_requires_verification_again(): void
    {
        Notification::fake();
        $admin = User::factory()->create([
            'role_id' => Role::where('slug', 'admin')->value('id'),
            'email_verified_at' => now(),
            'username' => 'admin-email',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.account.update'), [
                'name' => $admin->name,
                'username' => $admin->username,
                'email' => 'new-email@example.test',
                'phone' => '',
            ])
            ->assertRedirect(route('verification.notice'));

        $this->assertDatabaseHas('users', ['id' => $admin->id, 'email' => 'new-email@example.test', 'email_verified_at' => null]);
    }

    public function test_admin_must_supply_current_password_to_change_password(): void
    {
        $admin = User::factory()->create([
            'role_id' => Role::where('slug', 'admin')->value('id'),
            'email_verified_at' => now(),
            'password' => Hash::make('CurrentPassword123!'),
            'username' => 'admin-password',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.account.password'), [
                'current_password' => 'wrong-password',
                'password' => 'NewPassword123!',
                'password_confirmation' => 'NewPassword123!',
            ])
            ->assertSessionHasErrors('current_password');

        $this->actingAs($admin)
            ->put(route('admin.account.password'), [
                'current_password' => 'CurrentPassword123!',
                'password' => 'NewPassword123!',
                'password_confirmation' => 'NewPassword123!',
            ])
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('NewPassword123!', $admin->fresh()->password));
    }
}
