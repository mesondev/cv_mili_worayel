<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class VisitorAnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(auth()->user()?->canAccessAdminModule('analytics'), 403);

        $dates = $this->validatedDateRange($request, 30);
        $query = DB::table('visitor_logs')
            ->whereBetween('visited_at', [$dates['from'].' 00:00:00', $dates['to'].' 23:59:59']);

        $dailyRows = (clone $query)
            ->selectRaw('DATE(visited_at) as visit_date, COUNT(*) as total')
            ->groupBy('visit_date')
            ->orderBy('visit_date')
            ->get()
            ->keyBy('visit_date');
        $daily = collect();
        $cursor = Carbon::parse($dates['from']);
        $end = Carbon::parse($dates['to']);
        while ($cursor->lte($end)) {
            $key = $cursor->toDateString();
            $daily->push(['label' => $cursor->format('d M'), 'value' => (int) ($dailyRows[$key]->total ?? 0)]);
            $cursor->addDay();
        }

        $topPages = (clone $query)
            ->select('path')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('path')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
        $visits = (clone $query)->count();
        $pageCount = (clone $query)->distinct('path')->count('path');
        $recentVisits = (clone $query)
            ->select('path', 'visited_at')
            ->latest('visited_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.analytics.index', [
            'from' => $dates['from'],
            'to' => $dates['to'],
            'visits' => $visits,
            'pageCount' => $pageCount,
            'topPages' => $topPages,
            'dailyLabels' => $daily->pluck('label'),
            'dailyValues' => $daily->pluck('value'),
            'recentVisits' => $recentVisits,
        ]);
    }

    private function validatedDateRange(Request $request, int $defaultDays): array
    {
        $today = today()->toDateString();
        $validator = Validator::make($request->query(), [
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $dates = [
            'from' => $request->query('from', today()->subDays($defaultDays - 1)->toDateString()),
            'to' => $request->query('to', $today),
        ];
        $start = Carbon::parse($dates['from']);
        $end = Carbon::parse($dates['to']);
        if ($start->gt($end)) {
            throw ValidationException::withMessages(['from' => 'Tanggal awal tidak boleh setelah tanggal akhir.']);
        }
        if ($start->diffInDays($end) > 366) {
            throw ValidationException::withMessages(['from' => 'Rentang analitik maksimal 366 hari.']);
        }

        return $dates;
    }
}
