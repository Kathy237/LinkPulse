<?php
// app/Http/Controllers/API/SocialLinkController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SocialLinkResource;
use App\Models\Portfolio;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SocialLinkController extends Controller
{
    use AuthorizesRequests;

    /**
     * Ajouter un lien social à un portfolio.
     */
    public function store(Request $request, Portfolio $portfolio)
    {
        $this->authorize('update', $portfolio);

        $validated = $request->validate([
            'platform' => 'required|string|max:50',
            'url' => 'required|url|max:500',
            'sort_order' => 'nullable|integer',
        ]);

        $socialLink = $portfolio->socialLinks()->create($validated);

        return new SocialLinkResource($socialLink);
    }

    /**
     * Mettre à jour un lien social.
     */
    public function update(Request $request, SocialLink $socialLink)
    {
        $this->authorize('update', $socialLink->portfolio);

        $validated = $request->validate([
            'platform' => 'sometimes|string|max:50',
            'url' => 'sometimes|url|max:500',
            'sort_order' => 'nullable|integer',
        ]);

        $socialLink->update($validated);

        return new SocialLinkResource($socialLink);
    }

    /**
     * Supprimer un lien social.
     */
    public function destroy(SocialLink $socialLink)
    {
        $this->authorize('update', $socialLink->portfolio);

        $socialLink->delete();

        return response()->json(['message' => 'Lien social supprimé avec succès.']);
    }
}