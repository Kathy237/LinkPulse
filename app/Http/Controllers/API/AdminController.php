<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Report;
use App\Models\User;
use App\Models\UserQuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserActivated;
use App\Mail\UserRejected;

class AdminController extends Controller
{
    // Liste des utilisateurs avec filtre
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = User::query();
        if ($status && in_array($status, ['pending', 'active', 'rejected'])) {
            $query->where('status', $status);
        }
        $users = $query->orderBy('created_at', 'desc')->get();
        return UserResource::collection($users);
    }

    public function validateUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->status !== 'pending') {
            return response()->json(['message' => 'Utilisateur non en attente.'], 400);
        }
        $user->status = 'active';
        $user->save();
        Mail::to($user->email)->send(new UserActivated($user));
        return response()->json(['message' => 'Utilisateur activé.', 'user' => new UserResource($user)]);
    }

    public function rejectUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->status !== 'pending') {
            return response()->json(['message' => 'Utilisateur non en attente.'], 400);
        }
        $user->status = 'rejected';
        $user->save();
        Mail::to($user->email)->send(new UserRejected($user));
        return response()->json(['message' => 'Utilisateur rejeté.', 'user' => new UserResource($user)]);
    }

    public function blockUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->isAdmin()) {
            return response()->json(['message' => 'Impossible de bloquer un admin.'], 403);
        }
        $user->status = 'rejected';
        $user->blocked_at = now();
        $user->restorable_until = now()->addDays(30);
        $user->save();
        $user->portfolios()->update(['hidden_at' => now()]);
        return response()->json(['message' => 'Utilisateur bloqué 30 jours.']);
    }

    public function restoreUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->status !== 'rejected') {
            return response()->json(['message' => 'Utilisateur non bloqué.'], 400);
        }
        $user->status = 'active';
        $user->blocked_at = null;
        $user->restorable_until = null;
        $user->save();
        $user->portfolios()->update(['hidden_at' => null]);
        return response()->json(['message' => 'Utilisateur restauré.']);
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->isAdmin()) {
            return response()->json(['message' => 'Impossible de supprimer un admin.'], 403);
        }
        $user->delete();
        return response()->json(['message' => 'Utilisateur supprimé.']);
    }

    // Gestion des signalements
    public function getReports(Request $request)
    {
        $status = $request->query('status', 'pending');
        $reports = Report::with(['reportedPortfolio', 'reportedUser'])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($reports);
    }

    public function resolveReport($id, Request $request)
    {
        $report = Report::findOrFail($id);
        $report->status = 'resolved';
        $report->admin_notes = $request->input('admin_notes');
        $report->resolved_at = now();
        $report->save();
        return response()->json(['message' => 'Signalement résolu.']);
    }

    // Quotas
    public function setQuota(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $validated = $request->validate([
            'max_portfolios' => 'nullable|integer|min:0',
            'max_links_per_portfolio' => 'nullable|integer|min:0',
            'max_cards' => 'nullable|integer|min:0',
            'valid_until' => 'nullable|date',
        ]);
        $quota = UserQuota::updateOrCreate(['user_id' => $user->id], $validated);
        return response()->json(['message' => 'Quota mis à jour', 'quota' => $quota]);
    }

    public function getQuota($userId)
    {
        $user = User::findOrFail($userId);
        $quota = $user->quota ?? (object)['max_portfolios' => null, 'max_links_per_portfolio' => null, 'max_cards' => null];
        return response()->json($quota);
    }

    // Dashboard admin
    public function dashboard()
    {
        return response()->json([
            'pending' => User::where('status', 'pending')->count(),
            'active' => User::where('status', 'active')->count(),
            'rejected' => User::where('status', 'rejected')->count(),
            'reports' => Report::where('status', 'pending')->count(),
        ]);
    }
}