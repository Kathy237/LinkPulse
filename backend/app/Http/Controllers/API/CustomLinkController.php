<?php
// app/Http/Controllers/API/CustomLinkController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomLinkResource;
use App\Models\Portfolio;
use App\Models\CustomLink;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CustomLinkController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Portfolio $portfolio)
    {
        $this->authorize('update', $portfolio);
        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'url' => 'required|url|max:500',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
        ]);
        $link = $portfolio->customLinks()->create($validated);

        
        return new CustomLinkResource($link);
    }

    public function update(Request $request, CustomLink $customLink)
    {
        $this->authorize('update', $customLink->portfolio);
        $validated = $request->validate([
            'label' => 'sometimes|string|max:100',
            'url' => 'sometimes|url|max:500',
            'icon' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
        ]);
        $customLink->update($validated);
        return new CustomLinkResource($customLink);
    }

    public function destroy(CustomLink $customLink)
    {
        $this->authorize('update', $customLink->portfolio);
        $customLink->delete();
        return response()->json(['message' => 'Lien personnalisé supprimé.']);
    }
}