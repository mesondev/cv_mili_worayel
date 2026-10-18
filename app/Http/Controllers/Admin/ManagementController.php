<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ManagementController extends Controller
{
    private const MODULES = [
        'profil' => ['Profil Perusahaan', 'company_profiles', [['name', 'Nama perusahaan', 'text', true], ['tagline', 'Tagline', 'text', false], ['description', 'Deskripsi', 'textarea', false], ['email', 'Email', 'email', false], ['phone', 'Telepon', 'text', false], ['address', 'Alamat', 'textarea', false]]],
        'sejarah' => ['Sejarah Perusahaan', 'company_histories', [['year', 'Tahun', 'number', false], ['title', 'Judul sejarah', 'text', true], ['description', 'Cerita', 'textarea', false], ['is_published', 'Tampilkan di website', 'checkbox', false]]],
        'visi' => ['Visi Perusahaan', 'company_visions', [['content', 'Visi', 'textarea', true], ['is_published', 'Tampilkan di website', 'checkbox', false]]],
        'misi' => ['Misi Perusahaan', 'company_missions', [['content', 'Misi', 'textarea', true], ['is_published', 'Tampilkan di website', 'checkbox', false]]],
        'produk' => ['Produk', 'products', [['name', 'Nama produk', 'text', true], ['slug', 'Slug URL (opsional)', 'text', false], ['product_category_id', 'Kategori produk', 'select', false, []], ['excerpt', 'Ringkasan', 'textarea', false], ['description', 'Deskripsi', 'textarea', false], ['price', 'Harga', 'number', false], ['image', 'Path gambar', 'text', false], ['is_active', 'Aktif', 'checkbox', false], ['is_published', 'Tampilkan di website', 'checkbox', false]]],
        'kategori-produk' => ['Kategori Produk', 'product_categories', [['name', 'Nama kategori', 'text', true], ['slug', 'Slug URL (opsional)', 'text', false]]],
        'layanan' => ['Layanan', 'services', [['name', 'Nama layanan', 'text', true], ['slug', 'Slug URL (opsional)', 'text', false], ['icon', 'Ikon Bootstrap', 'text', false], ['excerpt', 'Ringkasan', 'textarea', false], ['description', 'Deskripsi', 'textarea', false], ['is_active', 'Aktif', 'checkbox', false], ['is_published', 'Tampilkan di website', 'checkbox', false]]],
        'portofolio' => ['Portofolio', 'projects', [['title', 'Judul proyek', 'text', true], ['slug', 'Slug URL (opsional)', 'text', false], ['project_category_id', 'Kategori proyek', 'select', false, []], ['client_name', 'Lokasi/klien yang boleh dipublikasikan', 'text', false], ['description', 'Deskripsi', 'textarea', false], ['case_study', 'Detail proyek', 'textarea', false], ['cover_image', 'Path gambar sampul', 'text', false], ['completed_at', 'Tanggal selesai', 'date', false], ['is_published', 'Tampilkan di website', 'checkbox', false], ['published_at', 'Waktu publikasi', 'datetime-local', false]]],
        'kategori-proyek' => ['Kategori Proyek', 'project_categories', [['name', 'Nama kategori', 'text', true], ['slug', 'Slug URL (opsional)', 'text', false]]],
        'galeri' => ['Galeri', 'galleries', [['title', 'Judul', 'text', true], ['gallery_category_id', 'Kategori galeri', 'select', false, []], ['type', 'Jenis (image/video)', 'select', true, ['image' => 'Gambar', 'video' => 'Video']], ['file_path', 'Unggah gambar', 'file', false], ['video_url', 'URL video', 'url', false], ['caption', 'Keterangan', 'textarea', false], ['alt_text', 'Teks alternatif gambar', 'text', false], ['is_published', 'Tampilkan di website', 'checkbox', false]]],
        'kategori-galeri' => ['Kategori Galeri', 'gallery_categories', [['name', 'Nama kategori', 'text', true], ['slug', 'Slug URL (opsional)', 'text', false]]],
        'berita' => ['Berita & Artikel', 'news', [['title', 'Judul berita', 'text', true], ['slug', 'Slug URL (opsional)', 'text', false], ['news_category_id', 'Kategori berita', 'select', false, []], ['excerpt', 'Ringkasan', 'textarea', false], ['content', 'Isi berita', 'textarea', true], ['cover_image', 'Path gambar sampul', 'text', false], ['published_at', 'Tanggal publikasi', 'datetime-local', false], ['is_published', 'Publikasikan', 'checkbox', false]]],
        'kategori-berita' => ['Kategori Berita', 'news_categories', [['name', 'Nama kategori', 'text', true], ['slug', 'Slug URL (opsional)', 'text', false]]],
        'karir' => ['Karir & Pelamar', 'careers', [['title', 'Nama posisi', 'text', true], ['department', 'Departemen', 'text', false], ['employment_type', 'Jenis kerja', 'text', false], ['location', 'Lokasi', 'text', false], ['description', 'Deskripsi', 'textarea', false], ['requirements', 'Persyaratan', 'textarea', false], ['expires_at', 'Batas lamaran', 'date', false], ['is_active', 'Lowongan aktif', 'checkbox', false]]],
        'testimoni' => ['Testimoni', 'testimonials', [['client_name', 'Nama mitra', 'text', true], ['position', 'Jabatan', 'text', false], ['company', 'Perusahaan', 'text', false], ['content', 'Testimoni', 'textarea', true], ['photo', 'Path foto', 'text', false], ['rating', 'Rating', 'select', false, ['5' => '5 bintang', '4' => '4 bintang', '3' => '3 bintang', '2' => '2 bintang', '1' => '1 bintang']]]],
        'partner' => ['Partner & Client', 'partners', [['name', 'Nama mitra', 'text', true], ['type', 'Kategori', 'text', false], ['logo', 'Path logo', 'text', false], ['website', 'Website', 'url', false], ['is_active', 'Tampilkan di website', 'checkbox', false]]],
        'pegawai' => ['Data Pegawai', 'employees', [['employee_number', 'Nomor pegawai', 'text', true], ['name', 'Nama lengkap', 'text', true], ['photo', 'Pas foto', 'file', false], ['email', 'Email', 'email', false], ['phone', 'Telepon', 'text', false], ['position', 'Jabatan', 'text', true], ['department', 'Departemen', 'text', false], ['joined_at', 'Tanggal bergabung', 'date', false], ['employment_status', 'Status', 'select', false, ['active' => 'Aktif', 'contract' => 'Kontrak', 'inactive' => 'Tidak aktif']], ['is_public', 'Tampilkan sebagai profil publik', 'checkbox', false], ['address', 'Alamat', 'textarea', false]]],
        'seo' => ['SEO Website', 'seo_settings', [['page', 'Halaman', 'text', true], ['meta_title', 'Judul meta', 'text', false], ['meta_description', 'Deskripsi meta', 'textarea', false], ['keywords', 'Kata kunci', 'text', false]]],
        'media-sosial' => ['Media Sosial', 'social_media', [['platform', 'Platform', 'text', true], ['url', 'URL akun resmi', 'url', true], ['icon', 'Ikon Bootstrap', 'text', false], ['is_active', 'Tampilkan', 'checkbox', false]]],
        'chatbot' => ['AI Chatbot Setting', 'settings', [['key', 'Nama pengaturan', 'text', true], ['value', 'Nilai', 'textarea', false], ['group', 'Grup', 'text', false]]],
        'pengguna' => ['Manajemen Pengguna', 'users', [['name', 'Nama', 'text', true], ['username', 'Username', 'text', true], ['email', 'Email', 'email', true], ['role_id', 'Peran', 'select', true, []], ['account_status', 'Status akun', 'select', true, ['active' => 'Aktif', 'suspended' => 'Ditangguhkan']], ['password', 'Password (minimal 12 karakter)', 'password', true], ['phone', 'Telepon', 'text', false], ['address', 'Alamat', 'textarea', false]]],
        'peran' => ['Role & Permission', 'roles', [['name', 'Nama peran', 'text', true], ['slug', 'Slug', 'text', false], ['description', 'Deskripsi', 'textarea', false]]],
        'pengaturan' => ['Pengaturan Website', 'settings', [['key', 'Nama pengaturan', 'text', true], ['value', 'Nilai', 'textarea', false], ['group', 'Grup', 'text', false]]],
        'pesan' => ['Pesan Masuk', 'contacts', []], 'analytics' => ['Visitor Analytics', 'visitor_logs', []], 'log' => ['Activity Log', 'activity_logs', []],
    ];

    private const SEARCHABLE_COLUMNS = [
        'profil' => ['name', 'email', 'phone'],
        'sejarah' => ['title', 'description'],
        'visi' => ['content'],
        'misi' => ['content'],
        'produk' => ['name', 'slug', 'excerpt'],
        'kategori-produk' => ['name', 'slug'],
        'layanan' => ['name', 'slug', 'excerpt'],
        'portofolio' => ['title', 'slug', 'client_name'],
        'kategori-proyek' => ['name', 'slug'],
        'galeri' => ['title', 'caption'],
        'kategori-galeri' => ['name', 'slug'],
        'berita' => ['title', 'slug', 'excerpt'],
        'kategori-berita' => ['name', 'slug'],
        'karir' => ['title', 'department', 'location'],
        'testimoni' => ['client_name', 'company', 'content'],
        'partner' => ['name', 'type'],
        'pegawai' => ['employee_number', 'name', 'email', 'position'],
        'seo' => ['page', 'meta_title'],
        'media-sosial' => ['platform', 'url'],
        'chatbot' => ['key', 'group'],
        'pengguna' => ['name', 'email', 'username'],
        'peran' => ['name', 'slug'],
        'pengaturan' => ['key', 'group'],
    ];

    public function index(Request $request, string $module): View
    {
        abort_unless(isset(self::MODULES[$module]), 404);
        [$title, $table] = self::MODULES[$module];
        $fields = $this->fieldsFor($module);
        $hasTable = DB::getSchemaBuilder()->hasTable($table);
        if ($module === 'profil' && $hasTable && DB::table($table)->exists()) {
            $fields = [];
        }
        $query = $hasTable ? DB::table($table)->latest('id') : null;
        if ($query && $request->filled('q')) {
            $term = '%'.$request->string('q')->trim().'%';
            $columns = array_filter(self::SEARCHABLE_COLUMNS[$module] ?? [], fn (string $column) => Schema::hasColumn($table, $column));
            if ($columns) {
                $query->where(function ($builder) use ($columns, $term) {
                    foreach ($columns as $column) {
                        $builder->orWhere($column, 'like', $term);
                    }
                });
            }
        }
        $items = $query ? $query->paginate(15)->withQueryString() : collect();
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
        $rules = $this->validationRules($fields, $table);
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
        if ($module === 'profil') {
            abort_if(DB::table($table)->exists(), 409, 'Profil perusahaan utama sudah tersedia. Ubah data yang ada.');
        }
        if ($module === 'berita' && $request->boolean('is_published') && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        if ($table === 'users') {
            $data['password'] = Hash::make($data['password']);
            $data['password_changed_at'] = now();
        }
        if (DB::getSchemaBuilder()->hasColumn($table, 'is_active') && ! isset($data['is_active'])) {
            $data['is_active'] = true;
        }
        if ($module === 'berita' && $request->boolean('is_published') && empty($data['published_at'])) {
            $data['published_at'] = now();
        }
        $data['created_at'] = now();
        $data['updated_at'] = now();
        $id = DB::table($table)->insertGetId($data);
        if ($table === 'users') {
            $user = User::findOrFail($id);
            event(new Registered($user));
        }
        ActivityLogger::record('Membuat data '.$title, $table, $id);

        return back()->with('success', $title.' berhasil ditambahkan.');
    }

    public function destroy(string $module, int $id): RedirectResponse
    {
        abort_unless(isset(self::MODULES[$module]), 404);
        [, $table] = self::MODULES[$module];
        abort_unless(DB::getSchemaBuilder()->hasTable($table), 404);
        $item = DB::table($table)->find($id);
        abort_unless($item, 404);
        if ($module === 'karir') {
            abort_if(DB::table('job_applications')->where('career_id', $id)->exists(), 409, 'Lowongan dengan lamaran tidak dapat dihapus. Nonaktifkan lowongan agar riwayat pelamar tetap tersimpan.');
        }
        $categoryRelations = [
            'kategori-produk' => ['products', 'product_category_id'],
            'kategori-proyek' => ['projects', 'project_category_id'],
            'kategori-galeri' => ['galleries', 'gallery_category_id'],
            'kategori-berita' => ['news', 'news_category_id'],
        ];
        if (isset($categoryRelations[$module])) {
            [$relatedTable, $foreignKey] = $categoryRelations[$module];
            abort_if(
                DB::table($relatedTable)->where($foreignKey, $id)->exists(),
                409,
                'Kategori masih digunakan. Pindahkan data terkait sebelum menghapus kategori.'
            );
        }
        if ($table === 'users' && $item->role_id && DB::table('roles')->where('id', $item->role_id)->value('slug') === 'super-admin') {
            abort_if(DB::table('users')->where('role_id', $item->role_id)->count() <= 1, 403, 'Administrator utama terakhir tidak dapat dihapus.');
        }
        DB::table($table)->where('id', $id)->delete();
        $filePath = in_array($module, ['galeri', 'pegawai'], true) ? ($item->file_path ?? $item->photo ?? null) : null;
        $directory = $module === 'galeri' ? 'galleries/' : 'employees/';
        if ($filePath && Str::startsWith($filePath, $directory)) {
            Storage::disk('public')->delete($filePath);
        }
        ActivityLogger::record('Menghapus data '.self::MODULES[$module][0], $table, $id);

        return back()->with('success', 'Data berhasil dihapus.');
    }

    public function show(string $module, int $id): View
    {
        abort_unless(isset(self::MODULES[$module]), 404);
        [$title, $table, $fields] = self::MODULES[$module];
        $item = DB::table($table)->find($id);
        abort_unless($item, 404);
        if ($table === 'users') {
            unset($item->password, $item->remember_token);
        }

        return view('admin.record', compact('module', 'title', 'table', 'fields', 'item'));
    }

    public function edit(string $module, int $id): View
    {
        abort_unless(isset(self::MODULES[$module]), 404);
        [$title, $table] = self::MODULES[$module];
        $fields = $this->fieldsFor($module);
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
        $rules = $this->validationRules($fields, $table, $id);
        $data = $request->validate($rules);
        foreach ($fields as $field) {
            if ($field[2] === 'file') {
                if ($request->hasFile($field[0])) {
                    $oldPath = $module === 'galeri' ? $item->file_path : ($module === 'pegawai' ? $item->photo : null);
                    $data[$field[0]] = $request->file($field[0])->store($module === 'galeri' ? 'galleries' : 'employees', 'public');
                    $directory = $module === 'galeri' ? 'galleries/' : 'employees/';
                    if ($oldPath && Str::startsWith($oldPath, $directory)) {
                        Storage::disk('public')->delete($oldPath);
                    }
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
        $emailChanged = false;
        if ($table === 'users') {
            if (empty($data['password'])) {
                unset($data['password']);
            } else {
                $data['password'] = Hash::make($data['password']);
                $data['password_changed_at'] = now();
            }
            $emailChanged = isset($data['email']) && $data['email'] !== $item->email;
            if ($emailChanged) {
                $data['email_verified_at'] = null;
            }
            if ((int) $item->id === auth()->id()) {
                abort_if($data['account_status'] !== 'active' || DB::table('roles')->where('id', $data['role_id'])->value('slug') !== 'super-admin', 403, 'Anda tidak dapat menonaktifkan atau menurunkan hak akses akun sendiri.');
            }
            if ($item->role_id && DB::table('roles')->where('id', $item->role_id)->value('slug') === 'super-admin' && (int) $data['role_id'] !== (int) $item->role_id) {
                abort_if(DB::table('users')->where('role_id', $item->role_id)->count() <= 1, 403, 'Administrator utama terakhir tidak dapat diturunkan perannya.');
            }
        }
        $data['updated_at'] = now();
        DB::table($table)->where('id', $id)->update($data);
        if ($table === 'users' && $emailChanged) {
            User::findOrFail($id)->sendEmailVerificationNotification();
        }
        ActivityLogger::record('Memperbarui data '.$title, $table, $id);

        return redirect()->route('admin.module', $module)->with('success', $title.' berhasil diperbarui.');
    }

    private function validationRules(array $fields, ?string $table = null, ?int $id = null): array
    {
        return collect($fields)->mapWithKeys(function ($field) use ($table, $id) {
            [$name, , $type, $required] = $field;
            if ($type === 'checkbox') {
                return [$name => ['sometimes', 'boolean']];
            }
            if ($type === 'file') {
                return [$name => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120']];
            }

            if ($table === 'users' && $name === 'password') {
                return [$name => [$id ? 'nullable' : 'required', Password::min(12)->mixedCase()->numbers()->symbols()]];
            }

            if ($table === 'users' && $name === 'role_id') {
                return [$name => ['required', 'integer', 'exists:roles,id']];
            }
            if ($table === 'users' && $name === 'account_status') {
                return [$name => ['required', Rule::in(['active', 'suspended'])]];
            }
            if ($name === 'slug') {
                $rules = [$required ? 'required' : 'nullable', 'string', 'max:255'];
                $rules[] = Rule::unique($table, 'slug')->ignore($id);

                return [$name => $rules];
            }
            if ($name === 'year') {
                return [$name => ['nullable', 'integer', 'between:1900,2100']];
            }
            if (in_array($name, ['product_category_id', 'project_category_id', 'gallery_category_id', 'news_category_id'], true)) {
                $foreignTable = match ($name) {
                    'product_category_id' => 'product_categories',
                    'project_category_id' => 'project_categories',
                    'gallery_category_id' => 'gallery_categories',
                    default => 'news_categories',
                };

                return [$name => ['nullable', 'integer', Rule::exists($foreignTable, 'id')]];
            }

            $rules = [$required ? 'required' : 'nullable'];
            if ($type === 'email') {
                $rules[] = 'email';
            } elseif ($type === 'url') {
                $rules[] = 'url';
            } elseif ($type === 'number') {
                $rules[] = 'numeric';
                $rules[] = 'min:0';
            } elseif ($type === 'date') {
                $rules[] = 'date';
            } elseif ($type === 'datetime-local') {
                $rules[] = 'date';
            } elseif ($type === 'select') {
                $rules[] = Rule::in(array_keys($field[4] ?? []));
            }
            $rules[] = 'max:'.($type === 'textarea' ? '65535' : '255');

            if ($table === 'users' && in_array($name, ['email', 'username'], true)) {
                $rules[] = Rule::unique('users', $name)->ignore($id);
            }

            return [$name => $rules];
        })->all();
    }

    private function fieldsFor(string $module): array
    {
        $fields = self::MODULES[$module][2];
        $options = [
            'role_id' => ['roles', 'name'],
            'product_category_id' => ['product_categories', 'name'],
            'project_category_id' => ['project_categories', 'name'],
            'gallery_category_id' => ['gallery_categories', 'name'],
            'news_category_id' => ['news_categories', 'name'],
        ];

        return array_map(function (array $field) use ($options) {
            if (! isset($options[$field[0]])) {
                return $field;
            }

            [$table, $column] = $options[$field[0]];

            return [...array_slice($field, 0, 4), DB::table($table)->orderBy($column)->pluck($column, 'id')->all()];
        }, $fields);
    }
}
