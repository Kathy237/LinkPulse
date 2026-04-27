<?php
// app/Http/Controllers/API/NfcController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\NfcCardResource;
use App\Http\Resources\NfcReadResource;
use App\Http\Resources\PortfolioResource;
use App\Models\NfcCard;
use App\Models\NfcRead;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NfcController extends Controller
{
    use AuthorizesRequests;
    /**
     * Associer une carte NFC (UID) à un portfolio.
     * Utilisé lors de l'écriture sur une carte.
     */
    public function storeCard(Request $request)
    {
        $request->validate([
            'uid' => 'required|string|unique:nfc_cards,uid',
            'portfolio_id' => 'required|exists:portfolios,id',
        ]);

        $portfolio = Portfolio::findOrFail($request->portfolio_id);
     // $this->authorize('update', $portfolio);

        $card = NfcCard::create([
            'uid' => $request->uid,
            'portfolio_id' => $request->portfolio_id,
            'created_by' => $request->user()->id,
        ]);

        return new NfcCardResource($card);
    }

    /**
     * Dissocier une carte NFC.
     */
    public function destroyCard(NfcCard $nfcCard)
    {
     // $this->authorize('update', $nfcCard->portfolio);
        $nfcCard->delete();
        return response()->json(['message' => 'Carte dissociée avec succès.']);
    }

    /**
     * Lire une carte NFC (par UID) – endpoint public.
     * Retourne les données du portfolio associé et enregistre la lecture si l'utilisateur est connecté.
     */
    public function readCard($uid, Request $request)
    {
        $card = NfcCard::with('portfolio')->where('uid', $uid)->first();

        if (!$card) {
            return response()->json(['message' => 'carte vide.'], 404);
        }

        $portfolio = $card->portfolio;

         // Enregistrement de la lecture
        $readData = [
            'card_id' => $card->id,
            'read_at' => now(),
        ];

        if ($request->user()) {
            $readData['user_id'] = $request->user()->id;
        } else {
            // Récupérer device_id depuis le header ou générer un ID temporaire
            $deviceId = $request->header('X-Device-ID');
            if (!$deviceId) {
                $deviceId = 'anonymous_' . md5($request->ip() . $request->userAgent());
            }
            $readData['visitor_device_id'] = $deviceId;
        }

        NfcRead::create($readData);

        return new PortfolioResource($portfolio);
    }

    public function myReads(Request $request)
    {
        $reads = $request->user()->nfcReads()->with('card.portfolio')->orderBy('read_at', 'desc')->get();
        return NfcReadResource::collection($reads);
    }

    public function myCards(Request $request)
    {
        $cards = NfcCard::whereHas('portfolio', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->with('portfolio')->get();
        return NfcCardResource::collection($cards);
    }

    public function dissociateCard(NfcCard $nfcCard)
    {
        $this->authorize('update', $nfcCard->portfolio);
        $nfcCard->update(['portfolio_id' => null]);
        return response()->json(['message' => 'Carte dissociée du portfolio.']);
    }
}