<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CompanyProfileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_the_admin_managed_company_profile(): void
    {
        DB::table('company_profiles')->insert([
            'name' => 'PT Contoh Papua',
            'tagline' => 'Mitra pembangunan',
            'description' => 'Profil perusahaan dari CMS.',
            'email' => 'halo@example.test',
            'phone' => '08123456789',
            'address' => 'Jayapura',
            'whatsapp' => '628123456789',
            'video_url' => 'https://example.test/profil',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->getJson('/api/company-profile')
            ->assertOk()
            ->assertJsonPath('name', 'PT Contoh Papua')
            ->assertJsonPath('tagline', 'Mitra pembangunan')
            ->assertJsonPath('email', 'halo@example.test')
            ->assertJsonPath('whatsapp', '628123456789');
    }
}
