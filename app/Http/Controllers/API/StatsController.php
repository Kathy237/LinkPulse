<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function dashboardStats(Request $request)
    {
        $user = $request->user();
        $portfolios = $user->portfolios;
        $totalPortfolios = $portfolios->count();
        $totalProjects = Project::whereIn('portfolio_id', $portfolios->pluck('id'))->count();
        $unlinkedNfc = $portfolios->filter(fn($p) => $p->nfcCards->isEmpty())->count();

        $visitsQuery = Visit::whereIn('portfolio_id', $portfolios->pluck('id'));
        $scansLifetime = $visitsQuery->count();
        $scansThisMonth = (clone $visitsQuery)->whereMonth('visited_at', now()->month)->count();
        $scansToday = (clone $visitsQuery)->whereDate('visited_at', now()->toDateString())->count();
        $scansBySource = (clone $visitsQuery)->select('source', DB::raw('count(*) as total'))->groupBy('source')->get();

        return response()->json([
            'total_portfolios' => $totalPortfolios,
            'total_projects' => $totalProjects,
            'unlinked_nfc' => $unlinkedNfc,
            'scans_lifetime' => $scansLifetime,
            'scans_this_month' => $scansThisMonth,
            'scans_today' => $scansToday,
            'scans_by_source' => $scansBySource,
        ]);
    }
}