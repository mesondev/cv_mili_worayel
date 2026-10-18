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

class CareerApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(AccessControlSeeder::class);
    }

    public function test_public_career_pages_only_show_active_and_unexpired_positions(): void
    {
        DB::table('careers')->insert([
            ['title' => 'Site Engineer', 'is_active' => true, 'expires_at' => today()->addWeek(), 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Lowongan Kedaluwarsa', 'is_active' => true, 'expires_at' => today()->subDay(), 'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Lowongan Nonaktif', 'is_active' => false, 'expires_at' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->get(route('careers.index'))
            ->assertOk()
            ->assertSee('Site Engineer')
            ->assertDontSee('Lowongan Kedaluwarsa')
            ->assertDontSee('Lowongan Nonaktif');
    }

    public function test_career_application_page_links_to_the_privacy_policy(): void
    {
        $careerId = DB::table('careers')->insertGetId([
            'title' => 'Site Engineer',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get(route('careers.show', $careerId))
            ->assertOk()
            ->assertSee(route('privacy'))
            ->assertSee('Kirim lamaran');
        $this->get(route('privacy'))->assertOk()->assertSee('Kebijakan privasi proses rekrutmen');
    }

    public function test_application_requires_consent_and_stores_cv_on_private_disk(): void
    {
        Storage::fake('local');
        $careerId = DB::table('careers')->insertGetId([
            'title' => 'Site Engineer',
            'is_active' => true,
            'expires_at' => today()->addWeek(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->post(route('careers.apply', $careerId), [
            'name' => 'Pelamar Uji',
            'email' => 'pelamar@example.test',
            'phone' => '081234567890',
            'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
            'cover_letter' => 'Pengalaman kerja konstruksi.',
            'privacy_consent' => '1',
        ]);

        $response->assertRedirect(route('careers.show', $careerId))
            ->assertSessionHas('application_reference');

        $application = DB::table('job_applications')->first();
        $this->assertSame('new', $application->status);
        $this->assertNotNull($application->privacy_consent_at);
        $this->assertStringStartsWith('MW-', $application->reference_code);
        $this->assertStringStartsWith('applications/', $application->cv_path);
        Storage::disk('local')->assertExists($application->cv_path);
        Storage::disk('public')->assertMissing($application->cv_path);
    }

    public function test_application_cannot_be_submitted_without_privacy_consent(): void
    {
        $careerId = DB::table('careers')->insertGetId([
            'title' => 'Site Engineer',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->from(route('careers.show', $careerId))
            ->post(route('careers.apply', $careerId), [
                'name' => 'Pelamar Uji',
                'email' => 'pelamar@example.test',
                'phone' => '081234567890',
                'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
            ])
            ->assertSessionHasErrors('privacy_consent');

        $this->assertDatabaseCount('job_applications', 0);
    }

    public function test_hrd_can_review_and_download_private_cv_but_editor_cannot(): void
    {
        Storage::fake('local');
        $careerId = DB::table('careers')->insertGetId([
            'title' => 'Site Engineer',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $cvPath = UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf')->store('applications', 'local');
        $applicationId = DB::table('job_applications')->insertGetId([
            'career_id' => $careerId,
            'name' => 'Pelamar Uji',
            'email' => 'pelamar@example.test',
            'cv_path' => $cvPath,
            'status' => 'new',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $hrd = User::factory()->create([
            'role_id' => Role::where('slug', 'hrd')->value('id'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($hrd)->get(route('admin.applications.cv', $applicationId))->assertOk();

        $editor = User::factory()->create([
            'role_id' => Role::where('slug', 'editor')->value('id'),
            'email_verified_at' => now(),
        ]);
        $this->actingAs($editor)->get(route('admin.applications.index'))->assertForbidden();
    }
}
