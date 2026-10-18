# CV MILI WORAYEL MAJU

Aplikasi Laravel 12 untuk website perusahaan, pengelolaan konten, dan
administrasi rekrutmen.

## Persyaratan lokal

- PHP 8.2 atau lebih baru dengan `pdo_mysql`; aktifkan ekstensi GD jika ingin
  menjalankan seluruh pengujian unggah gambar.
- Composer.
- Node.js dan npm.
- MariaDB/MySQL, misalnya XAMPP.

## Menyiapkan aplikasi di Windows/XAMPP

Jalankan perintah dari folder proyek `C:\xampp\htdocs\cv_mili_worayel`.

1. Pasang dependensi PHP:

   ```powershell
   composer install
   ```

2. Salin `.env.example` menjadi `.env` bila belum ada. Atur koneksi lokal ke
   database `db_worayel`. Gunakan akun database khusus aplikasi; jangan
   menaruh kredensial produksi ke Git.

3. Buat kunci aplikasi satu kali bila `APP_KEY` masih kosong:

   ```powershell
   php artisan key:generate
   ```

   Jangan mengganti kunci pada instalasi yang sedang dipakai tanpa rencana
   migrasi sesi dan data terenkripsi.

4. Pastikan MariaDB aktif, lalu periksa dan jalankan migrasi tambahan:

   ```powershell
   php artisan migrate:status
   php artisan migrate
   ```

   Migrasi bersifat aditif. Jangan menjalankan `migrate:fresh` atau `db:wipe`
   pada database yang berisi data.

5. Siapkan role dan izin awal (perintah ini tidak membuat akun demo):

   ```powershell
   php artisan db:seed --class=AccessControlSeeder
   ```

6. Buat administrator dengan prompt terminal privat:

   ```powershell
   php artisan app:admin-create
   ```

   Password tidak ditampilkan kembali dan harus memenuhi aturan kekuatan
   minimal. Pastikan email serta username administrator belum digunakan.

7. Pasang dan buat aset frontend:

   ```powershell
   npm ci
   npm run build
   ```

8. Jalankan aplikasi lokal:

   ```powershell
   php artisan serve
   ```

Seeder data contoh terpisah dari `DatabaseSeeder`. Jalankan
`php artisan db:seed --class=DemoContentSeeder` hanya pada database development
yang memang boleh berisi data contoh. Seeder tersebut bukan data perusahaan
yang sudah diverifikasi.

## Pengujian

Jalankan tes dengan konfigurasi tes yang ditentukan di `phpunit.xml`:

```powershell
php artisan test
```

Pada instalasi XAMPP yang memiliki `php_gd.dll` tetapi ekstensi GD belum
diaktifkan di `php.ini`, tes unggah gambar dapat dijalankan sementara dengan:

```powershell
php -d extension=C:\xampp\php\ext\php_gd.dll artisan test
```

Pengujian menggunakan SQLite in-memory dan tidak menghapus atau mereset
`db_worayel`.

## Peran dan izin admin

Izin disimpan sebagai pasangan role-permission dan diperiksa pada server untuk
setiap route/tindakan. Navigasi dashboard hanya menampilkan modul yang dapat
diakses pengguna. Pemetaan izin awal berada di `config/admin.php` dan dapat
diterapkan kembali secara idempoten melalui `AccessControlSeeder`.

Super Admin memiliki akses penuh. Editor berfokus pada berita/SEO, HRD pada
lowongan/lamaran/pegawai, Marketing pada konten pemasaran dan pesan, sedangkan
Admin mengelola modul operasional yang ditentukan. Penyesuaian izin per role
dilakukan melalui modul **Peran → Hak Akses**.

## Rekrutmen dan data pelamar

Lowongan aktif tersedia pada `/karir`. Pelamar harus memberi persetujuan
pemrosesan data dan mengunggah CV PDF, DOC, atau DOCX maksimal 5 MB. Berkas
disimpan pada disk privat Laravel, bukan direktori publik. Akses unduh hanya
diberikan kepada pengguna dengan izin lamaran; perubahan status dan unduhan
tercatat pada activity log.

Tetapkan pemberitahuan privasi dan masa retensi data pelamar sebelum
menggunakan formulir untuk rekrutmen sungguhan. Sesuaikan aturan file dengan
kebijakan organisasi bila diperlukan.

## Konfigurasi produksi

- Gunakan `.env` yang terpisah dan tidak dilacak Git.
- Atur `APP_ENV=production`, `APP_DEBUG=false`, serta `APP_URL` domain HTTPS.
- Konfigurasikan SMTP/penyedia email sebelum mengandalkan verifikasi atau reset
  password.
- Gunakan akun runtime database dengan izin minimum; pisahkan akun deployment
  yang menjalankan migrasi.
- Konfigurasikan penyimpanan privat untuk berkas pelamar, backup, pemantauan,
  dan prosedur pemulihan.
- Verifikasi PHP Apache/FPM dan PHP CLI secara terpisah, termasuk ekstensi
  yang diperlukan.
