<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class CareerController extends Controller
{
    public function index(): View
    {
        $careers = DB::table('careers')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')->orWhereDate('expires_at', '>=', today());
            })
            ->latest('created_at')
            ->paginate(9);

        return view('home.careers.index', compact('careers'));
    }

    public function show(int $career): View
    {
        $career = $this->activeCareer($career);

        return view('home.careers.show', compact('career'));
    }

    public function store(Request $request, int $career): RedirectResponse
    {
        $career = $this->activeCareer($career);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:25'],
            'cv' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'cover_letter' => ['nullable', 'string', 'max:5000'],
            'privacy_consent' => ['accepted'],
        ]);

        $cvPath = $request->file('cv')->store('applications', 'local');
        $referenceCode = 'MW-'.now()->format('Y').'-'.Str::upper(Str::random(10));

        try {
            $applicationId = DB::table('job_applications')->insertGetId([
                'career_id' => $career->id,
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'cv_path' => $cvPath,
                'cover_letter' => $data['cover_letter'] ?? null,
                'status' => 'new',
                'reference_code' => $referenceCode,
                'privacy_consent_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($cvPath);

            throw $exception;
        }

        return redirect()->route('careers.show', $career->id)
            ->with('application_reference', $referenceCode);
    }

    private function activeCareer(int $id): object
    {
        $career = DB::table('careers')->where('id', $id)->where('is_active', true)->first();
        abort_unless(
            $career && (! $career->expires_at || $career->expires_at >= today()->toDateString()),
            404
        );

        return $career;
    }
}
