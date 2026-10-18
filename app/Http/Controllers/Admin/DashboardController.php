<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user()->loadMissing('role.permissions');
        $canManageProducts = $user->canAccessAdminModule('produk');
        $canManageServices = $user->canAccessAdminModule('layanan');
        $metrics = collect([
            ['module' => 'portofolio', 'label' => 'Total proyek', 'value' => DB::table('projects')->count(), 'icon' => 'building', 'href' => route('admin.module', 'portofolio')],
            ['module' => 'portofolio', 'label' => 'Proyek selesai', 'value' => DB::table('projects')->whereNotNull('completed_at')->count(), 'icon' => 'check2-circle', 'href' => route('admin.module', 'portofolio')],
            ['module' => 'produk', 'label' => 'Produk & layanan aktif', 'value' => ($canManageProducts ? DB::table('products')->where('is_active', true)->where('is_published', true)->count() : 0) + ($canManageServices ? DB::table('services')->where('is_active', true)->where('is_published', true)->count() : 0), 'icon' => 'boxes', 'href' => route('admin.module', $canManageProducts ? 'produk' : 'layanan')],
            ['module' => 'berita', 'label' => 'Berita terbit', 'value' => DB::table('news')->where('is_published', true)->whereNotNull('published_at')->where('published_at', '<=', now())->count(), 'icon' => 'newspaper', 'href' => route('admin.module', 'berita')],
            ['module' => 'karir', 'label' => 'Lowongan aktif', 'value' => DB::table('careers')->where('is_active', true)->where(function ($query) {
                $query->whereNull('expires_at')->orWhereDate('expires_at', '>=', today());
            })->count(), 'icon' => 'briefcase', 'href' => route('admin.module', 'karir')],
            ['module' => 'lamaran', 'label' => 'Lamaran baru', 'value' => DB::table('job_applications')->where('status', 'new')->count(), 'icon' => 'person-vcard', 'href' => route('admin.applications.index')],
            ['module' => 'pesan', 'label' => 'Pesan baru', 'value' => DB::table('contacts')->where('status', 'new')->count(), 'icon' => 'envelope', 'href' => route('admin.contacts.index')],
            ['module' => 'pegawai', 'label' => 'Pegawai aktif', 'value' => DB::table('employees')->whereIn('employment_status', ['active', 'contract'])->count(), 'icon' => 'people', 'href' => route('admin.module', 'pegawai')],
        ])->filter(fn (array $metric) => $metric['module'] === 'produk'
            ? ($canManageProducts || $canManageServices)
            : $user->canAccessAdminModule($metric['module']))->values();

        $period = (int) $request->input('period', 7);
        if (! in_array($period, [7, 30, 90], true)) {
            $period = 7;
        }
        $visitRows = collect();
        if ($user->canAccessAdminModule('analytics')) {
            $start = today()->subDays($period - 1);
            $counts = DB::table('visitor_logs')
                ->selectRaw('DATE(visited_at) as visit_date, COUNT(*) as total')
                ->whereBetween('visited_at', [$start->copy()->startOfDay(), now()])
                ->groupBy('visit_date')
                ->pluck('total', 'visit_date');

            for ($day = $period - 1; $day >= 0; $day--) {
                $date = today()->subDays($day);
                $visitRows->push([
                    'label' => $date->format('d M'),
                    'value' => (int) ($counts[$date->toDateString()] ?? 0),
                ]);
            }
        }

        $recentProjects = $user->canAccessAdminModule('portofolio')
            ? DB::table('projects')->select('id', 'title', 'is_published', 'updated_at')->latest('updated_at')->limit(5)->get()
            : collect();
        $recentContacts = $user->canAccessAdminModule('pesan')
            ? DB::table('contacts')->select('id', 'name', 'subject', 'status', 'created_at')->latest('created_at')->limit(5)->get()
            : collect();
        $recentApplications = $user->canAccessAdminModule('lamaran')
            ? DB::table('job_applications')->join('careers', 'job_applications.career_id', '=', 'careers.id')
                ->select('job_applications.id', 'job_applications.reference_code', 'job_applications.name', 'job_applications.status', 'job_applications.created_at', 'careers.title as career_title')
                ->latest('job_applications.created_at')->limit(5)->get()
            : collect();

        $recentActivity = collect();
        if ($user->canAccessAdminModule('log')) {
            $recentActivity = DB::table('activity_logs')
                ->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
                ->select('activity_logs.event', 'activity_logs.subject_type', 'activity_logs.created_at', 'users.name as user_name')
                ->latest('activity_logs.created_at')
                ->limit(8)
                ->get();
        }

        $alerts = collect();
        if ($user->role?->slug === 'super-admin' && in_array(config('mail.default'), ['log', 'array'], true)) {
            $alerts->push([
                'type' => 'warning',
                'message' => 'Pengiriman email belum menggunakan mailer produksi. Verifikasi akun dan reset password belum mengirim email nyata.',
            ]);
        }
        if ($user->canAccessAdminModule('pesan') && DB::table('contacts')->where('status', 'new')->exists()) {
            $alerts->push([
                'type' => 'info',
                'message' => 'Ada pesan masuk baru yang memerlukan tindak lanjut.',
                'href' => route('admin.contacts.index'),
            ]);
        }
        if ($user->canAccessAdminModule('lamaran') && DB::table('job_applications')->where('status', 'new')->exists()) {
            $alerts->push([
                'type' => 'info',
                'message' => 'Ada lamaran kerja baru yang perlu ditinjau.',
                'href' => route('admin.applications.index'),
            ]);
        }

        return view('admin.dashboard', [
            'metrics' => $metrics,
            'visitLabels' => $visitRows->pluck('label'),
            'visitValues' => $visitRows->pluck('value'),
            'recentActivity' => $recentActivity,
            'showActivity' => $user->canAccessAdminModule('log'),
            'showAnalytics' => $user->canAccessAdminModule('analytics'),
            'alerts' => $alerts,
            'period' => $period,
            'recentProjects' => $recentProjects,
            'recentContacts' => $recentContacts,
            'recentApplications' => $recentApplications,
        ]);
    }
}
