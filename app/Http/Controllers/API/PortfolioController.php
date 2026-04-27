<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PortfolioResource;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\Facades\Image;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class PortfolioController extends Controller
{
    use AuthorizesRequests;

    /**
     * Afficher la liste des portfolios de l'utilisateur connecté.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $portfolios = $request->user()->portfolios()
            ->with(['projects', 'socialLinks', 'customLinks'])
            ->get();

        return PortfolioResource::collection($portfolios);
    }

    /**
     * Créer un nouveau portfolio.
     *
     * @param Request $request
     * @return PortfolioResource
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'display_name'           => 'required|string|max:150',
            'bio'                    => 'nullable|string',
            'profile_photo'          => 'nullable|image|max:2048',
            'skills'                 => 'nullable|string',
            'cv_file'                => 'nullable|file|mimes:pdf|max:10240',
            'vcard_email'            => 'nullable|email',
            'vcard_phone'            => 'nullable|string|max:50',
            'vcard_address'          => 'nullable|string',
            'portfolio_external_url' => 'nullable|url|max:500',
            'theme'                  => 'nullable|in:light,dark',
            'is_active'              => 'boolean',
        ]);

        // Gestion des quota 
        $quota = $request->user()->quota;
            if ($quota && $quota->max_portfolios !== null && $request->user()->portfolios()->count() >= $quota->max_portfolios) {
            return response()->json(['message' => 'Limite de portfolios atteinte.'], 403);
        }

        // Gestion de la photo de profil (nouvelle API Intervention Image)
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $fullPath = Storage::disk('public')->path($path);
            
            // Redimensionnement avec ImageManager
            $manager = new ImageManager(new Driver());
            $image = $manager->read($fullPath);
            $image->cover(400, 400);
            $image->save();
            
            $validated['profile_photo'] = $path;
        }

        // Gestion du CV
        if ($request->hasFile('cv_file')) {
            $validated['cv_file'] = $request->file('cv_file')->store('cvs', 'public');
        }

        // Création du portfolio lié à l'utilisateur authentifié
        $portfolio = $request->user()->portfolios()->create($validated);

        return new PortfolioResource($portfolio->load(['projects', 'socialLinks', 'customLinks']));
    }

    /**
     * Afficher un portfolio spécifique (autorisation requise).
     *
     * @param Portfolio $portfolio
     * @return PortfolioResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Portfolio $portfolio)
    {
        $this->authorize('view', $portfolio);

        return new PortfolioResource($portfolio->load(['projects', 'socialLinks', 'customLinks']));
    }

    /**
     * Mettre à jour un portfolio.
     *
     * @param Request $request
     * @param Portfolio $portfolio
     * @return PortfolioResource
     */
    public function update(Request $request, Portfolio $portfolio)
    {
        $this->authorize('update', $portfolio);

        $validated = $request->validate([
            'display_name'           => 'sometimes|string|max:150',
            'bio'                    => 'nullable|string',
            'profile_photo'          => 'nullable|image|max:2048',
            'skills'                 => 'nullable|string',
            'cv_file'                => 'nullable|file|mimes:pdf|max:10240',
            'vcard_email'            => 'nullable|email',
            'vcard_phone'            => 'nullable|string|max:50',
            'vcard_address'          => 'nullable|string',
            'portfolio_external_url' => 'nullable|url|max:500',
            'theme'                  => 'nullable|in:light,dark',
            'is_active'              => 'sometimes|boolean',
        ]);

        // Conversion manuelle de is_active si présent
        if ($request->has('is_active')) {
            $validated['is_active'] = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);
        }

        // Mise à jour de la photo de profil
        if ($request->hasFile('profile_photo')) {
            // Supprimer l'ancienne photo
            if ($portfolio->profile_photo && Storage::disk('public')->exists($portfolio->profile_photo)) {
                Storage::disk('public')->delete($portfolio->profile_photo);
            }

            $file = $request->file('profile_photo');
            $path = $file->store('profile_photos', 'public');
            $fullPath = Storage::disk('public')->path($path);

            // Redimensionnement
            try{
                $manager = new ImageManager(new Driver());
                $image = $manager->read($fullPath);
                $image->cover(400, 400);
                $image->save();

            } catch (\Exception $e) {
            // Si l'image ne peut pas être traitée, on supprime le fichier et on renvoie une erreur   
                Storage::disk('public')->delete($path);
                return response()->json(['message' => 'Erreur lors du traitement de l\'image : ' . $e->getMessage()], 500);
            }
            $validated['profile_photo'] = $path;
        }

        // Mise à jour du CV
        if ($request->hasFile('cv_file')) {
            if ($portfolio->cv_file && Storage::disk('public')->exists($portfolio->cv_file)) {
                Storage::disk('public')->delete($portfolio->cv_file);
            }
            $validated['cv_file'] = $request->file('cv_file')->store('cvs', 'public');
        }

        $portfolio->update($validated);

        return new PortfolioResource($portfolio->load(['projects', 'socialLinks', 'customLinks']));
    }

    /**
     * Supprimer un portfolio (ou demander dissociation si lié à une carte NFC).
     *
     * @param Portfolio $portfolio
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Portfolio $portfolio)
    {
        $this->authorize('delete', $portfolio);

        // Vérifier si le portfolio est associé à au moins une carte NFC
        if ($portfolio->nfcCards()->exists()) {
            return response()->json([
                'requires_dissociation' => true,
                'message' => 'Ce portfolio est associé à une carte NFC. Voulez-vous dissocier avant suppression ?',
                'portfolio_id' => $portfolio->id
            ], 200);
        }

        // Suppression définitive (adaptez si vous utilisez SoftDeletes)
        $portfolio->delete();

        return response()->json(['message' => 'Portfolio supprimé avec succès.'], 200);
    }

    /**
     * Générer un QR code pour l'URL publique du portfolio.
     *
     * @param Portfolio $portfolio
     * @return \Illuminate\Http\Response
     */
    public function qrcode(Portfolio $portfolio)
{
    $this->authorize('view', $portfolio);
    $url = $portfolio->public_url . '?ref=qr';
    
    $result = Builder::create()
        ->writer(new PngWriter())
        ->data($url)
        ->size(300)
        ->build();
    
    return response($result->getString(), 200)
        ->header('Content-Type', 'image/png')
        ->header('Content-Disposition', 'inline; filename="qrcode.png"');
}
}