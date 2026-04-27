<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

/**
 * PublicController — Pages accessibles sans connexion.
 *
 * - Affichage du portfolio public (page visiteur)
 * - Signalement d'un utilisateur frauduleux
 * - Historique des tags NFC (via cookie visiteur)
 * - Téléchargement vCard / CV
 */
class PublicController extends Controller
{
    protected ApiService $api;

    public function __construct(ApiService $api)
    {
        $this->api = $api;
    }

    // ─────────────────────────────────────────────────────────────
    // PORTFOLIO PUBLIC (page que le visiteur voit via NFC / QR)
    // ─────────────────────────────────────────────────────────────

    /**
     * Affiche le portfolio public d'un utilisateur.
     * Le slug est l'identifiant unique du portfolio (ex: /p/john-doe-pro)
     */
    public function showPortfolio(string $slug)
{
    $response = $this->api->get("/public/p/{$slug}");

    if (!$response->successful()) {
        abort(404, 'Ce portfolio n\'existe pas ou a été supprimé.');
    }

    $data = $response->json();
    // Si la réponse est encapsulée dans une clé 'data', on l'extrait
    if (is_array($data) && isset($data['data'])) {
        $portfolio = $data['data'];
    } else {
        $portfolio = $data;
    }

    // Générer un ID si absent (cas improbable, mais sécurité)
    if (!isset($portfolio['id'])) {
        $portfolio['id'] = null;
    }

    // Identifier le visiteur via un cookie anonyme
    $visitorId = request()->cookie('visitor_id') ?? \Illuminate\Support\Str::uuid();

    $response = response()->view('public.portfolio', compact('portfolio'))
        ->cookie('visitor_id', $visitorId, 60 * 24 * 365); // 1 an

    return $response;
}


    /**
     * Télécharge le CV d'un portfolio
     */
    public function downloadCv(int $portfolioId)
    {
        $response = $this->api->get("/public/portfolio/{$portfolioId}/cv");

        if ($response->successful()) {
            return response($response->body(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="cv.pdf"');
        }

        abort(404, 'CV non disponible.');
    }

    /**
     * Télécharge la vCard d'un portfolio
     */
    public function downloadVcard(int $portfolioId)
    {
        $response = $this->api->get("/public/portfolio/{$portfolioId}/vcard");

        if ($response->successful()) {
            return response($response->body(), 200)
                ->header('Content-Type', 'text/vcard')
                ->header('Content-Disposition', 'attachment; filename="contact.vcf"');
        }

        abort(404, 'vCard non disponible.');
    }

    // ─────────────────────────────────────────────────────────────
    // ESPACE VISITEUR
    // ─────────────────────────────────────────────────────────────

    /** Page visiteur — affiche son historique de tags NFC */
    public function visitorHome()
    {
        $response = $this->api->get('/public/visitor/history');
        $history = $response->successful() ? $response->json() : [];

        return view('visitor.home', compact('history'));
    }

    /** Formulaire de signalement d'un utilisateur frauduleux */
    public function showReport()
    {
        return view('visitor.report');
    }

    /** Traite le signalement */
    public function submitReport(Request $request)
    {
        $request->validate([
            'portfolio_url'    => 'nullable|url',
            'reported_email'   => 'nullable|email',
            'message'          => 'required|string|min:20|max:2000',
            'reporter_name'    => 'nullable|string|max:100',
            'reporter_email'   => 'nullable|email',
        ], [
            'message.required' => 'Veuillez décrire la fraude dont vous avez été victime.',
            'message.min'      => 'La description doit contenir au moins 20 caractères.',
        ]);

        // Au moins portfolio_url ou reported_email doit être fourni
        if (!$request->portfolio_url && !$request->reported_email) {
            return back()->withInput()
                         ->with('error', 'Veuillez fournir le lien du portfolio ou l\'email de l\'utilisateur frauduleux.');
        }

        $response = $this->api->post('/public/report', $request->all());

        if ($response->successful()) {
            return back()->with('success', 'Votre signalement a bien été transmis à l\'administrateur. Merci pour votre vigilance.');
        }

        return back()->withInput()
                     ->with('error', $response->json('message') ?? 'Erreur lors de l\'envoi du signalement.');
    }

    // ─────────────────────────────────────────────────────────────
    // LECTURE NFC
    // ─────────────────────────────────────────────────────────────

    /**
     * Redirige vers le portfolio associé à un UID de carte NFC.
     * Cette route est celle inscrite sur la puce NFC.
     */
    public function readNfc(string $uid)
    {
        $response = $this->api->get("/nfc/read/{$uid}");

        if ($response->successful()) {
            $data = $response->json();
            // Le backend retourne le slug du portfolio associé
            $slug = $data['portfolio']['slug'] ?? null;
            if ($slug) {
                return redirect("/p/{$slug}");
            }
        }

        // NFC non configuré ou portfolio supprimé
        return view('public.nfc-not-found', ['uid' => $uid]);
    }
}
