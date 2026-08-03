<!DOCTYPE html><html lang="id">
    <head><meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#0d3b35">
    <title>{{ $title }} | CV MILI WORAYEL MAJU</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="auth-body">
    <main class="auth-page auth-page-centered">
        <div class="auth-motion" aria-hidden="true">

        </div><section class="auth-panel">
            <div class="auth-card-wrap">
                <a class="auth-floating-logo" href="{{ route('home') }}" aria-label="Kembali ke beranda">
                    <img src="{{ asset('images/Logo-Mili-removebg-preview.png') }}" alt="Logo CV MILI WORAYEL MAJU"></a>
                    <div class="auth-card">{{ $slot }}</div>
                </div>
            </section>
        </main>
    </body>
    </html>
