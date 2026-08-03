<x-layouts.auth title="Masuk">
    <p class="section-label">PORTAL PENGGUNA</p>
    <h1>Selamat <em>datang.</em></h1>
    <p class="muted">Masukkan username/email dan password untuk mengakses dashboard.</p>
    @if(session('status'))<p class="success">{{ session('status') }}</p>@endif
    <form method="POST" action="{{ route('login.store') }}" class="auth-form">
        @csrf
        <label>Email atau username<input name="login" value="{{ old('login') }}" required autofocus autocomplete="username">@error('login')<small class="error">{{ $message }}</small>@enderror</label>
        <label>Password<input name="password" type="password" required autocomplete="current-password">@error('password')<small class="error">{{ $message }}</small>@enderror</label>
        @if($captchaQuestion)<label>Verifikasi keamanan: berapa hasil {{ $captchaQuestion }}?<input name="captcha_answer" inputmode="numeric" required autocomplete="off">@error('captcha_answer')<small class="error">{{ $message }}</small>@enderror</label><label class="check"><input type="checkbox" required> Saya bukan robot</label>@endif
        <label class="check"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
        <button class="button" type="submit">Masuk <span>→</span></button>
    </form>
    <p class="auth-foot"><a href="{{ route('password.request') }}">Lupa password?</a></p>
    <p class="auth-foot">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
</x-layouts.auth>
