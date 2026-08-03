<x-layouts.auth title="Password Baru">
    <p class="section-label">RESET PASSWORD</p>
    <h1>Buat password <em>baru.</em></h1>
    <form method="POST" action="{{ route('password.update') }}" class="auth-form">@csrf<input type="hidden" name="token" value="{{ $token }}"><label>Email<input name="email" type="email" value="{{ old('email') }}" required></label><label>Password baru<input name="password" type="password" required autocomplete="new-password"></label><label>Konfirmasi password<input name="password_confirmation" type="password" required autocomplete="new-password"></label>@if($errors->any())<p class="error">{{ $errors->first() }}</p>@endif<button class="button" type="submit">Simpan password baru <span>→</span></button></form>
</x-layouts.auth>
