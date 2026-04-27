<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * UserController — Toutes les actions de l'espace utilisateur.
 *
 * Protégé par le middleware 'lp.auth'.
 * L'utilisateur doit avoir été approuvé par l'admin pour accéder à cet espace.
 */
class UserController extends Controller
{
    protected ApiService $api;

    public function __construct(ApiService $api)
    {
        $this->api = $api;
    }

    // ═══════════════════════════════════════════════════════════════
    // TABLEAU DE BORD
    // ═══════════════════════════════════════════════════════════════

    /** Statistiques globales de l'utilisateur (portfolios, projets, visites...) */
    public function dashboard()
    {
        $response = $this->api->get('/user/stats');
        $stats = $response->successful()
            ? $response->json()
            : ['total_portfolios' => 0, 'total_projects' => 0,
               'portfolios_without_nfc' => 0, 'monthly_link_visits' => 0,
               'monthly_qr_visits' => 0];

        // Notifications non lues
        $notifResponse = $this->api->get('/user/notifications');
        $notifications = $notifResponse->successful() ? $notifResponse->json() : [];

        return view('user.dashboard', compact('stats', 'notifications'));
    }

    // ═══════════════════════════════════════════════════════════════
    // PORTFOLIOS
    // ═══════════════════════════════════════════════════════════════

    /** Liste tous les portfolios de l'utilisateur */
    public function portfolios()
{
    $response = $this->api->get('/portfolios');
    $portfolios = [];
    if ($response->successful()) {
        $data = $response->json();
        // Si la réponse est encapsulée dans une clé 'data' (ressource Laravel)
        if (is_array($data) && isset($data['data'])) {
            $portfolios = $data['data'];
        } else {
            $portfolios = $data;
        }
        // Normalisation : garantir les clés 'id' et 'slug'
        foreach ($portfolios as &$portfolio) {
            if (!isset($portfolio['id']) && isset($portfolio['portfolio_id'])) {
                $portfolio['id'] = $portfolio['portfolio_id'];
            }
            if (!isset($portfolio['slug']) && isset($portfolio['slug'])) {
                // déjà présent, mais sécurité
            }
            // Si le slug est absent, on le génère à partir du nom ou on met une valeur par défaut
            if (!isset($portfolio['slug'])) {
                $portfolio['slug'] = 'portfolio-' . ($portfolio['id'] ?? 'inconnu');
            }
        }
    }
    return view('user.portfolios.index', compact('portfolios'));
}

    /**
 * Affiche le formulaire de création d'un nouveau portfolio.
 */
public function createPortfolio()
{
    return view('user.portfolios.create');
}

    /** Crée un nouveau portfolio (avec fichiers : photo, CV) */
    public function storePortfolio(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'photo'       => 'nullable|image|max:5120',
            'cv'          => 'nullable|file|mimes:pdf|max:10240',
            'portfolio_url' => 'nullable|url',
        ]);

        $files = [];
        if ($request->hasFile('photo')) $files['photo'] = $request->file('photo');
        if ($request->hasFile('cv'))    $files['cv']    = $request->file('cv');

        $data = $request->except(['photo', 'cv', '_token']);
        $response = $this->api->postMultipart('/portfolios', $data, $files);

        if ($response->successful()) {
            return redirect()->route('user.portfolios')
                             ->with('success', 'Portfolio créé avec succès !');
        }

        return back()->withInput()
                     ->withErrors($response->json('errors') ?? [])
                     ->with('error', $response->json('message') ?? 'Erreur lors de la création.');
    }

    /** Affiche le formulaire de modification d'un portfolio */
    public function editPortfolio($id)
{
    $response = $this->api->get("/portfolios/{$id}");

    if (!$response->successful()) {
        return redirect()->route('user.portfolios')
                         ->with('error', 'Portfolio introuvable.');
    }

    $data = $response->json();
    // Si la réponse est encapsulée dans une clé 'data' (ressource Laravel)
    if (is_array($data) && isset($data['data'])) {
        $portfolio = $data['data'];
    } else {
        $portfolio = $data;
    }

    // Normalisation : garantir les clés obligatoires
    $portfolio['id'] = $portfolio['id'] ?? null;
    $portfolio['title'] = $portfolio['title'] ?? '';
    $portfolio['description'] = $portfolio['description'] ?? '';
    $portfolio['photo_url'] = $portfolio['photo_url'] ?? null;
    $portfolio['cv_url'] = $portfolio['cv_url'] ?? null;
    $portfolio['portfolio_url'] = $portfolio['portfolio_url'] ?? null;
    $portfolio['social_links'] = $portfolio['social_links'] ?? [];
    $portfolio['custom_links'] = $portfolio['custom_links'] ?? [];
    $portfolio['projects'] = $portfolio['projects'] ?? [];

    return view('user.portfolios.create', compact('portfolio'));
}

    /** Met à jour un portfolio */
    public function updatePortfolio(Request $request, $id)
    {
        $request->validate([
            'title'         => 'required|string|max:150',
            'description'   => 'nullable|string|max:1000',
            'photo'         => 'nullable|image|max:5120',
            'cv'            => 'nullable|file|mimes:pdf|max:10240',
            'portfolio_url' => 'nullable|url',
        ]);

        $files = [];
        if ($request->hasFile('photo')) $files['photo'] = $request->file('photo');
        if ($request->hasFile('cv'))    $files['cv']    = $request->file('cv');

        $data = $request->except(['photo', 'cv', '_token', '_method']);
        $response = $this->api->putMultipart("/portfolios/{$id}", $data, $files);

        if ($response->successful()) {
            return redirect()->route('user.portfolios')
                             ->with('success', 'Portfolio mis à jour.');
        }

        return back()->withInput()
                     ->with('error', $response->json('message') ?? 'Erreur lors de la mise à jour.');
    }

    /** Supprime un portfolio (avec vérification NFC côté backend) */
    public function destroyPortfolio($id)
    {
        $response = $this->api->delete("/portfolios/{$id}");

        return $response->successful()
            ? redirect()->route('user.portfolios')->with('success', 'Portfolio supprimé.')
            : back()->with('error', $response->json('message') ?? 'Erreur lors de la suppression.');
    }

    /** Dissocier un portfolio de sa carte NFC */
    public function dissociateNfc($portfolioId)
    {
        $response = $this->api->post("/portfolios/{$portfolioId}/dissociate-nfc");

        return $response->successful()
            ? back()->with('success', 'Portfolio dissocié de la carte NFC.')
            : back()->with('error', $response->json('message') ?? 'Erreur lors de la dissociation.');
    }

    /** Génère et retourne le QR Code d'un portfolio (image base64) */
    public function qrcode($id)
    {
        $response = $this->api->get("/portfolios/{$id}/qrcode");

        if ($response->successful()) {
            $data = $response->json();
            return view('user.portfolios.qrcode', compact('data', 'id'));
        }

        return back()->with('error', 'Impossible de générer le QR code.');
    }

    // ═══════════════════════════════════════════════════════════════
    // PROJETS
    // ═══════════════════════════════════════════════════════════════

    /** Ajoute un projet à un portfolio */
    public function storeProject(Request $request, $portfolioId)
    {
        $request->validate([
            'title'       => 'required|string|max:200',
            'url'         => 'nullable|url',
            'description' => 'nullable|string|max:500',
            'github_url'  => 'nullable|url',
        ]);

        $response = $this->api->post("/portfolios/{$portfolioId}/projects", $request->all());

        return $response->successful()
            ? back()->with('success', 'Projet ajouté.')
            : back()->with('error', $response->json('message') ?? 'Erreur lors de l\'ajout du projet.');
    }

    /** Supprime un projet */
    public function destroyProject($projectId)
    {
        $response = $this->api->delete("/projects/{$projectId}");

        return $response->successful()
            ? back()->with('success', 'Projet supprimé.')
            : back()->with('error', $response->json('message') ?? 'Erreur.');
    }

    // ═══════════════════════════════════════════════════════════════
    // LIENS SOCIAUX & PERSONNALISÉS
    // ═══════════════════════════════════════════════════════════════

    /** Ajoute un lien social à un portfolio */
    public function storeSocialLink(Request $request, $portfolioId)
    {
        $request->validate([
            'platform' => 'required|string|max:50',
            'url'      => 'required|url',
        ]);

        $response = $this->api->post("/portfolios/{$portfolioId}/social-links", $request->all());

        return $response->successful()
            ? back()->with('success', 'Lien social ajouté.')
            : back()->with('error', $response->json('message') ?? 'Erreur.');
    }

    /** Supprime un lien social */
    public function destroySocialLink($linkId)
    {
        $response = $this->api->delete("/social-links/{$linkId}");
        return $response->successful() ? back()->with('success', 'Lien supprimé.') : back()->with('error', 'Erreur.');
    }

    /** Ajoute un lien personnalisé (autre site) */
    public function storeCustomLink(Request $request, $portfolioId)
    {
        $request->validate([
            'label' => 'required|string|max:100',
            'url'   => 'required|url',
        ]);

        $response = $this->api->post("/portfolios/{$portfolioId}/custom-links", $request->all());

        return $response->successful()
            ? back()->with('success', 'Lien ajouté.')
            : back()->with('error', $response->json('message') ?? 'Erreur.');
    }

    /** Supprime un lien personnalisé */
    public function destroyCustomLink($linkId)
    {
        $response = $this->api->delete("/custom-links/{$linkId}");
        return $response->successful() ? back()->with('success', 'Lien supprimé.') : back()->with('error', 'Erreur.');
    }

    // ═══════════════════════════════════════════════════════════════
    // CARTES NFC
    // ═══════════════════════════════════════════════════════════════

    /** Affiche l'onglet NFC (lister les cartes, associer/dissocier) */
    public function nfc()
    {
          // 1. Récupérer les cartes NFC de l'utilisateur
    $cardsResponse = $this->api->get('/me/nfc-cards');
    $nfcCards = [];
    if ($cardsResponse->successful()) {
        $data = $cardsResponse->json();
        // Extraction si encapsulé dans 'data'
        if (is_array($data) && isset($data['data'])) {
            $nfcCards = $data['data'];
        } else {
            $nfcCards = $data;
        }
        // Normalisation : garantir la clé 'id' pour chaque carte
        foreach ($nfcCards as &$card) {
            if (!isset($card['id']) && isset($card['card_id'])) {
                $card['id'] = $card['card_id'];
            }
        }
    }

    // 2. Récupérer tous les portfolios de l'utilisateur
    $portfoliosResponse = $this->api->get('/portfolios');
    $allPortfolios = [];
    if ($portfoliosResponse->successful()) {
        $data = $portfoliosResponse->json();
        if (is_array($data) && isset($data['data'])) {
            $allPortfolios = $data['data'];
        } else {
            $allPortfolios = $data;
        }
        // Normalisation : garantir les clés 'id' et 'nfc_uid'
        foreach ($allPortfolios as &$portfolio) {
            // Assure que 'id' existe
            if (!isset($portfolio['id']) && isset($portfolio['portfolio_id'])) {
                $portfolio['id'] = $portfolio['portfolio_id'];
            }
            // Assure que 'nfc_uid' existe (même null)
            if (!isset($portfolio['nfc_uid'])) {
                $portfolio['nfc_uid'] = null;
            }
        }
    }

    // 3. Filtrer les portfolios sans NFC
    $portfoliosWithoutNfc = array_filter($allPortfolios, fn($p) => empty($p['nfc_uid']));

    return view('user.nfc.index', compact('nfcCards', 'portfoliosWithoutNfc', 'allPortfolios'));    
    }

    /** Enregistre une carte NFC et l'associe à un portfolio */
    public function storeNfcCard(Request $request)
    {
        $request->validate([
            'uid'          => 'required|string|max:100',
            'portfolio_id' => 'required|integer',
        ], [
            'uid.required'          => 'L\'UID de la carte NFC est obligatoire.',
            'portfolio_id.required' => 'Veuillez sélectionner un portfolio.',
        ]);

        $response = $this->api->post('/nfc/cards', $request->all());

        return $response->successful()
            ? back()->with('success', 'Carte NFC enregistrée et associée au portfolio.')
            : back()->with('error', $response->json('message') ?? 'Erreur lors de l\'enregistrement.');
    }

    /** Supprime une carte NFC */
    public function destroyNfcCard($cardId)
    {
        $response = $this->api->delete("/nfc/cards/{$cardId}");

        return $response->successful()
            ? back()->with('success', 'Carte NFC supprimée.')
            : back()->with('error', $response->json('message') ?? 'Erreur.');
    }

    /** Dissocie une carte NFC d'un portfolio (sans supprimer la carte) */
    public function dissociateNfcCard($cardId)
    {
        $response = $this->api->post("/nfc/cards/{$cardId}/dissociate");

        return $response->successful()
            ? back()->with('success', 'Association NFC supprimée.')
            : back()->with('error', $response->json('message') ?? 'Erreur.');
    }

    // ═══════════════════════════════════════════════════════════════
    // VISITES
    // ═══════════════════════════════════════════════════════════════

    /** Liste toutes les visites de l'utilisateur */
    public function visits()
{
    $response = $this->api->get('/visits');
    $visits = [];
    if ($response->successful()) {
        $data = $response->json();
        // Extraction si encapsulé dans 'data'
        if (is_array($data) && isset($data['data'])) {
            $visits = $data['data'];
        } else {
            $visits = $data;
        }

        // Normalisation de chaque visite
        foreach ($visits as &$visit) {
            // Décoder visitor_social_links si c'est une chaîne JSON
            if (isset($visit['visitor_social_links']) && is_string($visit['visitor_social_links'])) {
                $decoded = json_decode($visit['visitor_social_links'], true);
                $visit['visitor_social_links'] = is_array($decoded) ? $decoded : [];
            } elseif (!isset($visit['visitor_social_links'])) {
                $visit['visitor_social_links'] = [];
            }

            // S'assurer que c'est bien un tableau
            if (!is_array($visit['visitor_social_links'])) {
                $visit['visitor_social_links'] = [];
            }

            // Autres normalisations éventuelles
            $visit['id'] = $visit['id'] ?? null;
            $visit['source'] = $visit['source'] ?? 'direct';
            $visit['visited_at'] = $visit['visited_at'] ?? null;
            $visit['visitor_name'] = $visit['visitor_name'] ?? '';
            $visit['visitor_email'] = $visit['visitor_email'] ?? '';
            $visit['visitor_phone'] = $visit['visitor_phone'] ?? '';
            $visit['visitor_address'] = $visit['visitor_address'] ?? '';
        }
    }

    return view('user.visits.index', compact('visits'));
}

    /** Met à jour les informations d'un visiteur sur une visite */
    public function updateVisit(Request $request, $visitId)
    {
        $request->validate([
            'visitor_name'    => 'nullable|string|max:100',
            'visitor_email'   => 'nullable|email',
            'visitor_phone'   => 'nullable|string|max:30',
            'visitor_address' => 'nullable|string|max:255',
        ]);

        $response = $this->api->put("/visits/{$visitId}", $request->all());

        return $response->successful()
            ? back()->with('success', 'Informations du visiteur enregistrées.')
            : back()->with('error', $response->json('message') ?? 'Erreur.');
    }

    /** Exporte les informations d'une visite */
    public function exportVisit($visitId)
    {
        $response = $this->api->get('/visits/export', ['visit_id' => $visitId]);

        if ($response->successful()) {
            // Retourne le fichier directement si c'est un téléchargement
            return response($response->body(), 200)
                ->header('Content-Type', $response->header('Content-Type') ?? 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="visiteur-'.$visitId.'.vcf"');
        }

        return back()->with('error', 'Impossible d\'exporter.');
    }

    // ═══════════════════════════════════════════════════════════════
    // HISTORIQUE
    // ═══════════════════════════════════════════════════════════════

    /** Historique des portfolios vus par l'utilisateur lui-même */
    public function history()
    {
        $response = $this->api->get('/me/nfc-reads');
        $history = $response->successful() ? $response->json() : [];

        return view('user.history.index', compact('history'));
    }

    // ═══════════════════════════════════════════════════════════════
    // MODÈLES DE CARTE (Card Design)
    // ═══════════════════════════════════════════════════════════════

    /** Affiche les modèles prédéfinis et les designs de l'utilisateur */
    public function cardModels()
    {
        // Modèles système prédéfinis
        $templatesResponse = $this->api->get('/card-templates');
        $templates = $templatesResponse->successful() ? $templatesResponse->json() : [];

        // Designs personnalisés de l'utilisateur
        $designsResponse = $this->api->get('/my-card-designs');
        $myDesigns = $designsResponse->successful() ? $designsResponse->json() : [];

        return view('user.card-models.index', compact('templates', 'myDesigns'));
    }

    /** Affiche l'éditeur d'un modèle de carte */
    public function editCardModel($templateId)
    {
        $response = $this->api->get("/card-templates/{$templateId}");

        if (!$response->successful()) {
            return redirect()->route('user.card-models')
                             ->with('error', 'Modèle introuvable.');
        }

        $template = $response->json();
        $user = Session::get('user');

        return view('user.card-models.editor', compact('template', 'user'));
    }

    /** Sauvegarde un design de carte personnalisé */
    public function storeCardDesign(Request $request)
    {
        $request->validate([
            'template_id'   => 'required|integer',
            'name'          => 'required|string|max:100',
            'custom_data'   => 'required|array',
            'width_mm'      => 'nullable|numeric',
            'height_mm'     => 'nullable|numeric',
        ]);

        $response = $this->api->post('/card-designs', $request->all());

        return $response->successful()
            ? redirect()->route('user.card-models')
                        ->with('success', 'Design de carte sauvegardé.')
            : back()->with('error', $response->json('message') ?? 'Erreur lors de la sauvegarde.');
    }

    /** Exporte un design en PDF */
    public function exportCardDesign($designId)
    {
        $response = $this->api->post("/card-designs/{$designId}/export-pdf");

        if ($response->successful()) {
            $data = $response->json();
            return view('user.card-models.export', compact('data', 'designId'));
        }

        return back()->with('error', 'Impossible d\'exporter en PDF.');
    }

    /** Supprime un design de carte */
    public function destroyCardDesign($designId)
    {
        $response = $this->api->delete("/card-designs/{$designId}");

        return $response->successful()
            ? back()->with('success', 'Design supprimé.')
            : back()->with('error', $response->json('message') ?? 'Erreur.');
    }

    // ═══════════════════════════════════════════════════════════════
    // CONFIGURATION UTILISATEUR
    // ═══════════════════════════════════════════════════════════════

    /** Affiche la page de configuration personnelle */
    public function config()
    {
        $user = Session::get('user');
        return view('user.config.index', compact('user'));
    }

    /** Met à jour le profil utilisateur */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email',
            'phone'    => 'nullable|string|max:30',
            'location' => 'nullable|string|max:255',
            'language' => 'nullable|in:fr,en',
        ]);

        $response = $this->api->put('/me', $request->all());

        if ($response->successful()) {
            // Met à jour les infos utilisateur en session
            $updatedUser = $response->json('user') ?? array_merge(Session::get('user', []), $request->all());
            Session::put('user', $updatedUser);
            return back()->with('success', 'Profil mis à jour avec succès.');
        }

        return back()->with('error', $response->json('message') ?? 'Erreur lors de la mise à jour.');
    }
}
