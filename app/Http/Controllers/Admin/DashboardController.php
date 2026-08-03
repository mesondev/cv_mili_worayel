<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $count = fn (string $table): int => DB::getSchemaBuilder()->hasTable($table) ? DB::table($table)->count() : 0;
        $metrics = [
            ['label' => 'Proyek Selesai', 'value' => $count('projects'), 'icon' => '◉'],
            ['label' => 'Mitra Kerja', 'value' => $count('partners'), 'icon' => '◌'],
            ['label' => 'Tenaga Ahli', 'value' => $count('employees'), 'icon' => '◇'],
            ['label' => 'Tahun Pengalaman', 'value' => max(1, (int) date('Y') - 2009), 'icon' => '✦'],
        ];

        return view('admin.dashboard', ['metrics' => $metrics, 'chartLabels' => collect($metrics)->pluck('label'), 'chartValues' => collect($metrics)->pluck('value')]);
    }
}
