<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ManagementController extends Controller
{
    private const MODULES = [
        'profil' => ['Profil Perusahaan', 'company_profiles', [['name', 'Nama perusahaan', 'text', true], ['tagline', 'Tagline', 'text', false], ['description', 'Deskripsi', 'textarea', false], ['email', 'Email', 'email', false], ['phone', 'Telepon', 'text', false], ['address', 'Alamat', 'textarea', false]]],
        'produk' => ['Produk', 'products', [['name', 'Nama produk', 'text', true], ['excerpt', 'Ringkasan', 'textarea', false], ['description', 'Deskripsi', 'textarea', false], ['price', 'Harga', 'number', false], ['image', 'Path gambar', 'text', false]]],
        'layanan' => ['Layanan', 'services', [['name', 'Nama layanan', 'text', true], ['icon', 'Ikon Bootstrap', 'text', false], ['excerpt', 'Ringkasan', 'textarea', false], ['description', 'Deskripsi', 'textarea', false]]],
        'portofolio' => ['Portofolio', 'projects', [['title', 'Judul proyek', 'text', true], ['client_name', 'Klien/lokasi', 'text', false], ['description', 'Deskripsi', 'textarea', false], ['case_study', 'Detail proyek', 'textarea', false], ['cover_image', 'Path gambar sampul', 'text', false], ['completed_at', 'Tanggal selesai', 'date', false]]],
        'galeri' => ['Galeri', 'galleries', [['title', 'Judul', 'text', true], ['type', 'Jenis (image/video)', 'select', true, ['image' => 'Gambar', 'video' => 'Video']], ['file_path', 'Unggah gambar', 'file', false], ['video_url', 'URL video', 'url', false], ['caption', 'Keterangan', 'textarea', false]]],
        'berita' => ['Berita & Artikel', 'news', [['title', 'Judul berita', 'text', true], ['excerpt', 'Ringkasan', 'textarea', false], ['content', 'Isi berita', 'textarea', true], ['cover_image', 'Path gambar sampul', 'text', false], ['published_at', 'Tanggal publikasi', 'datetime-local', false], ['is_published', 'Publikasikan sekarang', 'checkbox', false]]],
        'karir' => ['Karir & Pelamar', 'careers', [['title', 'Nama posisi', 'text', true], ['department', 'Departemen', 'text', false], ['employment_type', 'Jenis kerja', 'text', false], ['location', 'Lokasi', 'text', false], ['description', 'Deskripsi', 'textarea', false], ['requirements', 'Persyaratan', 'textarea', false], ['expires_at', 'Batas lamaran', 'date', false]]],
        'testimoni' => ['Testimoni', 'testimonials', [['client_name', 'Nama mitra', 'text', true], ['position', 'Jabatan', 'text', false], ['company', 'Perusahaan', 'text', false], ['content', 'Testimoni', 'textarea', true], ['photo', 'Path foto', 'text', false], ['rating', 'Rating', 'select', false, ['5' => '5 bintang', '4' => '4 bintang', '3' => '3 bintang', '2' => '2 bintang', '1' => '1 bintang']]]],
        'partner' => ['Partner & Client', 'partners', [['name', 'Nama mitra', 'text', true], ['type', 'Kategori', 'text', false], ['logo', 'Path logo', 'text', false], ['website', 'Website', 'url', false]]],
        'pegawai' => ['Data Pegawai', 'employees', [['employee_number', 'Nomor pegawai', 'text', true], ['name', 'Nama lengkap', 'text', true], ['photo', 'Pas foto', 'file', false], ['email', 'Email', 'email', false], ['phone', 'Telepon', 'text', false], ['position', 'Jabatan', 'text', true], ['department', 'Departemen', 'text', false], ['joined_at', 'Tanggal bergabung', 'date', false], ['employment_status', 'Status', 'select', false, ['active' => 'Aktif', 'contract' => 'Kontrak', 'inactive' => 'Tidak aktif']], ['address', 'Alamat', 'textarea', false]]],
        'seo' => ['SEO Website', 'seo_settings', [['page', 'Halaman', 'text', true], ['meta_title', 'Judul meta', 'text', false], ['meta_description', 'Deskripsi meta', 'textarea', false], ['keywords', 'Kata kunci', 'text', false]]],
        'chatbot' => ['AI Chatbot Setting', 'settings', [['key', 'Nama pengaturan', 'text', true], ['value', 'Nilai', 'textarea', false], ['group', 'Grup', 'text', false]]],
        'pengguna' => ['User Management', 'users', [['name', 'Nama', 'text', true], ['email', 'Email', 'email', true], ['password', 'Password', 'password', true], ['phone', 'Telepon', 'text', false], ['address', 'Alamat', 'textarea', false]]],
        'peran' => ['Role & Permission', 'roles', [['name', 'Nama peran', 'text', true], ['slug', 'Slug', 'text', false], ['description', 'Deskripsi', 'textarea', false]]],
        'pengaturan' => ['Pengaturan Website', 'settings', [['key', 'Nama pengaturan', 'text', true], ['value', 'Nilai', 'textarea', false], ['group', 'Grup', 'text', false]]],
        'pesan' => ['Pesan Masuk', 'contacts', []], 'analytics' => ['Visitor Analytics', 'visitor_logs', []], 'log' => ['Activity Log', 'activity_logs', []],
    ];

    public function index(Request $request, string $module): View
    {
        abort_unless(isset(self::MODULES[$module]), 404);
        [$title, $table, $fields] = self::MODULES[$module];
        $hasTable = DB::getSchemaBuilder()->hasTable($table);
        $items = $hasTable ? DB::table($table)->latest('id')->limit(5)->get() : collect();
        $dateColumn = $hasTable && DB::getSchemaBuilder()->hasColumn($table, 'created_at') ? 'created_at' : 'visited_at';
        $chartRows = $hasTable && DB::getSchemaBuilder()->hasColumn($table, $dateColumn)
            ? DB::table($table)->selectRaw("DATE($dateColumn) as label, COUNT(*) as total")->groupBy('label')->orderByDesc('label')->limit(7)->get()->reverse()->values()
            : collect();
        $chartLabels = $chartRows->pluck('label');
        $chartValues = $chartRows->pluck('total');

        return view('admin.management', compact('module', 'title', 'table', 'items', 'fields', 'chartLabels', 'chartValues'));
    }

    public function store(Request $request, string $module): RedirectResponse
    {
        abort_unless(isset(self::MODULES[$module]), 404);
        [$title, $table, $fields] = self::MODULES[$module];
        abort_if(empty($fields) || ! DB::getSchemaBuilder()->hasTable($table), 403);
        $rules = collect($fields)->mapWithKeys(fn ($field) => [$field[0] => $field[2] === 'file' ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120' : ($field[3] ? 'required' : 'nullable').'|max:65535'])->all();
        $data = $request->validate($rules);
        foreach ($fields as $field) {
            if ($field[2] === 'file' && $request->hasFile($field[0])) {
                $data[$field[0]] = $request->file($field[0])->store($module === 'galeri' ? 'galleries' : 'employees', 'public');
            }
        }
        foreach ($fields as $field) {
            if ($field[2] === 'checkbox') {
                $data[$field[0]] = $request->boolean($field[0]);
            }
        }
        if (DB::getSchemaBuilder()->hasColumn($table, 'slug') && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title'] ?? $data['name'] ?? now()->timestamp).'-'.Str::lower(Str::random(5));
        }
        if ($table === 'users') {
            $data['password'] = Hash::make($data['password']);
        }
        if (DB::getSchemaBuilder()->hasColumn($table, 'is_active') && ! isset($data['is_active'])) {
            $data['is_active'] = true;
        }
        $data['created_at'] = now();
        $data['updated_at'] = now();
        DB::table($table)->insert($data);

        return back()->with('success', $title.' berhasil ditambahkan.');
    }

    public function destroy(string $module, int $id): RedirectResponse
    {
        abort_unless(isset(self::MODULES[$module]), 404);
        [, $table] = self::MODULES[$module];
        abort_unless(DB::getSchemaBuilder()->hasTable($table), 404);
        $item = DB::table($table)->find($id);
        if ($module === 'galeri' && $item?->file_path && Str::startsWith($item->file_path, 'galleries/')) {
            Storage::disk('public')->delete($item->file_path);
        }
        DB::table($table)->where('id', $id)->delete();

        return back()->with('success', 'Data berhasil dihapus.');
    }

    public function show(string $module, int $id): View
    {
        abort_unless(isset(self::MODULES[$module]), 404);
        [$title, $table, $fields] = self::MODULES[$module];
        $item = DB::table($table)->find($id);
        abort_unless($item, 404);

        return view('admin.record', compact('module', 'title', 'table', 'fields', 'item'));
    }

    public function edit(string $module, int $id): View
    {
        abort_unless(isset(self::MODULES[$module]), 404);
        [$title, $table, $fields] = self::MODULES[$module];
        $item = DB::table($table)->find($id);
        abort_unless($item, 404);

        return view('admin.record', compact('module', 'title', 'table', 'fields', 'item'));
    }

    public function update(Request $request, string $module, int $id): RedirectResponse
    {
        abort_unless(isset(self::MODULES[$module]), 404);
        [$title, $table, $fields] = self::MODULES[$module];
        abort_if(empty($fields) || ! DB::table($table)->where('id', $id)->exists(), 404);
        $item = DB::table($table)->find($id);
        $rules = collect($fields)->mapWithKeys(fn ($field) => [$field[0] => $field[2] === 'file' ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120' : (($field[3] && $field[0] !== 'password') ? 'required' : 'nullable').'|max:65535'])->all();
        $data = $request->validate($rules);
        foreach ($fields as $field) {
            if ($field[2] === 'file') {
                if ($request->hasFile($field[0])) {
                    if ($module === 'galeri' && ! empty($item->file_path) && Str::startsWith($item->file_path, 'galleries/')) {
                        Storage::disk('public')->delete($item->file_path);
                    }
                    $data[$field[0]] = $request->file($field[0])->store($module === 'galeri' ? 'galleries' : 'employees', 'public');
                } else {
                    unset($data[$field[0]]);
                }
            }
        }
        foreach ($fields as $field) {
            if ($field[2] === 'checkbox') {
                $data[$field[0]] = $request->boolean($field[0]);
            }
        }
        if ($table === 'users' && empty($data['password'])) {
            unset($data['password']);
        } elseif ($table === 'users') {
            $data['password'] = Hash::make($data['password']);
        }
        $data['updated_at'] = now();
        DB::table($table)->where('id', $id)->update($data);

        return redirect()->route('admin.module', $module)->with('success', $title.' berhasil diperbarui.');
    }
}
