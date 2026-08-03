<x-layouts.auth title="Lupa Password">
    <p class="section-label">RESET PASSWORD</p>
    <h1>Atur ulang <em>password.</em></h1>
    <p class="muted">Masukkan email akun Anda. Kami akan mengirimkan tautan reset yang berlaku 60 menit.</p>
    @if(session('status'))<p class="success">{{ session('status') }}</p>@endif
    @if(session('error'))<p class="error">{{ session('error') }}</p>@endif
    <form method="POST" action="{{ route('password.email') }}" class="auth-form">@csrf<label>Email<input name="email" type="email" value="{{ old('email') }}" required autofocus></label>@error('email')<p class="error">{{ $message }}</p>@enderror<button class="button" type="submit">Kirim tautan reset <span>→</span></button></form>
    <p class="auth-foot"><a href="{{ route('login') }}">Kembali ke masuk</a></p>
</x-layouts.auth>
