<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Lamaran | Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <style>body{background:#f4f8f6;color:#193b35}.apply-top{background:#103c36;color:white}.apply-panel{border:0;border-radius:1rem;box-shadow:0 12px 36px #102c240d}.candidate-meta{overflow-wrap:anywhere}</style>
</head>
<body>
@include('admin.partials.navigation', ['activeModule' => 'lamaran'])
<header class="apply-top py-3"><div class="container d-flex flex-wrap justify-content-between align-items-center gap-3"><div><a href="{{ route('admin.applications.index') }}" class="small text-white-50 text-decoration-none">← Lamaran kerja</a><h1 class="h4 mb-0 mt-1">Detail pelamar</h1></div><a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm">Dashboard</a></div></header>
<main class="container py-4 py-md-5">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <article class="card apply-panel"><div class="card-body p-4 p-md-5">
        <p class="text-uppercase small fw-bold text-success mb-2">Lamaran {{ $application->reference_code ?: '#'.$application->id }}</p>
        <h2 class="h3 fw-bold">{{ $application->name }}</h2>
        <div class="candidate-meta row g-3 text-secondary mt-1">
            <div class="col-12 col-md-6"><strong class="text-dark d-block">Posisi</strong>{{ $application->career_title }}</div>
            <div class="col-12 col-md-6"><strong class="text-dark d-block">Email</strong><a href="mailto:{{ $application->email }}">{{ $application->email }}</a></div>
            <div class="col-12 col-md-6"><strong class="text-dark d-block">Telepon</strong>{{ $application->phone ?: '—' }}</div>
            <div class="col-12 col-md-6"><strong class="text-dark d-block">Tanggal masuk</strong>{{ \Illuminate\Support\Carbon::parse($application->created_at)->format('d M Y H:i') }}</div>
            <div class="col-12"><strong class="text-dark d-block">Surat pengantar</strong><div class="mt-1">{{ $application->cover_letter ?: 'Tidak disertakan.' }}</div></div>
        </div>
        @if($application->cv_path)<a class="btn btn-outline-success mt-3" href="{{ route('admin.applications.cv',$application->id) }}">Unduh CV privat</a>@endif
        <hr class="my-4">
        <form method="POST" action="{{ route('admin.applications.update',$application->id) }}" class="row g-3">@csrf @method('PATCH')
            <div class="col-12 col-md-5"><label class="form-label" for="application-status">Status seleksi</label><select class="form-select" id="application-status" name="status">@foreach(['new'=>'Baru','reviewed'=>'Ditinjau','interview'=>'Wawancara','accepted'=>'Diterima','rejected'=>'Ditolak'] as $value=>$label)<option value="{{ $value }}" @selected($application->status===$value)>{{ $label }}</option>@endforeach</select></div>
            <div class="col-12"><label class="form-label" for="internal-notes">Catatan internal HRD</label><textarea class="form-control" id="internal-notes" name="internal_notes" rows="4" maxlength="10000">{{ old('internal_notes',$application->internal_notes) }}</textarea><div class="form-text">Catatan ini hanya tersedia untuk petugas rekrutmen yang berwenang.</div></div>
            <div class="col-12"><button class="btn btn-success">Simpan perubahan</button><a class="btn btn-outline-secondary ms-2" href="{{ route('admin.applications.index') }}">Kembali</a></div>
        </form>
    </div></article>
</main>
</body></html>
