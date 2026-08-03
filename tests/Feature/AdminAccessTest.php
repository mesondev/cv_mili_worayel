<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_verified_admin_can_open_the_dashboard(): void
    {
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
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
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
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
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
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
        $role = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $user = User::factory()->create(['role_id' => $role->id, 'email_verified_at' => now()]);

        $this->actingAs($user)
            ->post('/admin/galeri', [
                'title' => 'Dokumentasi proyek',
                'type' => 'image',
                'file_path' => UploadedFile::fake()->image('proyek.png'),
                'caption' => 'Foto pekerjaan lapangan',
            ])
            ->assertSessionHas('success');

        $path = (string) DB::table('galleries')->value('file_path');
        $this->assertStringStartsWith('galleries/', $path);
        Storage::disk('public')->assertExists($path);
    }
}
