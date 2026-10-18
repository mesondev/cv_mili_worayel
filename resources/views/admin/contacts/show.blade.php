<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Pesan | Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <style>body{background:#f4f8f6;color:#193b35}.inbox-top{background:#103c36;color:white}.inbox-panel{border:0;border-radius:1rem;box-shadow:0 12px 36px #102c240d}.message-body{white-space:pre-wrap;overflow-wrap:anywhere}</style>
</head>
<body>
@include('admin.partials.navigation', ['activeModule' => 'pesan'])
<header class="inbox-top py-3"><div class="container d-flex flex-wrap justify-content-between align-items-center gap-3"><div><a href="{{ route('admin.contacts.index') }}" class="small text-white-50 text-decoration-none">← Pesan masuk</a><h1 class="h4 mb-0 mt-1">Detail pesan</h1></div><a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm">Dashboard</a></div></header>
<main class="container py-4 py-md-5">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <article class="card inbox-panel"><div class="card-body p-4 p-md-5">
        <div class="d-flex flex-wrap justify-content-between gap-3"><div><h2 class="h4 fw-bold">{{ $contact->subject ?: 'Pesan dari pengunjung' }}</h2><p class="text-secondary mb-1">{{ $contact->name }} · <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></p>@if($contact->phone)<p class="text-secondary">Telepon: {{ $contact->phone }}</p>@endif</div><small class="text-secondary">{{ \Illuminate\Support\Carbon::parse($contact->created_at)->format('d M Y H:i') }}</small></div>
        <hr><div class="message-body">{{ $contact->message }}</div>
        <form method="POST" action="{{ route('admin.contacts.update',$contact->id) }}" class="row g-3 align-items-end mt-3">@csrf @method('PATCH')
            <div class="col-12 col-md-5"><label class="form-label" for="contact-status">Status tindak lanjut</label><select class="form-select" id="contact-status" name="status">@foreach(['new'=>'Baru','read'=>'Dibaca','replied'=>'Ditindaklanjuti','archived'=>'Diarsipkan'] as $value=>$label)<option value="{{ $value }}" @selected($contact->status===$value)>{{ $label }}</option>@endforeach</select></div>
            <div class="col-12 col-md-auto"><button class="btn btn-success">Simpan status</button><a class="btn btn-outline-secondary ms-md-2 mt-2 mt-md-0" href="{{ route('admin.contacts.index') }}">Kembali</a></div>
        </form>
    </div></article>
</main>
</body></html>
