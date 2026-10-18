<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ContactManagementController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAction('view');
        $query = DB::table('contacts')->latest('created_at');

        if ($request->filled('status') && in_array($request->query('status'), ['new', 'read', 'replied', 'archived'], true)) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('q')) {
            $term = '%'.$request->string('q')->trim().'%';
            $query->where(function ($builder) use ($term) {
                $builder->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('subject', 'like', $term);
            });
        }

        $contacts = $query->paginate(15)->withQueryString();
        $unreadCount = DB::table('contacts')->where('status', 'new')->count();

        return view('admin.contacts.index', compact('contacts', 'unreadCount'));
    }

    public function show(int $id): View
    {
        $this->authorizeAction('view');
        $contact = DB::table('contacts')->find($id);
        abort_unless($contact, 404);

        return view('admin.contacts.show', compact('contact'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $this->authorizeAction('update');
        $contact = DB::table('contacts')->find($id);
        abort_unless($contact, 404);
        $data = $request->validate([
            'status' => ['required', 'in:new,read,replied,archived'],
        ]);

        DB::table('contacts')->where('id', $id)->update([
            'status' => $data['status'],
            'updated_at' => now(),
        ]);
        ActivityLogger::record('Memperbarui status pesan masuk', 'contact', $id, [
            'status' => $data['status'],
        ]);

        return back()->with('success', 'Status pesan berhasil diperbarui.');
    }

    private function authorizeAction(string $action): void
    {
        abort_unless(auth()->user()?->canAccessAdminModule('pesan', $action), 403);
    }
}
