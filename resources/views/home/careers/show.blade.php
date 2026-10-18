<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags((string) $career->description), 155) }}">
    <meta name="theme-color" content="#0d3b35">
    <title>{{ $career->title }} | Karier MILI WORAYEL</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <style>
        :root{--career-ink:#103c36;--career-green:#176b5e;--career-paper:#f4f8f6}
        body{background:var(--career-paper);color:#193b35}
        .career-top{background:linear-gradient(115deg,#092e29,#176b5e);color:white}
        .career-shell{max-width:1050px}
        .career-panel{border:1px solid #e4ece8;border-radius:1.2rem;background:white;box-shadow:0 16px 44px #0a33240c}
        .career-label{color:#176b5e;font-size:.75rem;font-weight:800;letter-spacing:.14em;text-transform:uppercase}
        .career-heading{font-size:clamp(2.2rem,6vw,4rem);font-weight:850;letter-spacing:-.055em;line-height:1}
        .form-control:focus{border-color:#176b5e;box-shadow:0 0 0 .22rem #176b5e20}
        .career-submit{background:#176b5e;border-color:#176b5e;color:white;font-weight:700}
        .career-submit:hover{background:#103c36;border-color:#103c36;color:white}
        .career-meta{border:1px solid #e4ece8;border-radius:.85rem;background:#f8fbf9}
    </style>
</head>
<body>
<header class="career-top py-3"><div class="container career-shell"><a href="{{ route('careers.index') }}" class="small text-white-50 text-decoration-none">← Semua lowongan</a></div></header>
<main class="container career-shell py-4 py-lg-5">
    @if(session('application_reference'))
        <div class="alert alert-success border-0 rounded-4 p-4" role="status">
            <strong>Terima kasih, lamaran Anda sudah diterima.</strong>
            <div>Simpan nomor referensi: <code>{{ session('application_reference') }}</code>.</div>
        </div>
    @endif
    <section class="career-panel p-4 p-md-5 mb-4">
        <p class="career-label mb-2">Peluang karier</p>
        <h1 class="career-heading">{{ $career->title }}</h1>
        <div class="row g-2 mt-3">
            @foreach(['Departemen' => $career->department, 'Jenis pekerjaan' => $career->employment_type, 'Lokasi' => $career->location] as $label => $value)
                @if($value)<div class="col-12 col-sm-4"><div class="career-meta p-3 h-100"><small class="text-secondary d-block">{{ $label }}</small><strong>{{ $value }}</strong></div></div>@endif
            @endforeach
        </div>
        @if($career->expires_at)<p class="small text-secondary mt-3 mb-0">Batas lamaran: {{ \Illuminate\Support\Carbon::parse($career->expires_at)->translatedFormat('j F Y') }}</p>@endif
        @if($career->description)<h2 class="h5 fw-bold mt-4">Tanggung jawab</h2><div class="text-secondary">{!! nl2br(e($career->description)) !!}</div>@endif
        @if($career->requirements)<h2 class="h5 fw-bold mt-4">Persyaratan</h2><div class="text-secondary">{!! nl2br(e($career->requirements)) !!}</div>@endif
    </section>
    <section class="career-panel p-4 p-md-5">
        <p class="career-label mb-2">Formulir kandidat</p>
        <h2 class="h3 fw-bold">Kirim lamaran</h2>
        <p class="text-secondary">CV disimpan secara privat dan hanya dapat diakses oleh petugas rekrutmen yang berwenang.</p>
        @if($errors->any())<div class="alert alert-danger"><strong>Periksa kembali formulir.</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form method="POST" action="{{ route('careers.apply', $career->id) }}" enctype="multipart/form-data" class="row g-3">
            @csrf
            <div class="col-12 col-md-6"><label for="applicant-name" class="form-label">Nama lengkap</label><input id="applicant-name" class="form-control" name="name" value="{{ old('name') }}" autocomplete="name" maxlength="150" required></div>
            <div class="col-12 col-md-6"><label for="applicant-email" class="form-label">Email</label><input id="applicant-email" class="form-control" type="email" name="email" value="{{ old('email') }}" autocomplete="email" maxlength="255" required></div>
            <div class="col-12 col-md-6"><label for="applicant-phone" class="form-label">Nomor telepon</label><input id="applicant-phone" class="form-control" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel" maxlength="25" required></div>
            <div class="col-12 col-md-6"><label for="applicant-cv" class="form-label">CV (PDF, DOC, DOCX; maksimal 5 MB)</label><input id="applicant-cv" class="form-control" type="file" name="cv" accept=".pdf,.doc,.docx,application/pdf" required>@error('cv')<div class="text-danger small mt-1">{{ $message }}</div>@enderror</div>
            <div class="col-12"><label for="applicant-letter" class="form-label">Surat pengantar <span class="text-secondary">(opsional)</span></label><textarea id="applicant-letter" class="form-control" rows="4" name="cover_letter" maxlength="5000">{{ old('cover_letter') }}</textarea></div>
            <div class="col-12"><div class="form-check"><input id="privacy-consent" class="form-check-input" type="checkbox" name="privacy_consent" value="1" required @checked(old('privacy_consent'))><label class="form-check-label" for="privacy-consent">Saya menyetujui data yang saya kirim digunakan untuk proses rekrutmen posisi ini dan telah membaca <a href="{{ route('privacy') }}" target="_blank" rel="noopener">kebijakan privasi</a>.</label></div>@error('privacy_consent')<div class="text-danger small mt-1">{{ $message }}</div>@enderror</div>
            <div class="col-12"><button class="btn career-submit px-4 py-2 rounded-3" type="submit">Kirim lamaran</button></div>
        </form>
    </section>
</main>
<footer class="container career-shell pb-4 text-secondary small">© {{ date('Y') }} CV MILI WORAYEL MAJU</footer>
</body>
</html>
