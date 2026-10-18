<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#0b2545">
    <title>Dashboard | CV MILI WORAYEL MAJU</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js" defer></script>
    <style>
        :root { --mili:#0b2545; --mili2:#2563eb; --surface:#f5f7fb; }
        body { background:var(--surface); color:#0f172a; font-family:Inter,system-ui,sans-serif; }
        .dashboard-shell { min-height:100vh; margin-left:272px; }
        .main { flex:1 1 0; min-width:0; }
        .metric,.panel { border:1px solid #e2e8f0; border-radius:1rem; box-shadow:0 8px 25px #0b25450c; }
        .metric-icon { width:48px; height:48px; flex:0 0 48px; display:grid; place-items:center; border-radius:.85rem; background:#eaf1ff; color:#2563eb; font-size:1.25rem; }
        .metric-value { font-size:clamp(1.4rem,2vw,1.8rem); letter-spacing:-.06em; }
        .metric-link { color:inherit; text-decoration:none; }
        .metric-link:hover .metric { outline:2px solid #bfcef4; transform:translateY(-2px); }
        .metric { transition:transform .18s ease,box-shadow .18s ease; }
        .metric:hover { box-shadow:0 14px 32px #152f2b18; }
        .welcome-panel { border:0; border-radius:1.2rem; color:#fff; background:radial-gradient(circle at 92% 15%,#ffffff22,transparent 28%),linear-gradient(120deg,#0b2545,#2563eb); box-shadow:0 14px 36px #0b254524; }
        .welcome-panel .btn-light { color:var(--mili); }
        .status-pill { display:inline-flex; align-items:center; gap:.35rem; padding:.3rem .6rem; border-radius:999px; font-size:.75rem; font-weight:700; background:#edf2f1; color:#52615e; white-space:nowrap; }
        .status-pill.is-new { background:#fff3d8; color:#8a5b00; }
        .status-pill.is-published { background:#eaf1ff; color:#1d4ed8; }
        .dashboard-table { min-width:560px; }
        .quick { color:#1e302d; text-decoration:none; }
        .quick:hover { background:#eaf1ff; }
        .dashboard-topbar { position:sticky; top:0; z-index:20; min-height:64px; background:linear-gradient(110deg,#0b2545,#173d70); color:#fff; }
        .dashboard-topbar .text-primary { color:#f9b233!important; }
        .dashboard-topbar .text-secondary { color:#cbd5e1!important; }
        .table > :not(caption) > * > * { padding:.85rem .75rem; }
        @media (max-width:991.98px) {
            .dashboard-shell { margin-left:0; }
            .main { padding-top:1rem!important; }
        }
        @media (prefers-reduced-motion:reduce) { *,*::before,*::after { scroll-behavior:auto!important; transition:none!important; } }
    </style>
</head>
<body>
@include('admin.partials.navigation', ['activeModule' => 'dashboard'])
<div class="dashboard-shell">
    <div class="main flex-grow-1">
        <div class="dashboard-topbar px-3 px-md-4 py-2 d-flex align-items-center justify-content-between gap-3">
            <div><span class="fw-semibold"><i class="bi bi-grid-1x2-fill me-2 text-primary" aria-hidden="true"></i>Dashboard Utama</span><span class="text-secondary small ms-2 d-none d-md-inline">Ringkasan administrasi perusahaan</span></div>
            <div class="dropdown">
                <button class="btn btn-light border d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="rounded-circle d-grid place-items-center text-white fw-bold" style="width:34px;height:34px;background:var(--mili2)">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                    <span class="d-none d-sm-block text-start"><strong class="small d-block">{{ auth()->user()->name }}</strong><small class="text-secondary">{{ auth()->user()->role?->name ?? 'Pengguna' }}</small></span>
                    <i class="bi bi-chevron-down small"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><a class="dropdown-item" href="{{ route('admin.account.edit') }}"><i class="bi bi-person me-2"></i>Profil Saya</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><form method="POST" action="{{ route('logout') }}">@csrf<button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button></form></li>
                </ul>
            </div>
        </div>

        <main class="p-3 p-md-4 p-xl-5">
            @if(session('status'))<div class="alert alert-success" role="status">{{ session('status') }}</div>@endif
            <header class="welcome-panel p-4 p-lg-5 mb-4 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
                <div><p class="text-uppercase fw-bold small mb-2 text-white-50">Ringkasan administrasi perusahaan</p><h1 class="h2 fw-bold mb-2">Selamat datang, {{ auth()->user()->name }}</h1><p class="mb-0 text-white-50">Pantau konten, komunikasi, dan aktivitas CV MILI WORAYEL MAJU.</p></div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('home') }}" class="btn btn-light"><i class="bi bi-box-arrow-up-right me-2"></i>Lihat Website</a>
                    @if(auth()->user()->canAccessAdminModule('portofolio','create'))<a href="{{ route('admin.module','portofolio') }}?create=1" class="btn btn-outline-light"><i class="bi bi-plus-lg me-1"></i>Tambah Proyek</a>@endif
                    @if(auth()->user()->canAccessAdminModule('berita','create'))<a href="{{ route('admin.module','berita') }}?create=1" class="btn btn-outline-light"><i class="bi bi-plus-lg me-1"></i>Tambah Berita</a>@endif
                </div>
            </header>

            @if($alerts->isNotEmpty())
                <section class="mb-4" aria-label="Peringatan dashboard">
                    @foreach($alerts as $alert)
                        <div class="alert alert-{{ $alert['type'] }} d-flex align-items-center gap-2" role="status">
                            <i class="bi bi-{{ $alert['type'] === 'warning' ? 'exclamation-triangle' : 'info-circle' }}" aria-hidden="true"></i>
                            <span class="flex-grow-1">{{ $alert['message'] }}</span>
                            @if(isset($alert['href']))<a href="{{ $alert['href'] }}" class="alert-link">Buka</a>@endif
                        </div>
                    @endforeach
                </section>
            @endif

            @if($metrics->isEmpty())
                <div class="alert alert-info">Belum ada ringkasan modul yang dapat ditampilkan untuk hak akses akun ini.</div>
            @else
                <section class="row g-3 mb-4" aria-label="Ringkasan metrik">
                    @foreach($metrics as $metric)
                        <div class="col-12 col-sm-6 col-xl-4" data-search-item>
                            <a class="metric-link d-block h-100" href="{{ $metric['href'] }}">
                                <article class="card metric h-100"><div class="card-body d-flex gap-3 align-items-center">
                                    <div class="metric-icon"><i class="bi bi-{{ $metric['icon'] }}"></i></div>
                                    <div><small class="text-secondary">{{ $metric['label'] }}</small><strong class="metric-value d-block">{{ number_format($metric['value']) }}</strong></div>
                                </div></article>
                            </a>
                        </div>
                    @endforeach
                </section>
            @endif

            <section class="row g-3 mb-4">
                @if($showAnalytics)
                    <div class="col-12 col-xl-8"><article class="card panel h-100"><div class="card-body p-3 p-md-4">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-start gap-3 mb-3"><div><h2 class="h5 fw-bold mb-1">Kunjungan {{ $period }} hari terakhir</h2><p class="text-secondary small mb-0">Hitungan dari kunjungan publik yang berhasil dicatat.</p></div><div class="d-flex gap-2"><form method="GET"><label for="period" class="visually-hidden">Periode grafik</label><select class="form-select form-select-sm" id="period" name="period" onchange="this.form.submit()"><option value="7" @selected($period===7)>7 hari</option><option value="30" @selected($period===30)>30 hari</option><option value="90" @selected($period===90)>90 hari</option></select></form><a href="{{ route('admin.analytics.index') }}" class="btn btn-sm btn-outline-success text-nowrap">Lihat analitik</a></div></div>
                        <div style="height:260px"><canvas id="visitChart" aria-label="Grafik kunjungan tujuh hari" role="img"></canvas></div>
                    </div></article></div>
                @endif
                <div class="col-12 {{ $showAnalytics ? 'col-xl-4' : '' }}"><article class="card panel h-100"><div class="card-body p-3 p-md-4">
                    <h2 class="h5 fw-bold">Akses cepat</h2>
                    <div class="d-grid gap-2 mt-3">
                        @foreach([
                            'portofolio'=>['images','Kelola proyek'],
                            'berita'=>['pencil-square','Kelola berita'],
                            'karir'=>['briefcase','Kelola lowongan'],
                            'lamaran'=>['person-vcard','Tinjau lamaran'],
                            'pesan'=>['envelope','Lihat pesan'],
                        ] as $key=>$item)
                            @if(auth()->user()->canAccessAdminModule($key))
                                @php($href = match($key) {'lamaran'=>route('admin.applications.index'),'pesan'=>route('admin.contacts.index'),default=>route('admin.module',$key)})
                                <a class="quick rounded-3 p-2" href="{{ $href }}"><i class="bi bi-{{ $item[0] }} me-2" style="color:var(--mili2)"></i>{{ $item[1] }}<i class="bi bi-arrow-right float-end"></i></a>
                            @endif
                        @endforeach
                    </div>
                </div></article></div>
            </section>

            <section class="row g-3 mb-4" aria-label="Data terbaru">
                @if(auth()->user()->canAccessAdminModule('portofolio'))
                    <div class="col-12 col-xl-4"><article class="card panel h-100"><div class="card-body p-3 p-md-4"><div class="d-flex justify-content-between align-items-center mb-3"><div><h2 class="h5 fw-bold mb-1">Proyek terbaru</h2><p class="text-secondary small mb-0">Pembaruan portofolio terakhir.</p></div><a class="btn btn-sm btn-outline-success" href="{{ route('admin.module','portofolio') }}">Semua</a></div>
                        @forelse($recentProjects as $project)<div class="d-flex justify-content-between align-items-center gap-2 py-2 border-top"><a class="text-decoration-none fw-semibold text-dark text-truncate" href="{{ route('admin.module.show',['portofolio',$project->id]) }}">{{ $project->title }}</a><span class="status-pill {{ $project->is_published ? 'is-published' : '' }}">{{ $project->is_published ? 'Terbit' : 'Draf' }}</span></div>@empty<div class="text-center text-secondary py-4">Belum ada proyek.</div>@endforelse
                    </div></article></div>
                @endif
                @if(auth()->user()->canAccessAdminModule('pesan'))
                    <div class="col-12 col-xl-4"><article class="card panel h-100"><div class="card-body p-3 p-md-4"><div class="d-flex justify-content-between align-items-center mb-3"><div><h2 class="h5 fw-bold mb-1">Pesan terbaru</h2><p class="text-secondary small mb-0">Pesan dari pengunjung website.</p></div><a class="btn btn-sm btn-outline-success" href="{{ route('admin.contacts.index') }}">Semua</a></div>
                        @forelse($recentContacts as $contact)<div class="d-flex justify-content-between align-items-center gap-2 py-2 border-top"><div class="min-w-0"><a class="text-decoration-none fw-semibold text-dark text-truncate d-block" href="{{ route('admin.contacts.show',$contact->id) }}">{{ $contact->name }}</a><small class="text-secondary">{{ $contact->subject ?: 'Tanpa subjek' }}</small></div><span class="status-pill {{ $contact->status === 'new' ? 'is-new' : '' }}">{{ ucfirst($contact->status) }}</span></div>@empty<div class="text-center text-secondary py-4">Belum ada pesan.</div>@endforelse
                    </div></article></div>
                @endif
                @if(auth()->user()->canAccessAdminModule('lamaran'))
                    <div class="col-12 col-xl-4"><article class="card panel h-100"><div class="card-body p-3 p-md-4"><div class="d-flex justify-content-between align-items-center mb-3"><div><h2 class="h5 fw-bold mb-1">Lamaran terbaru</h2><p class="text-secondary small mb-0">Pelamar yang masuk terakhir.</p></div><a class="btn btn-sm btn-outline-success" href="{{ route('admin.applications.index') }}">Semua</a></div>
                        @forelse($recentApplications as $application)<div class="d-flex justify-content-between align-items-center gap-2 py-2 border-top"><div class="min-w-0"><a class="text-decoration-none fw-semibold text-dark text-truncate d-block" href="{{ route('admin.applications.show',$application->id) }}">{{ $application->reference_code ?: $application->name }}</a><small class="text-secondary">{{ $application->career_title }}</small></div><span class="status-pill {{ $application->status === 'new' ? 'is-new' : '' }}">{{ ucfirst($application->status) }}</span></div>@empty<div class="text-center text-secondary py-4">Belum ada lamaran.</div>@endforelse
                    </div></article></div>
                @endif
            </section>

            @if($showActivity)
                <section class="card panel"><div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center gap-2 mb-3"><div><h2 class="h5 fw-bold mb-1">Aktivitas terbaru</h2><p class="text-secondary small mb-0">Aktivitas admin terakhir yang dicatat.</p></div><a href="{{ route('admin.activity.index') }}" class="btn btn-sm btn-outline-success">Semua aktivitas</a></div>
                    @if($recentActivity->isEmpty())
                        <div class="text-center text-secondary py-4">Belum ada aktivitas yang tercatat.</div>
                    @else
                        <div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Aktivitas</th><th>Modul</th><th>Pengguna</th><th>Waktu</th></tr></thead><tbody>
                            @foreach($recentActivity as $activity)
                                <tr><td>{{ $activity->event }}</td><td>{{ $activity->subject_type ?: '—' }}</td><td>{{ $activity->user_name ?: 'Akun dihapus' }}</td><td class="text-nowrap">{{ \Illuminate\Support\Carbon::parse($activity->created_at)->format('d/m/Y H:i') }}</td></tr>
                            @endforeach
                        </tbody></table></div>
                    @endif
                </div></section>
            @endif
            <footer class="text-secondary small mt-4">© {{ now()->year }} CV MILI WORAYEL MAJU</footer>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const canvas = document.getElementById('visitChart');
        if (canvas && window.Chart) {
            new Chart(canvas, { type: 'line', data: { labels: @json($visitLabels), datasets: [{ label: 'Kunjungan', data: @json($visitValues), borderColor: '#2563eb', backgroundColor: '#2563eb22', fill: true, tension: .3 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } } });
        }
    });
</script>
</body>
</html>
