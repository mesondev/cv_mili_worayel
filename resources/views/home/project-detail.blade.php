<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ $data['title'] }} | CV Mili Worayel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>body{background:#f4f9ff;color:#062b55}.project-link,.project-type{color:#0876d1!important}.project-button{background:#0876d1;border:0}.project-button:hover{background:#062b55}</style>
</head>
<body>
<main class="container py-4 py-md-5">
    <a href="{{ route('home') }}#proyek" class="project-link text-decoration-none fw-semibold"><i class="bi bi-arrow-left me-2"></i>Kembali ke proyek</a>
    <article class="bg-white shadow-sm rounded-4 overflow-hidden mt-4">
        @if($data['image'])
            @php($image = \Illuminate\Support\Str::startsWith($data['image'], 'projects/') ? asset('storage/'.$data['image']) : asset($data['image']))
            <img src="{{ $image }}" class="w-100" style="max-height:460px;object-fit:cover" alt="{{ $data['title'] }}">
        @endif
        <div class="p-4 p-md-5">
            @if($data['type'])<p class="project-type text-uppercase fw-bold small">{{ $data['type'] }}</p>@endif
            <h1 class="display-6 fw-bold">{{ $data['title'] }}</h1>
            @if($data['location'] || $data['year'])
                <div class="text-secondary d-flex flex-wrap gap-4 my-4">
                    @if($data['location'])<span><i class="bi bi-info-circle me-1"></i>{{ $data['location'] }}</span>@endif
                    @if($data['year'])<span><i class="bi bi-calendar3 me-1"></i>{{ $data['year'] }}</span>@endif
                </div>
            @endif
            @if($data['description'])<p class="lead lh-lg">{{ $data['description'] }}</p>@endif
            <a class="btn btn-primary project-button mt-3" href="{{ route('home') }}#kontak">Hubungi perusahaan</a>
        </div>
    </article>
</main>
</body>
</html>
