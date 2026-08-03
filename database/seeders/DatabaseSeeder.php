<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = collect([
            ['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'Semua hak akses'],
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Kelola website dan konten'],
            ['name' => 'Editor', 'slug' => 'editor', 'description' => 'Kelola artikel dan berita'],
            ['name' => 'HRD', 'slug' => 'hrd', 'description' => 'Kelola karir dan pelamar'],
            ['name' => 'Marketing', 'slug' => 'marketing', 'description' => 'Kelola portofolio dan testimoni'],
        ])->mapWithKeys(fn ($role) => [$role['slug'] => Role::firstOrCreate(['slug' => $role['slug']], $role)]);
        User::updateOrCreate(['email' => 'jiro@gmail.com'], [
            'name' => 'Jiro Saputra',
            'username' => 'jiro',
            'password' => Hash::make('jiro123'),
            'email_verified_at' => now(),
            'role_id' => $roles['super-admin']->id,
        ]);

        $now = now();
        $seed = fn (string $table, array $where, array $data) => DB::table($table)->updateOrInsert($where, [...$data, 'created_at' => $now, 'updated_at' => $now]);
        $seed('company_profiles', ['name' => 'CV MILI WORAYEL MAJU'], ['tagline' => 'Mitra konstruksi Papua', 'description' => 'Perusahaan konstruksi yang mengutamakan mutu, keselamatan, dan ketepatan waktu.', 'email' => 'info@miliworayel.co.id', 'phone' => '0812-0000-0000', 'address' => 'Jayapura, Papua']);
        $seed('products', ['name' => 'Paket Konstruksi Gedung'], ['slug' => 'paket-konstruksi-gedung', 'excerpt' => 'Paket pekerjaan konstruksi gedung.', 'description' => 'Layanan konstruksi gedung dari perencanaan hingga serah terima.', 'price' => 150000000, 'image' => 'images/construction/project-commercial.png', 'is_active' => true]);
        $seed('services', ['name' => 'Jasa Konstruksi'], ['slug' => 'jasa-konstruksi', 'icon' => 'buildings', 'excerpt' => 'Pekerjaan konstruksi profesional.', 'description' => 'Pembangunan dan renovasi dengan tenaga berpengalaman.', 'is_active' => true]);
        $seed('projects', ['title' => 'Pembangunan Gedung Kantor Jayapura'], ['slug' => 'pembangunan-gedung-kantor-jayapura', 'client_name' => 'Jayapura', 'description' => 'Pembangunan gedung kantor yang aman dan fungsional.', 'case_study' => 'Perencanaan, pelaksanaan, dan pengendalian mutu proyek.', 'cover_image' => 'images/construction/project-commercial.png', 'completed_at' => now()->toDateString(), 'is_featured' => true]);
        $seed('galleries', ['title' => 'Dokumentasi Proyek Gedung'], ['type' => 'image', 'file_path' => 'images/construction/project-commercial.png', 'caption' => 'Dokumentasi pekerjaan konstruksi']);
        $seed('news', ['title' => 'Proyek Gedung Kantor Memasuki Tahap Akhir'], ['slug' => 'proyek-gedung-kantor-memasuki-tahap-akhir', 'excerpt' => 'Pekerjaan proyek berjalan sesuai standar mutu.', 'content' => 'Tim lapangan menyelesaikan pekerjaan dengan pengawasan kualitas dan keselamatan kerja.', 'cover_image' => 'images/construction/project-commercial.png', 'published_at' => $now, 'is_published' => true]);
        $seed('careers', ['title' => 'Site Engineer'], ['department' => 'Operasional', 'employment_type' => 'Full time', 'location' => 'Jayapura', 'description' => 'Mengawasi pelaksanaan pekerjaan proyek.', 'requirements' => 'Pendidikan teknik sipil dan pengalaman lapangan.', 'expires_at' => now()->addMonth()->toDateString(), 'is_active' => true]);
        $seed('testimonials', ['client_name' => 'PT Papua Maju'], ['position' => 'Klien', 'company' => 'PT Papua Maju', 'content' => 'Pekerjaan rapi, tepat waktu, dan komunikatif.', 'rating' => 5, 'is_active' => true]);
        $seed('partners', ['name' => 'PT Papua Maju'], ['type' => 'Klien', 'website' => 'https://example.com']);
        $seed('employees', ['employee_number' => 'EMP-001'], ['name' => 'Andi Pratama', 'email' => 'andi@example.com', 'phone' => '081200000001', 'position' => 'Site Engineer', 'department' => 'Operasional', 'joined_at' => now()->subYear()->toDateString(), 'employment_status' => 'active', 'address' => 'Jayapura']);
        $seed('seo_settings', ['page' => 'beranda'], ['meta_title' => 'CV MILI WORAYEL MAJU', 'meta_description' => 'Jasa konstruksi profesional di Papua.', 'keywords' => 'konstruksi,papua,jayapura']);
        $seed('settings', ['key' => 'chatbot_greeting'], ['value' => 'Halo, ada yang bisa kami bantu?', 'group' => 'chatbot']);
        $seed('contacts', ['email' => 'calon.klien@example.com'], ['name' => 'Budi Santoso', 'phone' => '081200000002', 'subject' => 'Konsultasi Proyek', 'message' => 'Saya ingin berkonsultasi mengenai pembangunan gedung.', 'status' => 'new']);
        DB::table('visitor_logs')->updateOrInsert(['path' => '/'], ['ip_address' => '127.0.0.1', 'referrer' => 'Dashboard', 'user_agent' => 'Sample visitor', 'visited_at' => $now]);
        $seed('activity_logs', ['event' => 'Data contoh dibuat'], ['user_id' => User::where('email', 'jiro@gmail.com')->value('id'), 'subject_type' => 'system', 'properties' => 'Data awal dashboard', 'ip_address' => '127.0.0.1']);
    }
}
