<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PublicContentController extends Controller
{
    // Menampilkan detail proyek dari database; data contoh dipakai bila admin belum menambahkan proyek.
    public function project(string $slug): View
    {
        $fallback = collect([
            'bangunan-yang-fungsional-dan-berkualitas' => ['title' => 'Bangunan yang fungsional dan berkualitas', 'type' => 'Konstruksi Gedung', 'location' => 'Jayapura', 'year' => '2026', 'image' => 'images/construction/project-commercial.png'],
            'akses-yang-lebih-baik-untuk-masyarakat' => ['title' => 'Akses yang lebih baik untuk masyarakat', 'type' => 'Konstruksi Jalan', 'location' => 'Kabupaten Jayapura', 'year' => '2025', 'image' => 'images/construction/project (1).png'],
            'kuat-untuk-mobilitas-masa-depan' => ['title' => 'Kuat untuk mobilitas masa depan', 'type' => 'Konstruksi Jembatan', 'location' => 'Papua', 'year' => '2025', 'image' => 'images/construction/project (2).png'],
            'hunian-nyaman-dan-berkualitas' => ['title' => 'Hunian nyaman dan berkualitas', 'type' => 'Perumahan', 'location' => 'Kota Jayapura', 'year' => '2024', 'image' => 'images/construction/proyect (1).png'],
            'sistem-penunjang-kawasan-yang-terintegrasi' => ['title' => 'Sistem penunjang kawasan yang terintegrasi', 'type' => 'Infrastruktur', 'location' => 'Papua', 'year' => '2024', 'image' => 'images/construction/proyect (3).png'],
            'ruang-kerja-yang-lebih-representatif' => ['title' => 'Ruang kerja yang lebih representatif', 'type' => 'Renovasi Gedung', 'location' => 'Jayapura', 'year' => '2023', 'image' => 'images/construction/project-engineering.png'],
        ]);

        $project = DB::getSchemaBuilder()->hasTable('projects') ? DB::table('projects')->where('slug', $slug)->first() : null;
        abort_unless($project || $fallback->has($slug), 404);
        $data = $project ? ['title' => $project->title, 'type' => 'Proyek', 'location' => $project->client_name ?: 'Papua', 'year' => $project->completed_at ? date('Y', strtotime($project->completed_at)) : 'Berjalan', 'image' => $project->cover_image ?: 'images/construction/hero-site.png', 'description' => $project->description] : $fallback->get($slug);
        $data['description'] ??= 'Pelaksanaan proyek dilakukan dengan perencanaan yang matang, koordinasi terbuka, serta pengawasan mutu dan keselamatan kerja pada setiap tahap.';

        return view('home.project-detail', compact('data'));
    }

    // Menampilkan artikel database atau artikel contoh yang ada di halaman Berita Terbaru.
    public function news(string $slug): View
    {
        $article = DB::getSchemaBuilder()->hasTable('news') ? DB::table('news')->where('slug', $slug)->first() : null;
        if (! $article) {
            $fallback = collect([
                'komitmen-kualitas-dalam-setiap-tahapan-pembangunan' => ['title' => 'Komitmen kualitas dalam setiap tahapan pembangunan', 'excerpt' => 'Informasi pelaksanaan proyek dan komitmen mutu perusahaan.', 'content' => 'Mutu dijaga sejak perencanaan, pelaksanaan, hingga serah terima pekerjaan melalui koordinasi terbuka dan pengawasan lapangan.', 'cover_image' => 'images/construction/project-commercial.png', 'published_at' => now()],
                'pentingnya-perencanaan-untuk-proyek-yang-berkelanjutan' => ['title' => 'Pentingnya perencanaan untuk proyek yang berkelanjutan', 'excerpt' => 'Perencanaan adalah dasar proyek yang tepat sasaran.', 'content' => 'Kami mengawali pekerjaan dengan pemetaan kebutuhan, kondisi lokasi, jadwal, dan sumber daya agar proyek memberi manfaat jangka panjang.', 'cover_image' => 'images/construction/project (1).png', 'published_at' => now()],
                'budaya-kerja-aman-sebagai-fondasi-produktivitas-proyek' => ['title' => 'Budaya kerja aman sebagai fondasi produktivitas proyek', 'excerpt' => 'Keselamatan kerja adalah bagian dari kualitas.', 'content' => 'Setiap tim diarahkan untuk bekerja tertib, menggunakan perlengkapan yang sesuai, dan menjaga koordinasi di lapangan.', 'cover_image' => 'images/construction/project-engineering.png', 'published_at' => now()],
            ]);
            abort_unless($fallback->has($slug), 404);
            $article = (object) $fallback->get($slug);
        }

        return view('home.news-detail', compact('article'));
    }
}
