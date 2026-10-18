<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Profil Saya | Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{background:#f5f7f7;color:#1e302d}.panel{border:0;border-radius:1rem;box-shadow:0 8px 25px #152f2b0f}.form-label{font-weight:600}</style>
</head>
<body>
@include('admin.partials.navigation', ['activeModule' => 'account'])
<header class="bg-white border-bottom sticky-top"><div class="container-fluid px-3 px-md-4 py-3 d-flex justify-content-between align-items-center gap-3"><div><a href="{{ route('admin.dashboard') }}" class="small text-success text-decoration-none">← Dashboard</a><h1 class="h3 fw-bold mb-0 mt-1">Profil Saya</h1></div><span class="badge text-bg-light">{{ $user->role?->name }}</span></div></header>
<main class="container p-3 p-md-4">
    @if(session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
    <section class="card panel mb-4"><div class="card-body p-3 p-md-4">
        <h2 class="h5 fw-bold">Informasi akun</h2>
        <p class="text-secondary">Perubahan alamat email akan meminta verifikasi ulang. Peran akun hanya dapat diubah oleh administrator berwenang.</p>
        <form method="POST" action="{{ route('admin.account.update') }}" class="row g-3">
            @csrf @method('PUT')
            <div class="col-12 col-md-6"><label class="form-label" for="name">Nama</label><input class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required maxlength="100">@error('name')<small class="text-danger">{{ $message }}</small>@enderror</div>
            <div class="col-12 col-md-6"><label class="form-label" for="username">Username</label><input class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" required maxlength="30">@error('username')<small class="text-danger">{{ $message }}</small>@enderror</div>
            <div class="col-12 col-md-6"><label class="form-label" for="email">Email</label><input class="form-control" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required maxlength="255">@error('email')<small class="text-danger">{{ $message }}</small>@enderror</div>
            <div class="col-12 col-md-6"><label class="form-label" for="phone">Telepon</label><input class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" maxlength="25">@error('phone')<small class="text-danger">{{ $message }}</small>@enderror</div>
            <div class="col-12"><button class="btn btn-success">Simpan profil</button></div>
        </form>
    </div></section>
    <section class="card panel"><div class="card-body p-3 p-md-4">
        <h2 class="h5 fw-bold">Ubah password</h2>
        <p class="text-secondary">Gunakan password unik minimal 12 karakter, dengan huruf besar/kecil, angka, dan simbol.</p>
        <form method="POST" action="{{ route('admin.account.password') }}" class="row g-3">
            @csrf @method('PUT')
            <div class="col-12"><label class="form-label" for="current_password">Password saat ini</label><input class="form-control" id="current_password" name="current_password" type="password" autocomplete="current-password" required>@error('current_password')<small class="text-danger">{{ $message }}</small>@enderror</div>
            <div class="col-12 col-md-6"><label class="form-label" for="password">Password baru</label><input class="form-control" id="password" name="password" type="password" autocomplete="new-password" required>@error('password')<small class="text-danger">{{ $message }}</small>@enderror</div>
            <div class="col-12 col-md-6"><label class="form-label" for="password_confirmation">Konfirmasi password baru</label><input class="form-control" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required></div>
            <div class="col-12"><button class="btn btn-outline-success">Perbarui password</button></div>
        </form>
    </div></section>
</main>
</body>
</html>
