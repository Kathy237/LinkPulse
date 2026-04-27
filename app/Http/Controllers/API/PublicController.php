<?php
// app/Http/Controllers/API/PublicController.php

namespace App\Http\Controllers\API;

use App\Models\Report;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\PortfolioResource;
use App\Models\Portfolio;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicController extends Controller
{
    /**
     * Afficher le portfolio public (sans authentification).
     * Enregistre une visite avec la source fournie dans la requête.
     */
    public function showPortfolio($slug, Request $request)
    {
        $portfolio = Portfolio::where('slug', $slug)
            ->with(['projects', 'socialLinks', 'customLinks'])
            ->firstOrFail();

        // Récupérer la source (ref) : direct, nfc, qr
        $source = $request->query('ref', 'direct');
        if (!in_array($source, ['direct', 'nfc', 'qr'])) {
            $source = 'direct';
        }

        // Device ID pour suivi anonyme
        $deviceId = $request->header('X-Device-ID');
        if (!$deviceId) {
            $deviceId = 'anonymous_' . md5($request->ip() . $request->userAgent());
        }

        // Enregistrer la visite
        Visit::create([
            'portfolio_id' => $portfolio->id,
            'source' => $source,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'visited_at' => now(),
        ]);

        // Retourner les données du portfolio
        return new PortfolioResource($portfolio);
    }

    /**
     * Générer la vCard du portfolio (téléchargement).
     */
    public function vcard(Portfolio $portfolio)
    {
        $vcard = "BEGIN:VCARD\r\nVERSION:3.0\r\n";
        $vcard .= "FN:{$portfolio->display_name}\r\n";
        if ($portfolio->vcard_email) {
            $vcard .= "EMAIL:{$portfolio->vcard_email}\r\n";
        }
        if ($portfolio->vcard_phone) {
            $vcard .= "TEL;TYPE=WORK:{$portfolio->vcard_phone}\r\n";
        }
        if ($portfolio->vcard_address) {
            $vcard .= "ADR:;;{$portfolio->vcard_address};;;\r\n";
        }
        $vcard .= "END:VCARD\r\n";

        return response($vcard)
            ->header('Content-Type', 'text/vcard')
            ->header('Content-Disposition', 'attachment; filename="contact.vcf"');
    }

    /**
     * Télécharger le CV (fichier).
     */
    public function downloadCv(Portfolio $portfolio)
    {
        if (!$portfolio->cv_file || !Storage::disk('public')->exists($portfolio->cv_file)) {
            return response()->json(['message' => 'CV non trouvé.'], 404);
        }

        return Storage::disk('public')->download($portfolio->cv_file);
    }

       
    public function report(Request $request)
    {
    $validated = $request->validate([
        'reporter_name' => 'nullable|string|max:191',
        'reporter_email' => 'nullable|email',
        'reported_portfolio_id' => 'nullable|exists:portfolios,id',
        'reported_user_email' => 'nullable|email|exists:users,email',
        'message' => 'required|string',
    ]);
    $reportedUserId = null;
    if (!empty($validated['reported_user_email'])) {
        $user = User::where('email', $validated['reported_user_email'])->first();
        $reportedUserId = $user->id;
    }
    $report = Report::create([
        'reporter_name' => $validated['reporter_name'] ?? null,
        'reporter_email' => $validated['reporter_email'] ?? null,
        'reported_portfolio_id' => $validated['reported_portfolio_id'] ?? null,
        'reported_user_id' => $reportedUserId,
        'message' => $validated['message'],
        'status' => 'pending',
        'admin_notified' => false,
    ]);
    return response()->json(['message' => 'Signalement envoyé', 'report' => $report], 201);
    }
}