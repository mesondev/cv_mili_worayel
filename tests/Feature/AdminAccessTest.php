<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\AccessControlSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(AccessControlSeeder::class);
    }

    public function test_a_verified_admin_can_open_the_dashboard(): void
    {
        $role = Role::where('slug', 'admin')->firstOrFail();
        $user = User::factory()->create([
            'role_id' => $role->id,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Selamat datang');
    }

    public function test_a_signed_in_admin_can_still_open_the_login_form(): void
    {
        $role = Role::where('slug', 'admin')->firstOrFail();
        $user = User::factory()->create([
            'role_id' => $role->id,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/login')
            ->assertOk()
            ->assertSee('Masuk');
    }

    public function test_an_unknown_admin_module_returns_not_found(): void
    {
        $role = Role::where('slug', 'admin')->firstOrFail();
        $user = User::factory()->create([
            'role_id' => $role->id,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/admin/tidak-ada')
            ->assertNotFound();
    }

    public function test_an_admin_can_upload_an_image_to_the_gallery(): void
    {
        Storage::fake('public');
        $role = Role::where('slug', 'admin')->firstOrFail();
        $user = User::factory()->create(['role_id' => $role->id, 'email_verified_at' => now()]);

        $this->actingAs($user)
            ->post('/admin/galeri', [
                'title' => 'Dokumentasi proyek',
                'type' => 'image',
                'file_path' => UploadedFile::fake()->createWithContent(
                    'proyek.png',
                    base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jpC8AAAAASUVORK5CYII=', true)
                ),
                'caption' => 'Foto pekerjaan lapangan',
            ])
            ->assertSessionHas('success');

        $path = (string) DB::table('galleries')->value('file_path');
        $this->assertStringStartsWith('galleries/', $path);
        Storage::disk('public')->assertExists($path);
    }
}
