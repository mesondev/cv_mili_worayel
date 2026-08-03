<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_seeded_super_admin_can_log_in_with_username(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->post('/login', [
            'login' => ' jiro ',
            'password' => 'jiro123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
    }

    public function test_an_unverified_user_is_not_logged_in(): void
    {
        $role = Role::create(['name' => 'Member', 'slug' => 'member']);
        $user = User::factory()->create([
            'role_id' => $role->id,
            'email_verified_at' => null,
            'password' => Hash::make('rahasia-aman'),
        ]);

        $this->post('/login', [
            'login' => $user->email,
            'password' => 'rahasia-aman',
        ])->assertSessionHasErrors('login');

        $this->assertGuest();
    }

    public function test_login_reports_an_unknown_username(): void
    {
        $this->post('/login', ['login' => 'tidak-ada', 'password' => 'rahasia-aman'])
            ->assertSessionHasErrors(['login' => 'Email atau password salah. Percobaan login gagal.']);
    }

    public function test_login_reports_an_incorrect_password(): void
    {
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $user = User::factory()->create([
            'role_id' => $role->id,
            'username' => 'admin-pengujian',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia-aman'),
        ]);

        $this->post('/login', ['login' => $user->username, 'password' => 'password-salah'])
            ->assertSessionHasErrors(['login' => 'Email atau password salah. Percobaan login gagal.']);
    }

    public function test_captcha_is_required_after_three_failed_login_attempts(): void
    {
        foreach (range(1, 3) as $attempt) {
            $this->post('/login', ['login' => 'captcha-test', 'password' => 'password-salah']);
        }

        $this->get('/login')
            ->assertOk()
            ->assertSee('Verifikasi keamanan');
    }

    public function test_registration_shows_a_success_message_when_the_data_is_valid(): void
    {
        Notification::fake();

        $this->post('/register', [
            'name' => 'Pengguna Baru',
            'username' => 'pengguna-baru',
            'email' => 'baru@example.test',
            'phone' => '081234567890',
            'address' => 'Jayapura',
            'password' => 'PasswordAman123!',
            'password_confirmation' => 'PasswordAman123!',
            'terms' => '1',
        ])->assertRedirect(route('verification.notice'))
            ->assertSessionHas('status', 'Akun berhasil dibuat. Kami mengirim tautan verifikasi ke email Anda.');

        $this->assertDatabaseHas('users', ['email' => 'baru@example.test', 'username' => 'pengguna-baru']);
    }
}
