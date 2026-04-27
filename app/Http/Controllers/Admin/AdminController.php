<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * AdminController — Toutes les actions de l'espace administrateur.
 */
class AdminController extends Controller
{
    protected ApiService $api;

    public function __construct(ApiService $api)
    {
        $this->api = $api;
    }

    // ═══════════════════════════════════════════════════════════════
    // TABLEAU DE BORD
    // ═══════════════════════════════════════════════════════════════

    public function dashboard()
    {
        $response = $this->api->get('/admin/dashboard');
        $stats = $response->successful()
            ? $response->json()
            : ['pending' => 0, 'active' => 0, 'rejected' => 0, 'reports' => 0];

        return view('admin.dashboard', compact('stats'));
    }

    // ═══════════════════════════════════════════════════════════════
    // GESTION DES UTILISATEURS
    // ═══════════════════════════════════════════════════════════════

    /**
     * Liste des utilisateurs filtrés par statut.
     */
    public function users(Request $request)
    {
        $status = $request->query('status', 'pending');
        $response = $this->api->get('/admin/users', ['status' => $status]);
        
        $users = [];
        if ($response->successful()) {
            $data = $response->json();
            // Si la réponse est encapsulée dans une clé 'data' (ressource Laravel)
            if (is_array($data) && isset($data['data'])) {
                $users = $data['data'];
            } else {
                $users = $data;
            }
            
            // Normalisation : garantir la clé 'id'
            foreach ($users as &$user) {
                if (!isset($user['id']) && isset($user['user_id'])) {
                    $user['id'] = $user['user_id'];
                }
            }
        }
        
        return view('admin.users.index', compact('users', 'status'));
    }

    public function validateUser($id)
    {
        $response = $this->api->put("/admin/users/{$id}/validate");
        return $response->successful()
            ? back()->with('success', 'Utilisateur approuvé. Un email de confirmation lui a été envoyé.')
            : back()->with('error', $response->json('message') ?? 'Erreur lors de l\'approbation.');
    }

    public function rejectUser($id)
    {
        $response = $this->api->put("/admin/users/{$id}/reject");
        return $response->successful()
            ? back()->with('success', 'Utilisateur rejeté.')
            : back()->with('error', $response->json('message') ?? 'Erreur lors du rejet.');
    }

    public function blockUser($id)
    {
        $response = $this->api->put("/admin/users/{$id}/block");
        return $response->successful()
            ? back()->with('success', 'Utilisateur bloqué pour 30 jours.')
            : back()->with('error', $response->json('message') ?? 'Erreur lors du blocage.');
    }

    public function restoreUser($id)
    {
        $response = $this->api->put("/admin/users/{$id}/restore");
        return $response->successful()
            ? back()->with('success', 'Utilisateur restauré avec succès.')
            : back()->with('error', $response->json('message') ?? 'Erreur lors de la restauration.');
    }

    public function destroyUser($id)
    {
        $response = $this->api->delete("/admin/users/{$id}");
        return $response->successful()
            ? back()->with('success', 'Utilisateur supprimé définitivement.')
            : back()->with('error', $response->json('message') ?? 'Erreur lors de la suppression.');
    }

    // ═══════════════════════════════════════════════════════════════
    // ALERTES / SIGNALEMENTS
    // ═══════════════════════════════════════════════════════════════

    public function reports(Request $request)
    {
        $status = $request->query('status', 'pending');
        $response = $this->api->get('/admin/reports', ['status' => $status]);
        $reports = $response->successful() ? $response->json() : [];
        return view('admin.alerts.index', compact('reports', 'status'));
    }

    public function resolveReport($id, Request $request)
    {
        $response = $this->api->put("/admin/reports/{$id}/resolve", $request->only('admin_notes'));
        return $response->successful()
            ? back()->with('success', 'Signalement marqué comme résolu.')
            : back()->with('error', $response->json('message') ?? 'Erreur.');
    }

    // ═══════════════════════════════════════════════════════════════
    // FORFAITS (QUOTAS)
    // ═══════════════════════════════════════════════════════════════

    public function getQuota($userId)
    {
        $response = $this->api->get("/admin/users/{$userId}/quota");
        $quota = $response->successful() ? $response->json() : null;

        $userResponse = $this->api->get('/admin/users', ['status' => 'active']);
        $allUsers = $userResponse->successful() ? $userResponse->json() : [];
        $targetUser = collect($allUsers)->firstWhere('id', (int) $userId);

        return view('admin.quotas.edit', compact('quota', 'userId', 'targetUser'));
    }

    public function setQuota(Request $request, $userId)
    {
        $request->validate([
            'max_portfolios'          => 'nullable|integer|min:1',
            'max_links_per_portfolio' => 'nullable|integer|min:1',
            'max_cards'               => 'nullable|integer|min:1',
            'valid_until'             => 'nullable|date',
        ]);

        $response = $this->api->post("/admin/users/{$userId}/quota", $request->all());

        return $response->successful()
            ? redirect()->route('admin.users', ['status' => 'active'])->with('success', 'Forfait mis à jour avec succès.')
            : back()->with('error', $response->json('message') ?? 'Erreur lors de la mise à jour du forfait.');
    }

    // ═══════════════════════════════════════════════════════════════
    // CONFIGURATION
    // ═══════════════════════════════════════════════════════════════

    public function configForm()
    {
        $response = $this->api->get('/settings');
        $settings = $response->successful() ? $response->json() : [];

        $adminsResponse = $this->api->get('/admin/users', ['status' => 'active']);
        $allUsers = $adminsResponse->successful() ? $adminsResponse->json() : [];
        $admins = array_filter($allUsers, fn($u) => ($u['role'] ?? '') === 'admin');

        return view('admin.config.index', compact('settings', 'admins'));
    }

    public function updateConfig(Request $request)
    {
        $request->validate([
            'app_name'         => 'sometimes|string|max:100',
            'default_language' => 'sometimes|in:fr,en',
        ]);

        $response = $this->api->post('/settings', $request->only('app_name', 'default_language'));

        if ($response->successful()) {
            if ($request->has('app_name')) {
                Session::put('app_name', $request->app_name);
            }
            return back()->with('success', 'Configuration mise à jour avec succès.');
        }

        return back()->with('error', $response->json('message') ?? 'Erreur lors de la mise à jour.');
    }

    public function updateOwnPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        $response = $this->api->post('/admin/update-password',
            $request->only('current_password', 'password', 'password_confirmation'));

        return $response->successful()
            ? back()->with('success', 'Mot de passe modifié avec succès.')
            : back()->with('error', $response->json('message') ?? 'Mot de passe actuel incorrect.');
    }

    public function addAdmin(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email',
            'password' => 'required|min:8',
        ]);

        $response = $this->api->post('/settings', array_merge(
            $request->only('name', 'email', 'password'),
            ['action' => 'add_admin']
        ));

        return $response->successful()
            ? back()->with('success', 'Nouvel administrateur ajouté.')
            : back()->with('error', $response->json('message') ?? 'Erreur lors de l\'ajout.');
    }
}