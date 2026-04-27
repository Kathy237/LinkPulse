<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Client\Response;

/**
 * ApiService — Point d'entrée unique pour toutes les communications
 * avec le backend Laravel (port 8000).
 *
 * Utilisation :
 *   $this->api->get('/portfolios')
 *   $this->api->post('/portfolios', $data)
 *   $this->api->put('/portfolios/1', $data)
 *   $this->api->delete('/portfolios/1')
 *   $this->api->postMultipart('/portfolios', $data, $files)
 */
class ApiService
{
    /** URL de base de l'API, définie dans config/app.php → API_BASE_URL */
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('app.api_base_url', 'http://127.0.0.1:8000/api');
    }

    // ─────────────────────────────────────────────
    // Récupère le token JWT depuis la session
    // ─────────────────────────────────────────────
    protected function token(?string $token = null): ?string
    {
        return $token ?? Session::get('api_token');
    }

    // ─────────────────────────────────────────────
    // Crée un client HTTP de base avec le token
    // ─────────────────────────────────────────────
    protected function client(?string $token = null)
    {
        return Http::withToken($this->token($token))
                   ->acceptJson()
                   ->timeout(30);
    }

    /**
     * GET — Récupérer des données
     */
    public function get(string $endpoint, array $query = [], ?string $token = null): Response
    {
        return $this->client($token)->get($this->baseUrl . $endpoint, $query);
    }

    /**
     * POST — Créer une ressource (JSON)
     */
    public function post(string $endpoint, array $data = [], ?string $token = null): Response
    {
        return $this->client($token)->post($this->baseUrl . $endpoint, $data);
    }

    /**
     * PUT — Mettre à jour une ressource
     */
    public function put(string $endpoint, array $data = [], ?string $token = null): Response
    {
        return $this->client($token)->put($this->baseUrl . $endpoint, $data);
    }

    /**
     * DELETE — Supprimer une ressource
     */
    public function delete(string $endpoint, ?string $token = null): Response
    {
        return $this->client($token)->delete($this->baseUrl . $endpoint);
    }

    /**
     * POST Multipart — Envoi de fichiers (photos, CV, etc.)
     * $files = ['photo' => $request->file('photo'), ...]
     */
    public function postMultipart(string $endpoint, array $data = [], array $files = [], ?string $token = null): Response
    {
        $http = Http::withToken($this->token($token))->timeout(60);

        // Attacher chaque fichier uploadé
        foreach ($files as $key => $file) {
            if ($file) {
                $http = $http->attach(
                    $key,
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                );
            }
        }

        return $http->post($this->baseUrl . $endpoint, $data);
    }

    /**
     * POST Multipart avec méthode simulée (PUT via _method=PUT)
     * Utilisé pour mettre à jour une ressource avec fichiers.
     */
    public function putMultipart(string $endpoint, array $data = [], array $files = [], ?string $token = null): Response
    {
        $data['_method'] = 'PUT';
        return $this->postMultipart($endpoint, $data, $files, $token);
    }
}
