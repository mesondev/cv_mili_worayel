<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PublicContentController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Statistik halaman pengunjung memakai sumber tabel yang sama dengan dashboard.
    $count = function (string $table): int {
        try {
            return DB::getSchemaBuilder()->hasTable($table) ? DB::table($table)->count() : 0;
        } catch (Throwable) {
            // Halaman profil tetap dapat dibuka saat layanan database sedang restart.
            return 0;
        }
    };

    $records = function (string $table, callable $query) {
        try {
            if (! DB::getSchemaBuilder()->hasTable($table)) {
                return collect();
            }

            return $query();
        } catch (Throwable) {
            // Konten publik tidak boleh gagal hanya karena database sementara tidak tersedia.
            return collect();
        }
    };

    return view('home.index', ['siteStats' => [
        'projects' => $count('projects'),
        'partners' => $count('partners'),
        'employees' => $count('employees'),
        'experience_years' => max(1, (int) date('Y') - 2009),
    ],
        'projects' => $records('projects', fn () => DB::table('projects')->latest('id')->limit(6)->get()),
        'newsItems' => $records('news', fn () => DB::table('news')->where('is_published', true)->latest('published_at')->limit(3)->get()),
        'galleries' => $records('galleries', fn () => DB::table('galleries')->where('type', 'image')->latest('id')->limit(12)->get()),
    ]);
})->name('home');

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('login.store');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/forgot-password', [AuthController::class, 'forgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'resetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1')->name('password.update');
});
Route::middleware('auth')->group(function () {
    Route::get('/verify-email', fn () => view('auth.verify-email'))->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect()->route('login')->with('status', 'Email berhasil diverifikasi. Silakan masuk.');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Tautan verifikasi baru telah dikirim.');
    })->middleware('throttle:6,1')->name('verification.send');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/proyek/{slug}', [PublicContentController::class, 'project'])->name('projects.show');
Route::get('/berita/{slug}', [PublicContentController::class, 'news'])->name('news.show');
Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'))
    ->middleware(['auth', 'verified', 'admin.access:super-admin,admin,editor,hrd,marketing'])
    ->name('dashboard');
Route::middleware(['auth', 'verified', 'admin.access:super-admin,admin,editor,hrd,marketing'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/{module}', [ManagementController::class, 'index'])->name('module');
    Route::post('/{module}', [ManagementController::class, 'store'])->name('module.store');
    Route::get('/{module}/{id}/edit', [ManagementController::class, 'edit'])->whereNumber('id')->name('module.edit');
    Route::get('/{module}/{id}', [ManagementController::class, 'show'])->whereNumber('id')->name('module.show');
    Route::put('/{module}/{id}', [ManagementController::class, 'update'])->whereNumber('id')->name('module.update');
    Route::delete('/{module}/{id}', [ManagementController::class, 'destroy'])->whereNumber('id')->name('module.destroy');
});
