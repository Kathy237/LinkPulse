<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class VisitController extends Controller
{
    use AuthorizesRequests;

    // Toutes les visites des portfolios de l'utilisateur connecté
    public function index(Request $request)
    {
        $portfolioIds = $request->user()->portfolios()->pluck('id');
        $visits = Visit::whereIn('portfolio_id', $portfolioIds)
            ->with('portfolio')
            ->orderBy('visited_at', 'desc')
            ->get();
        return response()->json($visits);
    }

    // Visites d'un portfolio spécifique (propriétaire seulement)
    public function portfolioVisits(Portfolio $portfolio, Request $request)
    {
        $this->authorize('view', $portfolio);
        $visits = $portfolio->visits()->orderBy('visited_at', 'desc')->get();
        return response()->json($visits);
    }

    // Historique d'un visiteur anonyme (device_id)
    public function visitorHistory(Request $request)
{
    try {
        $deviceId = $request->header('X-Device-ID');
        if (!$deviceId) {
            return response()->json(['message' => 'Device ID manquant'], 400);
        }

        $history = Visit::where('device_id', $deviceId)
            ->with('portfolio:id,display_name,slug,profile_photo')
            ->select('portfolio_id', \DB::raw('MAX(visited_at) as last_visit'))
            ->groupBy('portfolio_id')
            ->orderBy('last_visit', 'desc')
            ->get();

        return response()->json($history);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500);
    }
}

    // Mettre à jour les infos d'un visiteur (crayon)
    public function updateVisitorInfo(Request $request, Visit $visit)
    {
        $this->authorize('update', $visit->portfolio);
        $validated = $request->validate([
            'visitor_name' => 'nullable|string|max:191',
            'visitor_email' => 'nullable|email|max:191',
            'visitor_phone' => 'nullable|string|max:50',
            'visitor_address' => 'nullable|string',
            'visitor_social_links' => 'nullable|array',
        ]);
        $visit->update($validated);
        return response()->json(['message' => 'Informations mises à jour', 'visit' => $visit]);
    }

    // Export CSV des visites
    public function export(Request $request)
    {
        $user = $request->user();
        $portfolioIds = $user->portfolios()->pluck('id');
        $visits = Visit::whereIn('portfolio_id', $portfolioIds)->with('portfolio')->get();
        $csv = "ID,Portfolio,Source,IP,Visited At,Visitor Name,Visitor Email,Visitor Phone\n";
        foreach ($visits as $visit) {
            $csv .= implode(',', [
                $visit->id,
                $visit->portfolio->display_name,
                $visit->source,
                $visit->ip_address,
                $visit->visited_at,
                $visit->visitor_name,
                $visit->visitor_email,
                $visit->visitor_phone,
            ]) . "\n";
        }
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="visits.csv"');
    }

    // Fusion de l'historique anonyme vers un compte (après inscription)
    public function mergeVisitorHistory(Request $request)
    {
        $deviceId = $request->header('X-Device-ID');
        if (!$deviceId) {
            return response()->json(['message' => 'Device ID manquant'], 400);
        }
        $user = $request->user();
        // Associer toutes les visites anonymes à cet utilisateur
        Visit::where('device_id', $deviceId)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id]);
        // Idem pour nfc_reads (si table modifiée)
        // NfcRead::where('visitor_device_id', $deviceId)->whereNull('user_id')->update(['user_id' => $user->id]);
        return response()->json(['message' => 'Historique fusionné avec succès.']);
    }
}