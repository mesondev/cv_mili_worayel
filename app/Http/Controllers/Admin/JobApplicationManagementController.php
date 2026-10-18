<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class JobApplicationManagementController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAction('view');
        $query = DB::table('job_applications')
            ->join('careers', 'job_applications.career_id', '=', 'careers.id')
            ->select('job_applications.*', 'careers.title as career_title')
            ->latest('job_applications.created_at');

        if ($request->filled('status') && in_array($request->query('status'), ['new', 'reviewed', 'interview', 'rejected', 'accepted'], true)) {
            $query->where('job_applications.status', $request->query('status'));
        }

        if ($request->filled('q')) {
            $term = '%'.$request->string('q')->trim().'%';
            $query->where(function ($builder) use ($term) {
                $builder->where('job_applications.name', 'like', $term)
                    ->orWhere('job_applications.email', 'like', $term)
                    ->orWhere('job_applications.reference_code', 'like', $term)
                    ->orWhere('careers.title', 'like', $term);
            });
        }

        $applications = $query->paginate(15)->withQueryString();
        $newCount = DB::table('job_applications')->where('status', 'new')->count();

        return view('admin.applications.index', compact('applications', 'newCount'));
    }

    public function show(int $id): View
    {
        $this->authorizeAction('view');
        $application = DB::table('job_applications')
            ->join('careers', 'job_applications.career_id', '=', 'careers.id')
            ->select('job_applications.*', 'careers.title as career_title')
            ->where('job_applications.id', $id)
            ->first();
        abort_unless($application, 404);

        return view('admin.applications.show', compact('application'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $this->authorizeAction('update');
        $application = DB::table('job_applications')->find($id);
        abort_unless($application, 404);
        $data = $request->validate([
            'status' => ['required', 'in:new,reviewed,interview,rejected,accepted'],
            'internal_notes' => ['nullable', 'string', 'max:10000'],
        ]);

        DB::table('job_applications')->where('id', $id)->update([
            'status' => $data['status'],
            'internal_notes' => $data['internal_notes'] ?? null,
            'updated_at' => now(),
        ]);
        ActivityLogger::record('Memperbarui status lamaran kerja', 'job_application', $id, [
            'status' => $data['status'],
        ]);

        return back()->with('success', 'Lamaran berhasil diperbarui.');
    }

    public function downloadCv(int $id): StreamedResponse
    {
        $this->authorizeAction('view');
        $application = DB::table('job_applications')->find($id);
        abort_unless($application && $application->cv_path, 404);
        abort_unless(Storage::disk('local')->exists($application->cv_path), 404);

        ActivityLogger::record('Mengunduh CV pelamar', 'job_application', $id);

        return Storage::disk('local')->download(
            $application->cv_path,
            'lamaran-'.($application->reference_code ?: $application->id).'.'.pathinfo($application->cv_path, PATHINFO_EXTENSION)
        );
    }

    private function authorizeAction(string $action): void
    {
        abort_unless(auth()->user()?->canAccessAdminModule('lamaran', $action), 403);
    }
}
