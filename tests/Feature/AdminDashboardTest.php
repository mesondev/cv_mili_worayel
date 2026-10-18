<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\AccessControlSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(AccessControlSeeder::class);
    }

    public function test_dashboard_shows_database_metrics_and_latest_activity_without_fabricated_experience_years(): void
    {
        $admin = User::factory()->create([
            'role_id' => Role::where('slug', 'admin')->value('id'),
            'email_verified_at' => now(),
        ]);
        DB::table('projects')->insert([
            'title' => 'Proyek terverifikasi',
            'slug' => 'proyek-terverifikasi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('activity_logs')->insert([
            'user_id' => $admin->id,
            'event' => 'Memperbarui profil',
            'subject_type' => 'company_profile',
            'subject_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Total proyek')
            ->assertSee('Aktivitas terbaru')
            ->assertSee('Memperbarui profil')
            ->assertDontSee('Tahun Pengalaman');
    }

    public function test_visitor_analytics_filters_by_date_and_paginates_real_visits(): void
    {
        $admin = User::factory()->create([
            'role_id' => Role::where('slug', 'admin')->value('id'),
            'email_verified_at' => now(),
        ]);
        DB::table('visitor_logs')->insert([
            ['ip_address' => '192.168.1.0', 'path' => 'home', 'visited_at' => today()->toDateTimeString()],
            ['ip_address' => '192.168.2.0', 'path' => 'careers.index', 'visited_at' => today()->toDateTimeString()],
            ['ip_address' => '192.168.3.0', 'path' => 'news.show', 'visited_at' => today()->subDays(10)->toDateTimeString()],
        ]);

        $this->actingAs($admin)
            ->get(route('admin.analytics.index', ['from' => today()->toDateString(), 'to' => today()->toDateString()]))
            ->assertOk()
            ->assertSee('2')
            ->assertSee('careers.index')
            ->assertDontSee('news.show');
    }

    public function test_activity_log_search_does_not_expose_ip_addresses_or_properties(): void
    {
        $admin = User::factory()->create([
            'role_id' => Role::where('slug', 'admin')->value('id'),
            'email_verified_at' => now(),
        ]);
        DB::table('activity_logs')->insert([
            'user_id' => $admin->id,
            'event' => 'Memperbarui data uji',
            'subject_type' => 'test',
            'subject_id' => 9,
            'properties' => json_encode(['secret_note' => 'jangan tampilkan']),
            'ip_address' => '10.2.3.0',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.activity.index', ['q' => 'data uji']))
            ->assertOk()
            ->assertSee('Memperbarui data uji')
            ->assertDontSee('10.2.3.0')
            ->assertDontSee('jangan tampilkan');
    }
}
