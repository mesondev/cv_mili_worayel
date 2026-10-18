<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Analitik Pengunjung | Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js" defer></script>
    <style>body{background:#f5f7f7;color:#1e302d}.panel{border:0;border-radius:1rem;box-shadow:0 8px 25px #152f2b0f}.chart-wrap{height:300px}.table{min-width:520px}</style>
</head>
<body>
@include('admin.partials.navigation', ['activeModule' => 'analytics'])
<header class="bg-white border-bottom sticky-top"><div class="container-fluid px-3 px-md-4 py-3 d-flex justify-content-between align-items-center gap-3"><div><a href="{{ route('admin.dashboard') }}" class="small text-success text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Dashboard</a><h1 class="h3 fw-bold mb-0 mt-1">Analitik Pengunjung</h1></div><span class="badge text-bg-light">{{ auth()->user()->role?->name }}</span></div></header>
<main class="container-fluid p-3 p-md-4">
    @if($errors->any())<div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>@endif
    <section class="card panel mb-3"><div class="card-body p-3 p-md-4">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-sm-5 col-lg-4"><label class="form-label" for="from">Dari tanggal</label><input class="form-control" id="from" name="from" type="date" value="{{ $from }}" required></div>
            <div class="col-12 col-sm-5 col-lg-4"><label class="form-label" for="to">Sampai tanggal</label><input class="form-control" id="to" name="to" type="date" value="{{ $to }}" required></div>
            <div class="col-12 col-sm-2"><button class="btn btn-success w-100"><i class="bi bi-funnel me-1"></i>Filter</button></div>
            <div class="col-12"><small class="text-secondary">Rentang maksimal 366 hari. Statistik hanya berasal dari kunjungan yang berhasil dicatat.</small></div>
        </form>
    </div></section>
    <section class="row g-3 mb-3">
        <div class="col-12 col-md-6"><article class="card panel h-100"><div class="card-body"><small class="text-secondary">Total kunjungan pada periode</small><strong class="d-block fs-2">{{ number_format($visits) }}</strong></div></article></div>
        <div class="col-12 col-md-6"><article class="card panel h-100"><div class="card-body"><small class="text-secondary">Halaman unik yang tercatat</small><strong class="d-block fs-2">{{ number_format($pageCount) }}</strong></div></article></div>
    </section>
    <section class="row g-3">
        <div class="col-12 col-xl-8"><article class="card panel h-100"><div class="card-body p-3 p-md-4"><h2 class="h5 fw-bold">Tren kunjungan harian</h2><p class="text-secondary small">{{ \Illuminate\Support\Carbon::parse($from)->format('d/m/Y') }} – {{ \Illuminate\Support\Carbon::parse($to)->format('d/m/Y') }}</p><div class="chart-wrap"><canvas id="analyticsChart" aria-label="Grafik kunjungan harian" role="img"></canvas></div></div></article></div>
        <div class="col-12 col-xl-4"><article class="card panel h-100"><div class="card-body p-3 p-md-4"><h2 class="h5 fw-bold">Halaman terpopuler</h2>@if($topPages->isEmpty())<p class="text-secondary mb-0">Belum ada data pada periode ini.</p>@else<div class="table-responsive"><table class="table align-middle"><thead><tr><th>Halaman</th><th class="text-end">Kunjungan</th></tr></thead><tbody>@foreach($topPages as $page)<tr><td class="text-break">{{ $page->path ?: '—' }}</td><td class="text-end">{{ number_format($page->total) }}</td></tr>@endforeach</tbody></table></div>@endif</div></article></div>
        <div class="col-12"><article class="card panel"><div class="card-body p-3 p-md-4"><h2 class="h5 fw-bold">Kunjungan terbaru dalam periode</h2>@if($recentVisits->isEmpty())<p class="text-secondary mb-0">Belum ada kunjungan yang tercatat pada periode ini.</p>@else<div class="table-responsive"><table class="table align-middle"><thead><tr><th>Halaman</th><th>Waktu dicatat</th></tr></thead><tbody>@foreach($recentVisits as $visit)<tr><td>{{ $visit->path ?: '—' }}</td><td>{{ \Illuminate\Support\Carbon::parse($visit->visited_at)->format('d/m/Y H:i') }}</td></tr>@endforeach</tbody></table></div>{{ $recentVisits->links() }}@endif</div></article></div>
    </section>
</main>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const canvas = document.getElementById('analyticsChart');
        if (canvas && window.Chart) new Chart(canvas, { type: 'line', data: { labels: @json($dailyLabels), datasets: [{ label: 'Kunjungan', data: @json($dailyValues), borderColor: '#176b5e', backgroundColor: '#176b5e22', fill: true, tension: .3 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } } });
    });
</script>
</body>
</html>
