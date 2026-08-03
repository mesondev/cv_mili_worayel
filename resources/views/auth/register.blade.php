<x-layouts.auth title="Daftar">
    <p class="section-label">BUAT AKUN</p>
    <h1>Mari mulai <em>bersama.</em></h1>
    <p class="muted">Akun akan aktif setelah email Anda diverifikasi.</p>
    <form method="POST" action="{{ route('register.store') }}" class="auth-form" enctype="multipart/form-data">
        @csrf
        <label>Nama lengkap<input name="name" value="{{ old('name') }}" required>@error('name')<small class="error">{{ $message }}</small>@enderror</label>
        <label>Username<input name="username" value="{{ old('username') }}" required autocomplete="username">@error('username')<small class="error">{{ $message }}</small>@enderror</label>
        <label>Email<input name="email" type="email" value="{{ old('email') }}" required autocomplete="email">@error('email')<small class="error">{{ $message }}</small>@enderror</label>
        <label>Nomor HP<input name="phone" value="{{ old('phone') }}" required>@error('phone')<small class="error">{{ $message }}</small>@enderror</label>
        <label>Foto profil <small>(opsional, maks. 2 MB)</small><input name="avatar" type="file" accept="image/*"></label>
        <label>Alamat <small>(opsional)</small><textarea name="address">{{ old('address') }}</textarea></label>
        <label>Password<input name="password" type="password" required autocomplete="new-password">@error('password')<small class="error">{{ $message }}</small>@enderror</label>
        <label>Konfirmasi password<input name="password_confirmation" type="password" required autocomplete="new-password"></label>
        <label class="check"><input name="terms" type="checkbox" required> Saya setuju dengan kebijakan privasi dan syarat layanan.</label>
        @if($errors->any())<p class="error">Pendaftaran belum berhasil. Periksa dan lengkapi kolom yang ditandai.</p>@endif
        <button class="button" type="submit">Daftar <span>→</span></button>
    </form>
    <p class="auth-foot">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
</x-layouts.auth>
