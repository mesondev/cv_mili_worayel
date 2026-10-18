<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(auth()->user()?->canAccessAdminModule('log'), 403);
        $dates = $this->validatedDateRange($request);

        $query = DB::table('activity_logs')
            ->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
            ->select(
                'activity_logs.id',
                'activity_logs.event',
                'activity_logs.subject_type',
                'activity_logs.subject_id',
                'activity_logs.created_at',
                'users.name as user_name'
            )
            ->whereBetween('activity_logs.created_at', [$dates['from'].' 00:00:00', $dates['to'].' 23:59:59']);

        if ($request->filled('q')) {
            $term = '%'.$request->string('q')->trim().'%';
            $query->where(function ($builder) use ($term) {
                $builder->where('activity_logs.event', 'like', $term)
                    ->orWhere('activity_logs.subject_type', 'like', $term)
                    ->orWhere('users.name', 'like', $term);
            });
        }

        $logs = $query->latest('activity_logs.created_at')->paginate(20)->withQueryString();

        return view('admin.activity.index', [
            'logs' => $logs,
            'from' => $dates['from'],
            'to' => $dates['to'],
        ]);
    }

    private function validatedDateRange(Request $request): array
    {
        $validator = Validator::make($request->query(), [
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $dates = [
            'from' => $request->query('from', today()->subDays(29)->toDateString()),
            'to' => $request->query('to', today()->toDateString()),
        ];
        $start = Carbon::parse($dates['from']);
        $end = Carbon::parse($dates['to']);
        if ($start->gt($end)) {
            throw ValidationException::withMessages(['from' => 'Tanggal awal tidak boleh setelah tanggal akhir.']);
        }
        if ($start->diffInDays($end) > 366) {
            throw ValidationException::withMessages(['from' => 'Rentang log maksimal 366 hari.']);
        }

        return $dates;
    }
}
