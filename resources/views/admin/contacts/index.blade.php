<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesan Masuk | Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <style>body{background:#f4f8f6;color:#193b35}.inbox-top{background:#103c36;color:white}.inbox-panel{border:0;border-radius:1rem;box-shadow:0 12px 36px #102c240d}.table>:not(caption)>*>*{padding:.9rem .75rem;vertical-align:middle}@media(max-width:575.98px){.table>:not(caption)>*>*{padding:.7rem .55rem}.inbox-filter>*{width:100%}}</style>
</head>
<body>
@include('admin.partials.navigation', ['activeModule' => 'pesan'])
<header class="inbox-top py-3"><div class="container-fluid px-3 px-md-4 d-flex flex-wrap justify-content-between align-items-center gap-3"><div><a href="{{ route('admin.dashboard') }}" class="small text-white-50 text-decoration-none">← Dashboard</a><h1 class="h4 mb-0 mt-1">Pesan masuk</h1></div><span class="badge rounded-pill text-bg-warning">{{ $unreadCount }} belum dibaca</span></div></header>
<main class="container-fluid px-3 px-md-4 py-4">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <section class="card inbox-panel"><div class="card-body p-3 p-md-4">
        <form method="GET" class="inbox-filter d-flex flex-column flex-sm-row gap-2 mb-3">
            <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, atau subjek" aria-label="Cari pesan">
            <select class="form-select" name="status" aria-label="Filter status"><option value="">Semua status</option>@foreach(['new'=>'Baru','read'=>'Dibaca','replied'=>'Ditindaklanjuti','archived'=>'Diarsipkan'] as $value=>$label)<option value="{{ $value }}" @selected(request('status')===$value)>{{ $label }}</option>@endforeach</select>
            <button class="btn btn-success">Filter</button><a class="btn btn-outline-secondary" href="{{ route('admin.contacts.index') }}">Reset</a>
        </form>
        @if($contacts->isEmpty())<div class="py-5 text-center text-secondary">Belum ada pesan yang sesuai.</div>@else
            <div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Pengirim</th><th>Subjek</th><th>Status</th><th>Diterima</th><th></th></tr></thead><tbody>
                @foreach($contacts as $contact)<tr><td><strong>{{ $contact->name }}</strong><small class="d-block text-secondary">{{ $contact->email }}</small></td><td>{{ \Illuminate\Support\Str::limit($contact->subject ?: $contact->message, 65) }}</td><td><span class="badge text-bg-{{ $contact->status==='new'?'warning':($contact->status==='archived'?'secondary':'success') }}">{{ ['new'=>'Baru','read'=>'Dibaca','replied'=>'Ditindaklanjuti','archived'=>'Diarsipkan'][$contact->status] ?? $contact->status }}</span></td><td>{{ \Illuminate\Support\Carbon::parse($contact->created_at)->format('d M Y H:i') }}</td><td><a class="btn btn-sm btn-outline-success" href="{{ route('admin.contacts.show',$contact->id) }}">Buka</a></td></tr>@endforeach
            </tbody></table></div>
            <div class="mt-3">{{ $contacts->links() }}</div>
        @endif
    </div></section>
</main>
</body></html>
