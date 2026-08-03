<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard | CV MILI WORAYEL MAJU</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <style>
        :root { --mili:#0d3b35; --mili2:#176b5e; --surface:#f5f7f7; }
        body { background:var(--surface); color:#1e302d; font-family:Inter,system-ui,sans-serif; }
        .sidebar { position:static; inset:auto; flex:0 0 276px; width:276px; height:auto; align-self:flex-start; overflow:visible; background:linear-gradient(180deg,#092e29,#0e493f); min-height:100vh; }
        .brand-logo { width:48px; height:48px; object-fit:contain; background:#fff; border-radius:12px; padding:4px; }
        .sidebar .nav-link { color:#d5e6e1; border-radius:.45rem; padding:.18rem .60rem; font-size:.72rem; line-height:1.3; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color:#fff; background:#ffffff24; }
        .dashboard-nav { display:flex; flex-direction:column; gap:.10rem; width:100%; }
        .dashboard-nav .nav-link { display:block; width:100%; }
        .nav-label { color:#83aaa1; font-size:.60rem; font-weight:700; letter-spacing:.12em; margin:.55rem .60rem .22rem; }
        .main { flex:1 1 0; min-width:0; }
        .metric,.panel { border:0; border-radius:1rem; box-shadow:0 8px 25px #152f2b0f; }
        .metric-icon { width:48px; height:48px; display:grid; place-items:center; border-radius:.85rem; background:#dff4ee; color:#08765e; font-size:1.25rem; }
        .metric-value { font-size:1.8rem; letter-spacing:-.06em; }
        .quick { color:#1e302d; text-decoration:none; }
        .quick:hover { background:#e2f2ec; }
        .chart { height:190px; display:flex; align-items:end; gap:.65rem; border-bottom:1px solid #e8eeec; }
        .chart span { flex:1; background:linear-gradient(#60b9a6,#176b5e); border-radius:.45rem .45rem 0 0; }
        @media (max-width:991px) { .sidebar { width:100%; min-height:unset; } .main { padding-top:1.5rem!important; } }
    </style>
</head>
<body>
@php
    $main = [
        'dashboard' => ['grid-1x2-fill', 'Dashboard'], 'profil' => ['building', 'Profil Perusahaan'],
        'produk' => ['box-seam', 'Produk'], 'layanan' => ['briefcase', 'Layanan'],
        'portofolio' => ['images', 'Proyek & Portofolio'], 'galeri' => ['image', 'Galeri'],
        'berita' => ['newspaper', 'Berita'], 'karir' => ['people', 'Karir'],
        'testimoni' => ['chat-quote', 'Testimoni'], 'partner' => ['handshake', 'Mitra Kerja'],
        'pesan' => ['envelope', 'Pesan Masuk'],
    ];
    $system = [
        'analytics' => ['bar-chart', 'Analytics'], 'chatbot' => ['robot', 'Chatbot'],
        'pegawai' => ['people', 'Data Pegawai'], 'pengguna' => ['person-gear', 'Pengguna'],
        'peran' => ['shield-lock', 'Role & Permission'], 'seo' => ['search', 'SEO Website'],
        'log' => ['clock-history', 'Activity Log'], 'pengaturan' => ['gear', 'Pengaturan'],
    ];
@endphp
<div class="d-lg-flex">
    <aside class="sidebar p-3 flex-shrink-0">
        <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 text-white text-decoration-none px-2 mb-3">
            <img class="brand-logo" src="{{ asset('images/Logo-Mili.png') }}" alt="Logo">
            <span class="fw-bold lh-sm">CV MILI WORAYEL MAJU<small class="d-block text-white-50">DASHBOARD</small></span>
        </a>

        <div class="nav-label">MENU UTAMA</div>
        <nav class="dashboard-nav" aria-label="Menu utama dashboard">
            @foreach($main as $key => $item)
                <a class="nav-link {{ $key === 'dashboard' ? 'active' : '' }}" data-search-item href="{{ $key === 'dashboard' ? route('admin.dashboard') : route('admin.module', $key) }}">
                    <i class="bi bi-{{ $item[0] }} me-2"></i>{{ $item[1] }}
                </a>
            @endforeach
        </nav>

        <div class="nav-label">SISTEM</div>
        <nav class="dashboard-nav" aria-label="Menu sistem dashboard">
            @foreach($system as $key => $item)
                <a class="nav-link" data-search-item href="{{ route('admin.module', $key) }}">
                    <i class="bi bi-{{ $item[0] }} me-2"></i>{{ $item[1] }}
                </a>
            @endforeach
        </nav>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button class="btn btn-outline-light btn-sm w-100"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
        </form>
    </aside>

    <main class="main flex-grow-1 p-3 p-md-4 p-xl-5">
        <div class="input-group mb-4" style="max-width:620px">
            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
            <input id="dashboardSearch" class="form-control border-start-0" placeholder="Cari menu dashboard secara otomatis...">
        </div>
        <header class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div><p class="text-uppercase fw-bold small mb-1" style="color:var(--mili2)">Ringkasan bisnis</p><h1 class="h2 fw-bold mb-1">Selamat datang, {{ auth()->user()->name }}</h1><p class="text-secondary mb-0">Kelola seluruh konten website dari satu dashboard.</p></div>
            <div class="bg-white rounded-4 px-3 py-2 shadow-sm"><strong class="small">{{ auth()->user()->name }}</strong><small class="d-block text-secondary">{{ auth()->user()->role?->name ?? 'Administrator' }}</small></div>
        </header>
        <section class="row g-3 mb-4">
            @foreach($metrics as $metric)
                <div class="col-6 col-xl-4" data-search-item><article class="card metric h-100"><div class="card-body d-flex gap-3 align-items-center"><div class="metric-icon"><i class="bi bi-bar-chart-fill"></i></div><div><small class="text-secondary">{{ $metric['label'] }}</small><strong class="metric-value d-block">{{ number_format($metric['value']) }}</strong></div></div></article></div>
            @endforeach
        </section>
        <section class="row g-3">
            <div class="col-xl-8"><article class="card panel h-100"><div class="card-body p-4"><h2 class="h5 fw-bold">Grafik Data Perusahaan</h2><p class="text-secondary small">Ringkasan data yang tersimpan pada dashboard.</p><div style="height:260px"><canvas id="companyChart"></canvas></div></div></article></div>
            <div class="col-xl-4"><article class="card panel h-100"><div class="card-body p-4"><h2 class="h5 fw-bold">Akses Cepat</h2><div class="d-grid gap-2 mt-3">@foreach(['portofolio'=>['images','Tambah proyek'],'berita'=>['pencil-square','Tulis berita'],'layanan'=>['briefcase','Tambah layanan'],'pegawai'=>['person-plus','Tambah pegawai']] as $key=>$item)<a class="quick rounded-3 p-2" href="{{ route('admin.module', $key) }}"><i class="bi bi-{{ $item[0] }} me-2" style="color:var(--mili2)"></i>{{ $item[1] }}<i class="bi bi-arrow-right float-end"></i></a>@endforeach</div></div></article></div>
        </section>
    </main>
</div>
<script>
    new Chart(document.getElementById('companyChart'), { type: 'bar', data: { labels: @json($chartLabels), datasets: [{ label: 'Jumlah Data', data: @json($chartValues), backgroundColor: '#176b5e', borderRadius: 8 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } } });
    document.getElementById('dashboardSearch').addEventListener('input', function () {
        const query = this.value.toLowerCase();
        document.querySelectorAll('[data-search-item]').forEach((item) => item.hidden = !item.innerText.toLowerCase().includes(query));
    });
</script>
</body>
</html>
