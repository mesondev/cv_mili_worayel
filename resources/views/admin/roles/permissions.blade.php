<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hak Akses {{ $role->name }} | Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <style>body{background:#f4f8f6;color:#193b35}.access-top{background:#103c36;color:white}.access-card{border:0;border-radius:1rem;box-shadow:0 12px 36px #102c240d}.access-module{border:1px solid #e5ece8;border-radius:.9rem}.permission-check{min-height:2.5rem}.access-actions{position:sticky;bottom:0;background:#fffffff2;backdrop-filter:blur(10px)}@media(max-width:575.98px){.access-actions .btn{width:100%}}</style>
</head>
<body>
@include('admin.partials.navigation', ['activeModule' => 'peran'])
<header class="access-top py-3"><div class="container"><a href="{{ route('admin.module','peran') }}" class="small text-white-50 text-decoration-none">← Role &amp; Permission</a><h1 class="h4 mb-0 mt-1">Hak akses: {{ $role->name }}</h1></div></header>
<main class="container py-4 py-md-5">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <section class="card access-card"><div class="card-body p-3 p-md-4">
        @if($role->slug==='super-admin')
            <div class="alert alert-info mb-0">Super Admin memiliki seluruh hak akses. Hak wildcard ini tidak dapat diubah dari panel.</div>
        @else
            <p class="text-secondary">Pilih izin per modul. Hak akses diperiksa kembali oleh server pada setiap route, termasuk tindakan perubahan data.</p>
            <form method="POST" action="{{ route('admin.roles.permissions.update',$role->id) }}">@csrf @method('PUT')
                <div class="row g-3">
                    @foreach($permissions as $module=>$modulePermissions)
                        <div class="col-12 col-md-6 col-xl-4">
                            <fieldset class="access-module h-100 p-3">
                                <legend class="float-none w-auto px-2 h6 fw-bold text-capitalize">{{ str_replace('_',' ',$module) }}</legend>
                                @foreach($modulePermissions as $permission)
                                    <div class="form-check permission-check d-flex align-items-center gap-2">
                                        <input class="form-check-input mt-0" type="checkbox" name="permissions[]" value="{{ $permission->slug }}" id="permission-{{ $permission->id }}" @checked($role->permissions->contains('id',$permission->id))>
                                        <label class="form-check-label text-capitalize" for="permission-{{ $permission->id }}">{{ str_replace('_',' ',str($permission->slug)->after('.')->toString()) }}</label>
                                    </div>
                                @endforeach
                            </fieldset>
                        </div>
                    @endforeach
                </div>
                <div class="access-actions mt-4 py-3 d-flex flex-column flex-sm-row gap-2">
                    <button class="btn btn-success">Simpan hak akses</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.module','peran') }}">Kembali</a>
                </div>
            </form>
        @endif
    </div></section>
</main>
</body></html>
