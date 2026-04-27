<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Afficher les notifications de l'utilisateur.
     */
    public function index(Request $request)
    {
        // Retourne les notifications via la relation native de Laravel
        return response()->json($request->user()->notifications);
    }

    /**
     * Marquer une notification comme lue.
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['message' => 'Notification marquée comme lue.']);
    }
}