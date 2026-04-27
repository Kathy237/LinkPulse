<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserCardDesign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class CardDesignController extends Controller
{
    public function index(Request $request)
    {
        $designs = $request->user()->userCardDesigns()->with('template')->get();
        return response()->json($designs);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'template_id' => 'required|exists:card_templates,id',
            'card_data' => 'required|array',
            'dimensions' => 'nullable|string|max:50',
        ]);
        $design = $request->user()->userCardDesigns()->create($validated);
        return response()->json($design, 201);
    }

    public function update(Request $request, UserCardDesign $design)
    {
        if ($design->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        $design->update($request->only(['card_data', 'dimensions']));
        return response()->json($design);
    }

    public function destroy(UserCardDesign $design, Request $request)
    {
        if ($design->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        $design->delete();
        return response()->json(['message' => 'Design supprimé']);
    }

    public function exportPdf(UserCardDesign $design, Request $request)
    {
        if ($design->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }
        $pdf = Pdf::loadView('pdf.card', ['design' => $design]);
        return $pdf->download('carte_nfc.pdf');
    }
}