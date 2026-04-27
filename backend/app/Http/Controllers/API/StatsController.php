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

        // Modification selon les consignes : vues du mois en distinguant QR et Lien
        $startOfMonth = \Carbon\Carbon::now()->startOfMonth();
        $monthlyVisits = (clone $visitsQuery)->where('visited_at', '>=', $startOfMonth)->get();
        $scansThisMonth = $monthlyVisits->count();
        $qrViews = $monthlyVisits->where('source', 'qr')->count();
        $linkViews = $monthlyVisits->where('source', 'link')->count();

        $scansToday = (clone $visitsQuery)->whereDate('visited_at', now()->toDateString())->count();
        $scansBySource = (clone $visitsQuery)->select('source', DB::raw('count(*) as total'))->groupBy('source')->get();

        return response()->json([
            'total_portfolios' => $totalPortfolios,
            'total_projects' => $totalProjects,
            'unlinked_nfc' => $unlinkedNfc,
            'scans_lifetime' => $scansLifetime,
            'scans_this_month' => $scansThisMonth,
            'total_views' => $scansThisMonth, // Mapping pour le front
            'qr_views' => $qrViews,           // Mapping pour le front
            'link_views' => $linkViews,       // Mapping pour le front
            'scans_today' => $scansToday,
            'scans_by_source' => $scansBySource,
        ]);
    }
}