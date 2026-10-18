<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lamaran Kerja | Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <style>body{background:#f4f8f6;color:#193b35}.apply-top{background:#103c36;color:white}.apply-panel{border:0;border-radius:1rem;box-shadow:0 12px 36px #102c240d}.table>:not(caption)>*>*{padding:.9rem .75rem;vertical-align:middle}@media(max-width:575.98px){.table>:not(caption)>*>*{padding:.7rem .55rem}.apply-filter>*{width:100%}}</style>
</head>
<body>
@include('admin.partials.navigation', ['activeModule' => 'lamaran'])
<header class="apply-top py-3"><div class="container-fluid px-3 px-md-4 d-flex flex-wrap justify-content-between align-items-center gap-3"><div><a href="{{ route('admin.dashboard') }}" class="small text-white-50 text-decoration-none">← Dashboard</a><h1 class="h4 mb-0 mt-1">Lamaran kerja</h1></div><span class="badge rounded-pill text-bg-warning">{{ $newCount }} lamaran baru</span></div></header>
<main class="container-fluid px-3 px-md-4 py-4">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <section class="card apply-panel"><div class="card-body p-3 p-md-4">
        <form method="GET" class="apply-filter d-flex flex-column flex-sm-row gap-2 mb-3">
            <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Cari pelamar, referensi, atau posisi" aria-label="Cari lamaran">
            <select class="form-select" name="status" aria-label="Filter status"><option value="">Semua status</option>@foreach(['new'=>'Baru','reviewed'=>'Ditinjau','interview'=>'Wawancara','accepted'=>'Diterima','rejected'=>'Ditolak'] as $value=>$label)<option value="{{ $value }}" @selected(request('status')===$value)>{{ $label }}</option>@endforeach</select>
            <button class="btn btn-success">Filter</button><a class="btn btn-outline-secondary" href="{{ route('admin.applications.index') }}">Reset</a>
        </form>
        @if($applications->isEmpty())<div class="py-5 text-center text-secondary">Belum ada lamaran yang sesuai.</div>@else
            <div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Pelamar</th><th>Posisi</th><th>Referensi</th><th>Status</th><th>Diterima</th><th></th></tr></thead><tbody>
                @foreach($applications as $application)<tr><td><strong>{{ $application->name }}</strong><small class="d-block text-secondary">{{ $application->email }}</small></td><td>{{ $application->career_title }}</td><td><code>{{ $application->reference_code ?: '—' }}</code></td><td><span class="badge text-bg-{{ $application->status==='new'?'warning':($application->status==='rejected'?'danger':($application->status==='accepted'?'success':'primary')) }}">{{ ['new'=>'Baru','reviewed'=>'Ditinjau','interview'=>'Wawancara','accepted'=>'Diterima','rejected'=>'Ditolak'][$application->status] ?? $application->status }}</span></td><td>{{ \Illuminate\Support\Carbon::parse($application->created_at)->format('d M Y') }}</td><td><a class="btn btn-sm btn-outline-success" href="{{ route('admin.applications.show',$application->id) }}">Buka</a></td></tr>@endforeach
            </tbody></table></div>
            <div class="mt-3">{{ $applications->links() }}</div>
        @endif
    </div></section>
</main>
</body></html>
