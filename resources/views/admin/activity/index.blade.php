<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Activity Log | Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{background:#f5f7f7;color:#1e302d}.panel{border:0;border-radius:1rem;box-shadow:0 8px 25px #152f2b0f}.table{min-width:720px}</style>
</head>
<body>
@include('admin.partials.navigation', ['activeModule' => 'log'])
<header class="bg-white border-bottom sticky-top"><div class="container-fluid px-3 px-md-4 py-3 d-flex justify-content-between align-items-center gap-3"><div><a href="{{ route('admin.dashboard') }}" class="small text-success text-decoration-none">← Dashboard</a><h1 class="h3 fw-bold mb-0 mt-1">Activity Log</h1></div><span class="badge text-bg-light">{{ auth()->user()->role?->name }}</span></div></header>
<main class="container-fluid p-3 p-md-4">
    @if($errors->any())<div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>@endif
    <section class="card panel mb-3"><div class="card-body p-3 p-md-4">
        <form method="GET" class="row g-2 align-items-end" role="search">
            <div class="col-12 col-md-4"><label class="form-label" for="q">Cari aktivitas, modul, atau pengguna</label><input class="form-control" id="q" name="q" value="{{ request('q') }}" maxlength="100"></div>
            <div class="col-6 col-md-3"><label class="form-label" for="from">Dari tanggal</label><input class="form-control" id="from" name="from" type="date" value="{{ $from }}"></div>
            <div class="col-6 col-md-3"><label class="form-label" for="to">Sampai tanggal</label><input class="form-control" id="to" name="to" type="date" value="{{ $to }}"></div>
            <div class="col-12 col-md-2 d-flex gap-2"><button class="btn btn-success flex-grow-1">Filter</button><a href="{{ route('admin.activity.index') }}" class="btn btn-outline-secondary">Reset</a></div>
            <div class="col-12"><small class="text-secondary">Maksimal rentang 366 hari. Detail properti dan alamat IP tidak ditampilkan di daftar ini.</small></div>
        </form>
    </div></section>
    <section class="card panel"><div class="card-body p-3 p-md-4">
        @if($logs->isEmpty())<div class="text-center text-secondary py-5">Tidak ada aktivitas sesuai filter.</div>
        @else
            <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Waktu</th><th>Pengguna</th><th>Aktivitas</th><th>Modul</th><th>Record</th></tr></thead><tbody>
                @foreach($logs as $log)
                    <tr><td class="text-nowrap">{{ \Illuminate\Support\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</td><td>{{ $log->user_name ?: 'Akun dihapus' }}</td><td>{{ $log->event }}</td><td>{{ $log->subject_type ?: '—' }}</td><td>{{ $log->subject_id ?: '—' }}</td></tr>
                @endforeach
            </tbody></table></div>
            {{ $logs->links() }}
        @endif
    </div></section>
</main>
</body>
</html>
