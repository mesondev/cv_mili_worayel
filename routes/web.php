<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ContactManagementController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JobApplicationManagementController;
use App\Http\Controllers\Admin\ManagementController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\VisitorAnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PublicContentController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Statistik halaman pengunjung memakai sumber tabel yang sama dengan dashboard.
    $count = function (string $table, ?callable $filter = null): int {
        $query = DB::table($table);
        if ($filter) {
            $filter($query);
        }

        return $query->count();
    };

    $records = function (string $table, callable $query) {
        return $query();
    };

    $publicProjects = $records('projects', fn () => DB::table('projects')
        ->leftJoin('project_categories', 'projects.project_category_id', '=', 'project_categories.id')
        ->select('projects.*', 'project_categories.name as category_name', 'project_categories.slug as category_slug')
        ->where('is_published', true)
        ->where(function ($query) {
            $query->whereNull('projects.published_at')->orWhere('projects.published_at', '<=', now());
        })
        ->latest('projects.id')->limit(6)->get());
    $publicPartners = $records('partners', fn () => DB::table('partners')->where('is_active', true)->latest('id')->limit(16)->get());
    $publicEmployees = $records('employees', fn () => DB::table('employees')->where('is_public', true)->whereIn('employment_status', ['active', 'contract'])->latest('id')->limit(12)->get());
    $publicGalleries = $records('galleries', fn () => DB::table('galleries')->where('type', 'image')->where('is_published', true)->latest('id')->limit(12)->get());
    $companyProfile = $records('company_profiles', fn () => DB::table('company_profiles')->latest('id')->get())->first();

    return view('home.index', [
        'companyProfile' => $companyProfile,
        'socialLinks' => $records('social_media', fn () => DB::table('social_media')->where('is_active', true)->orderBy('platform')->get()),
        'companyHistories' => $records('company_histories', fn () => DB::table('company_histories')->where('is_published', true)->orderBy('year')->get()),
        'companyVisions' => $records('company_visions', fn () => DB::table('company_visions')->where('is_published', true)->latest('id')->get()),
        'companyMissions' => $records('company_missions', fn () => DB::table('company_missions')->where('is_published', true)->latest('id')->get()),
        'services' => $records('services', fn () => DB::table('services')->where('is_active', true)->where('is_published', true)->latest('id')->limit(12)->get()),
        'products' => $records('products', fn () => DB::table('products')->where('is_active', true)->where('is_published', true)->latest('id')->limit(12)->get()),
        'siteStats' => [
            'projects' => $count('projects', fn ($query) => $query->where('is_published', true)->where(function ($builder) {
                $builder->whereNull('published_at')->orWhere('published_at', '<=', now());
            })),
            'partners' => $count('partners', fn ($query) => $query->where('is_active', true)),
            'employees' => $count('employees', fn ($query) => $query->where('is_public', true)->whereIn('employment_status', ['active', 'contract'])),
        ],
        'projects' => $publicProjects,
        'partners' => $publicPartners,
        'publicEmployees' => $publicEmployees,
        'newsItems' => $records('news', fn () => DB::table('news')
            ->leftJoin('news_categories', 'news.news_category_id', '=', 'news_categories.id')
            ->select('news.*', 'news_categories.name as category_name')
            ->where('news.is_published', true)
            ->whereNotNull('news.published_at')
            ->where('news.published_at', '<=', now())
            ->latest('news.published_at')->limit(3)->get()),
        'galleries' => $publicGalleries,
        'testimonials' => $records('testimonials', fn () => DB::table('testimonials')->where('is_active', true)->latest('id')->limit(6)->get()),
    ]);
})->middleware('public.visit')->name('home');

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
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');
Route::view('/kebijakan-privasi', 'home.privacy')->name('privacy');
Route::get('/karir', [CareerController::class, 'index'])->middleware('public.visit')->name('careers.index');
Route::get('/karir/{career}', [CareerController::class, 'show'])->whereNumber('career')->middleware('public.visit')->name('careers.show');
Route::post('/karir/{career}/lamaran', [CareerController::class, 'store'])->whereNumber('career')->middleware('throttle:5,1')->name('careers.apply');
Route::get('/proyek/{slug}', [PublicContentController::class, 'project'])->middleware('public.visit')->name('projects.show');
Route::get('/berita/{slug}', [PublicContentController::class, 'news'])->middleware('public.visit')->name('news.show');
Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'))
    ->middleware(['auth', 'verified', 'admin.access:super-admin,admin,editor,hrd,marketing'])
    ->name('dashboard');
Route::middleware(['auth', 'verified', 'admin.access:super-admin,admin,editor,hrd,marketing'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/akun', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('/akun', [AccountController::class, 'update'])->name('account.update');
    Route::put('/akun/password', [AccountController::class, 'updatePassword'])->name('account.password');
    Route::get('/pesan', [ContactManagementController::class, 'index'])->name('contacts.index');
    Route::get('/pesan/{id}', [ContactManagementController::class, 'show'])->whereNumber('id')->name('contacts.show');
    Route::patch('/pesan/{id}', [ContactManagementController::class, 'update'])->whereNumber('id')->name('contacts.update');
    Route::get('/lamaran', [JobApplicationManagementController::class, 'index'])->name('applications.index');
    Route::get('/lamaran/{id}', [JobApplicationManagementController::class, 'show'])->whereNumber('id')->name('applications.show');
    Route::patch('/lamaran/{id}', [JobApplicationManagementController::class, 'update'])->whereNumber('id')->name('applications.update');
    Route::get('/lamaran/{id}/cv', [JobApplicationManagementController::class, 'downloadCv'])->whereNumber('id')->name('applications.cv');
    Route::get('/analytics', [VisitorAnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/log', [ActivityLogController::class, 'index'])->name('activity.index');
    Route::get('/peran/{role}/hak-akses', [RolePermissionController::class, 'edit'])->whereNumber('role')->name('roles.permissions.edit');
    Route::put('/peran/{role}/hak-akses', [RolePermissionController::class, 'update'])->whereNumber('role')->name('roles.permissions.update');
    Route::get('/{module}', [ManagementController::class, 'index'])->middleware('admin.module:view')->name('module');
    Route::post('/{module}', [ManagementController::class, 'store'])->middleware('admin.module:create')->name('module.store');
    Route::get('/{module}/{id}/edit', [ManagementController::class, 'edit'])->whereNumber('id')->middleware('admin.module:update')->name('module.edit');
    Route::get('/{module}/{id}', [ManagementController::class, 'show'])->whereNumber('id')->middleware('admin.module:view')->name('module.show');
    Route::put('/{module}/{id}', [ManagementController::class, 'update'])->whereNumber('id')->middleware('admin.module:update')->name('module.update');
    Route::delete('/{module}/{id}', [ManagementController::class, 'destroy'])->whereNumber('id')->middleware('admin.module:delete')->name('module.destroy');
});
