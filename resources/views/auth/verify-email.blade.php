<x-layouts.auth title="Verifikasi Email">
    <p class="section-label">AKTIVASI AKUN</p>
    <h1>Periksa <em>email Anda.</em></h1>
    <p class="muted">Kami telah mengirim tautan verifikasi. Klik tautan tersebut sebelum masuk ke dashboard.</p>
    @if(session('status'))<p class="success">{{ session('status') }}</p>@endif
    <form method="POST" action="{{ route('verification.send') }}" class="auth-form">@csrf<button class="button" type="submit">Kirim ulang verifikasi <span>→</span></button></form>
    <p class="auth-foot"><a href="{{ route('home') }}">Kembali ke beranda</a></p>
</x-layouts.auth>
