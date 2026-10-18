<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class HomePublicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_uses_company_data_and_only_displays_published_content(): void
    {
        $now = now();

        DB::table('company_profiles')->insert([
            'name' => 'Perusahaan Resmi',
            'tagline' => 'Tagline resmi',
            'email' => 'contact@example.test',
            'phone' => '+62 811 234 567',
            'whatsapp' => '+62 811 234 567',
            'address' => 'Alamat resmi perusahaan',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('services')->insert([
            ['name' => 'Layanan Terbit', 'slug' => 'layanan-terbit', 'is_active' => true, 'is_published' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Layanan Draft', 'slug' => 'layanan-draft', 'is_active' => true, 'is_published' => false, 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('products')->insert([
            'name' => 'Produk Terbit',
            'slug' => 'produk-terbit',
            'is_active' => true,
            'is_published' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        DB::table('projects')->insert([
            ['title' => 'Proyek Terbit', 'slug' => 'proyek-terbit', 'is_published' => true, 'published_at' => $now, 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Proyek Terjadwal', 'slug' => 'proyek-terjadwal', 'is_published' => true, 'published_at' => $now->copy()->addDay(), 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('social_media')->insert([
            'platform' => 'LinkedIn',
            'url' => 'https://example.test/company',
            'icon' => 'bi bi-linkedin',
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Perusahaan Resmi')
            ->assertSee('Layanan Terbit')
            ->assertDontSee('Layanan Draft')
            ->assertSee('Produk Terbit')
            ->assertSee('Proyek Terbit')
            ->assertDontSee('Proyek Terjadwal')
            ->assertSee('contact@example.test')
            ->assertSee('Alamat resmi perusahaan')
            ->assertSee('https://example.test/company')
            ->assertDontSee('6281234567890');
    }
}
