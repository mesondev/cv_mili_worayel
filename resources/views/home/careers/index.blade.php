<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Lowongan kerja dan karier di CV MILI WORAYEL MAJU, Jayapura, Papua.">
    <meta name="theme-color" content="#0d3b35">
    <title>Karier | CV MILI WORAYEL MAJU</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <style>
        :root{--career-ink:#103c36;--career-green:#176b5e;--career-gold:#f5b324;--career-paper:#f4f8f6}
        body{background:var(--career-paper);color:#193b35}
        .career-hero{position:relative;isolation:isolate;overflow:hidden;background:linear-gradient(115deg,#092e29f2,#0d5146db),url('/images/construction/hero-site.png') center/cover;color:#fff}
        .career-hero:after{position:absolute;z-index:-1;right:-8rem;bottom:-15rem;width:34rem;height:34rem;border:1px solid #ffffff30;border-radius:50%;content:""}
        .career-hero__inner{padding-block:clamp(4rem,10vw,8rem)}
        .career-kicker{color:#ffd77e;font-size:.75rem;font-weight:800;letter-spacing:.16em;text-transform:uppercase}
        .career-title{max-width:780px;font-size:clamp(2.5rem,7vw,5.2rem);font-weight:850;letter-spacing:-.055em;line-height:.98}
        .career-card{height:100%;border:1px solid #e4ece8;border-radius:1.15rem;background:#fff;box-shadow:0 12px 36px #0a33240b;transition:transform .2s ease,box-shadow .2s ease}
        .career-card:hover{transform:translateY(-4px);box-shadow:0 18px 42px #0a332419}
        .career-badge{display:inline-flex;align-items:center;min-height:2rem;padding:.35rem .75rem;border-radius:999px;background:#e8f4ef;color:var(--career-green);font-size:.78rem;font-weight:700}
        .career-cta{background:var(--career-green);border-color:var(--career-green);color:white;font-weight:700}
        .career-cta:hover{background:var(--career-ink);border-color:var(--career-ink);color:white}
        @media(prefers-reduced-motion:reduce){.career-card{transition:none}.career-card:hover{transform:none}}
    </style>
</head>
<body>
<header class="career-hero">
    <div class="container career-hero__inner">
        <a class="small text-white-50 text-decoration-none" href="{{ route('home') }}">← Kembali ke beranda</a>
        <p class="career-kicker mt-5 mb-3">Karier bersama MILI WORAYEL</p>
        <h1 class="career-title">Berkarya membangun masa depan Papua.</h1>
        <p class="mt-4 mb-0 text-white-50 fs-5" style="max-width:650px">Temukan kesempatan untuk tumbuh bersama tim konstruksi kami. Lamaran dan dokumen Anda dikirim melalui jalur privat.</p>
    </div>
</header>
<main class="container py-5 py-lg-6">
    @if(session('application_reference'))
        <div class="alert alert-success border-0 rounded-4 p-4 mb-4" role="status">
            <strong>Lamaran berhasil diterima.</strong>
            <span> Simpan nomor referensi Anda: <code>{{ session('application_reference') }}</code>.</span>
        </div>
    @endif
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
        <div><p class="career-kicker mb-2">Peluang kerja</p><h2 class="h2 fw-bold mb-0">Lowongan yang tersedia</h2></div>
        <p class="text-secondary mb-0">{{ $careers->total() }} posisi tersedia</p>
    </div>
    @if($careers->isEmpty())
        <section class="career-card p-4 p-md-5 text-center">
            <h3 class="h4 fw-bold">Belum ada lowongan aktif</h3>
            <p class="text-secondary mb-0">Silakan periksa kembali nanti untuk melihat peluang karier terbaru.</p>
        </section>
    @else
        <div class="row g-3 g-lg-4">
            @foreach($careers as $career)
                <div class="col-12 col-md-6 col-xl-4">
                    <article class="career-card d-flex flex-column p-4 p-lg-4">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @if($career->department)<span class="career-badge">{{ $career->department }}</span>@endif
                            @if($career->employment_type)<span class="career-badge">{{ $career->employment_type }}</span>@endif
                        </div>
                        <h3 class="h4 fw-bold">{{ $career->title }}</h3>
                        <p class="text-secondary mb-2">{{ $career->location ?: 'Papua' }}</p>
                        <p class="text-secondary flex-grow-1">{{ \Illuminate\Support\Str::limit(strip_tags((string) $career->description), 150) }}</p>
                        @if($career->expires_at)<p class="small text-secondary">Batas lamaran: {{ \Illuminate\Support\Carbon::parse($career->expires_at)->translatedFormat('j F Y') }}</p>@endif
                        <a class="btn career-cta rounded-3 mt-auto" href="{{ route('careers.show', $career->id) }}">Lihat detail dan lamar</a>
                    </article>
                </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $careers->links() }}</div>
    @endif
</main>
<footer class="container pb-4 text-secondary small">© {{ date('Y') }} CV MILI WORAYEL MAJU · <a href="{{ route('home') }}" class="text-reset">Beranda</a></footer>
</body>
</html>
