@php
    $navUser = auth()->user()->loadMissing('role.permissions');
    $activeModule = $activeModule ?? null;
    $pendingMessages = $navUser->canAccessAdminModule('pesan') ? \Illuminate\Support\Facades\DB::table('contacts')->where('status', 'new')->count() : 0;
    $pendingApplications = $navUser->canAccessAdminModule('lamaran') ? \Illuminate\Support\Facades\DB::table('job_applications')->where('status', 'new')->count() : 0;
    $navSections = [
        'PROFIL PERUSAHAAN' => [
            'profil' => ['building', 'Profil Perusahaan'],
            'sejarah' => ['clock-history', 'Sejarah Perusahaan'],
            'visi' => ['eye', 'Visi Perusahaan'],
            'misi' => ['list-check', 'Misi Perusahaan'],
        ],
        'KONTEN WEBSITE' => [
            'produk' => ['box-seam', 'Produk'],
            'kategori-produk' => ['tags', 'Kategori Produk'],
            'layanan' => ['briefcase', 'Layanan'],
            'portofolio' => ['images', 'Proyek & Portofolio'],
            'kategori-proyek' => ['collection', 'Kategori Proyek'],
            'galeri' => ['image', 'Galeri'],
            'kategori-galeri' => ['folder2-open', 'Kategori Galeri'],
            'berita' => ['newspaper', 'Berita'],
            'kategori-berita' => ['bookmark', 'Kategori Berita'],
            'testimoni' => ['chat-quote', 'Testimoni'],
            'partner' => ['handshake', 'Mitra Perusahaan'],
        ],
        'SDM & KOMUNIKASI' => [
            'karir' => ['people', 'Karier & Lowongan'],
            'lamaran' => ['person-vcard', 'Lamaran Pekerjaan'],
            'pegawai' => ['person-workspace', 'Data Karyawan'],
            'pesan' => ['envelope', 'Pesan Masuk'],
        ],
        'ANALISIS & SISTEM' => [
            'analytics' => ['bar-chart', 'Analitik Website'],
            'log' => ['activity', 'Log Aktivitas'],
            'seo' => ['search', 'SEO Website'],
            'pengguna' => ['person-gear', 'Pengguna'],
            'peran' => ['shield-lock', 'Peran & Hak Akses'],
            'pengaturan' => ['gear', 'Pengaturan Sistem'],
        ],
    ];
    $navSections = collect($navSections)
        ->map(fn (array $items) => collect($items)->filter(fn ($item, $key) => $navUser->canAccessAdminModule($key))->all())
        ->filter(fn (array $items) => $items !== [])
        ->all();
@endphp
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<style>
    :root{--admin-navy:#0b2545;--admin-blue:#2563eb;--admin-surface:#f5f7fb;--admin-white:#fff;--admin-gold:#f9b233;--admin-line:#e2e8f0;--admin-text:#0f172a;--admin-muted:#64748b}
    body{background:var(--admin-surface)!important;color:var(--admin-text)!important;font-family:Inter,system-ui,-apple-system,"Segoe UI",sans-serif}
    body .card{border:1px solid var(--admin-line);border-radius:14px;box-shadow:0 8px 25px #0b25450c}
    body .table>:not(caption)>*>*{border-color:var(--admin-line)}
    body .form-control,body .form-select{min-height:42px;border-color:#cbd5e1;border-radius:8px}
    body .btn{border-radius:8px;font-weight:600}
    body .btn-success{--bs-btn-bg:var(--admin-blue);--bs-btn-border-color:var(--admin-blue);--bs-btn-hover-bg:#1d4ed8;--bs-btn-hover-border-color:#1d4ed8;--bs-btn-active-bg:#1e40af;--bs-btn-active-border-color:#1e40af}
    body .btn-outline-success{--bs-btn-color:var(--admin-blue);--bs-btn-border-color:var(--admin-blue);--bs-btn-hover-bg:var(--admin-blue);--bs-btn-hover-border-color:var(--admin-blue)}
    .inbox-top,.apply-top,.access-top,.topbar,body>header.bg-white{background:linear-gradient(110deg,var(--admin-navy),#173d70)!important;color:#fff!important;min-height:64px}
    body>header.bg-white h1{color:#fff!important}
    body>header.bg-white a.text-success{color:#bfdbfe!important}
    :focus-visible{outline:3px solid #f9b233!important;outline-offset:2px}
    .admin-global-sidebar{position:fixed;z-index:1080;inset:0 auto 0 0;width:272px;padding:1rem .85rem;background:linear-gradient(180deg,#0b2545,#123b68);color:#fff;overflow-y:auto;box-shadow:8px 0 28px #0b25451a}
    .admin-global-brand{display:flex;align-items:center;gap:.7rem;padding:.35rem .5rem 1rem;color:#fff;text-decoration:none;font-weight:800;line-height:1.15}
    .admin-global-brand img{width:43px;height:43px;object-fit:contain;background:#fff;border-radius:11px;padding:3px}
    .admin-global-brand small{display:block;margin-top:.25rem;color:#bdcde0;font-size:.65rem;letter-spacing:.1em}
    .admin-global-nav{display:flex;flex-direction:column;gap:.12rem}
    .admin-global-nav a{display:block;padding:.46rem .6rem;border-radius:.55rem;color:#dbe6f4;text-decoration:none;font-size:.82rem;line-height:1.3;transition:background .15s,color .15s}
    .admin-global-nav a:hover,.admin-global-nav a.active{background:#ffffff24;color:#fff}
    .admin-global-nav a.active{box-shadow:inset 3px 0 #eab650;font-weight:700}
    .admin-global-nav .nav-label{margin:.85rem .55rem .3rem;color:#9bb0cb;font-size:.64rem;font-weight:800;letter-spacing:.12em}
    .admin-global-search{margin:.3rem .1rem .55rem}
    .admin-global-search .form-control{border:1px solid #ffffff36;background:#ffffff12;color:#fff;font-size:.8rem}
    .admin-global-search .form-control::placeholder{color:#bdcde0}
    .admin-global-search .form-control:focus{background:#fff;color:#193b35;box-shadow:0 0 0 .2rem #ffffff2b}
    .admin-global-account{border-top:1px solid #ffffff26;margin-top:1rem;padding:.8rem .5rem 0;color:#c9ded8;font-size:.75rem}
    .admin-global-toggle,.admin-global-overlay{display:none}
    @media(max-width:991.98px){
        .admin-global-sidebar{width:min(86vw,320px);transform:translateX(-105%);transition:transform .22s ease;box-shadow:0 0 32px #0004}
        .admin-global-sidebar.is-open{transform:translateX(0)}
        .admin-global-toggle{display:grid;place-items:center;position:fixed;z-index:1090;left:.8rem;top:.75rem;width:42px;height:42px;border:1px solid #dce5e1;border-radius:.65rem;background:#fff;color:#164b40;box-shadow:0 3px 12px #102c241c;font-size:1.3rem}
        .admin-global-overlay.is-visible{display:block;position:fixed;z-index:1070;inset:0;border:0;background:#081b18a8}
        body>header{padding-left:3.7rem!important}
    }
    @media(min-width:992px){body>header,body>main{margin-left:272px!important;width:calc(100% - 272px)!important;max-width:none!important}}
    @media print{.admin-global-sidebar,.admin-global-toggle,.admin-global-overlay{display:none!important}body>header,body>main{margin:0!important;width:100%!important;max-width:100%!important}}
</style>
<aside id="adminGlobalSidebar" class="admin-global-sidebar" aria-label="Navigasi dashboard">
    <a href="{{ route('admin.dashboard') }}" class="admin-global-brand"><img src="{{ asset('images/Logo-Mili.png') }}" alt=""><span>CV MILI WORAYEL MAJU<small>PANEL ADMINISTRASI</small></span></a>
    <nav class="admin-global-nav" aria-label="Menu administrasi">
        <div class="admin-global-search"><label for="adminNavSearch" class="visually-hidden">Cari menu</label><input id="adminNavSearch" class="form-control form-control-sm" type="search" placeholder="Cari menu..." autocomplete="off"></div>
        <a data-nav-link href="{{ route('admin.dashboard') }}" @class(['active' => $activeModule === 'dashboard']) @if($activeModule === 'dashboard') aria-current="page" @endif><i class="bi bi-grid-1x2-fill me-2" aria-hidden="true"></i>Dashboard</a>
        @foreach($navSections as $section => $items)
            <div class="nav-label">{{ $section }}</div>
            @foreach($items as $key => [$icon, $label])
                @php($href = match($key) {'lamaran' => route('admin.applications.index'), 'pesan' => route('admin.contacts.index'), 'analytics' => route('admin.analytics.index'), 'log' => route('admin.activity.index'), default => route('admin.module', $key)})
                <a data-nav-link href="{{ $href }}" @class(['active' => $activeModule === $key]) @if($activeModule === $key) aria-current="page" @endif><i class="bi bi-{{ $icon }} me-2" aria-hidden="true"></i>{{ $label }}@if($key === 'pesan' && $pendingMessages > 0)<span class="badge rounded-pill text-bg-warning float-end">{{ $pendingMessages }}</span>@elseif($key === 'lamaran' && $pendingApplications > 0)<span class="badge rounded-pill text-bg-warning float-end">{{ $pendingApplications }}</span>@endif</a>
            @endforeach
        @endforeach
        <div class="nav-label">AKUN</div>
        <a data-nav-link href="{{ route('admin.account.edit') }}" @class(['active' => $activeModule === 'account']) @if($activeModule === 'account') aria-current="page" @endif><i class="bi bi-person me-2" aria-hidden="true"></i>Profil Saya</a>
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="w-100 border-0 text-start" style="padding:.46rem .6rem;border-radius:.55rem;background:transparent;color:#d5e6e1;font-size:.82rem"><i class="bi bi-box-arrow-right me-2" aria-hidden="true"></i>Keluar</button></form>
    </nav>
    <div class="admin-global-account"><strong class="d-block text-white">{{ $navUser->name }}</strong>{{ $navUser->role?->name ?? 'Pengguna' }}</div>
</aside>
<button id="adminGlobalOverlay" type="button" class="admin-global-overlay" aria-label="Tutup menu"></button>
<button id="adminGlobalToggle" type="button" class="admin-global-toggle" aria-controls="adminGlobalSidebar" aria-expanded="false" aria-label="Buka menu"><i class="bi bi-list" aria-hidden="true"></i></button>
<script>
    (()=>{const sidebar=document.getElementById('adminGlobalSidebar'),toggle=document.getElementById('adminGlobalToggle'),overlay=document.getElementById('adminGlobalOverlay'),search=document.getElementById('adminNavSearch');if(!sidebar||!toggle||!overlay)return;const close=()=>{sidebar.classList.remove('is-open');overlay.classList.remove('is-visible');toggle.setAttribute('aria-expanded','false');document.body.classList.remove('overflow-hidden')};toggle.addEventListener('click',()=>{const open=!sidebar.classList.contains('is-open');sidebar.classList.toggle('is-open',open);overlay.classList.toggle('is-visible',open);toggle.setAttribute('aria-expanded',String(open));document.body.classList.toggle('overflow-hidden',open)});overlay.addEventListener('click',close);document.addEventListener('keydown',event=>{if(event.key==='Escape')close()});search?.addEventListener('input',()=>{const query=search.value.trim().toLocaleLowerCase();sidebar.querySelectorAll('[data-nav-link]').forEach(link=>link.hidden=!link.innerText.toLocaleLowerCase().includes(query));sidebar.querySelectorAll('.nav-label').forEach(label=>{let next=label.nextElementSibling,visible=false;while(next&& !next.classList.contains('nav-label')){if(next.matches('[data-nav-link]')&&!next.hidden)visible=true;next=next.nextElementSibling}label.hidden=!visible})})})();
</script>
