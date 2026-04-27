<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CardDesignController;
use App\Http\Controllers\API\CustomLinkController;
use App\Http\Controllers\API\NfcController;
use App\Http\Controllers\API\PortfolioController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\PublicController;
use App\Http\Controllers\API\SocialLinkController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\VisitController;
use App\Http\Controllers\API\StatsController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\CardTemplateController;

/*
|--------------------------------------------------------------------------
| Routes publiques (sans authentification)
|--------------------------------------------------------------------------
*/
Route::prefix('public')->group(function () {
    Route::post('report', [PublicController::class, 'report']);               // Signalement par visiteur
    Route::get('p/{slug}', [PublicController::class, 'showPortfolio'])->name('public.portfolio');
    Route::get('portfolio/{portfolio}/vcard', [PublicController::class, 'vcard']);
    Route::get('portfolio/{portfolio}/cv', [PublicController::class, 'downloadCv']);
    Route::get('visitor/history', [VisitController::class, 'visitorHistory']); // Historique anonyme
});

// Lecture publique d’une carte NFC
Route::get('nfc/read/{uid}', [NfcController::class, 'readCard']);

// Authentification
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

/*
|--------------------------------------------------------------------------
| Routes protégées par authentification (token Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Profil utilisateur
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::put('me', [AuthController::class, 'updateProfile']);

    // Statistiques & Dashboard
    Route::get('dashboard/statistics', [StatsController::class, 'dashboardStats']);
    Route::get('dashboard/notifications', [NotificationController::class, 'index']);
    Route::get('user/stats', [StatsController::class, 'dashboardStats']); // Legacy route
    Route::get('user/notifications', [NotificationController::class, 'index']); // Legacy route

    // Portfolios
    Route::get('portfolios/pending-nfc', function (Illuminate\Http\Request $request) {
        $request->merge(['unassociated' => true]);
        return app(PortfolioController::class)->index($request);
    });
    Route::get('portfolios', [PortfolioController::class, 'index']);
    Route::post('portfolios', [PortfolioController::class, 'store']);
    Route::get('portfolios/{portfolio}', [PortfolioController::class, 'show']);
    Route::post('portfolios/{portfolio}', [PortfolioController::class, 'update']);
    Route::delete('portfolios/{portfolio}', [PortfolioController::class, 'destroy']);
    Route::get('portfolios/{portfolio}/qrcode', [PortfolioController::class, 'qrcode']);
    Route::post('portfolios/{portfolio}/dissociate-nfc', [PortfolioController::class, 'dissociateNfc']);

    // Projets
    Route::post('portfolios/{portfolio}/projects', [ProjectController::class, 'store']);
    Route::post('projects/{project}', [ProjectController::class, 'update']);
    Route::delete('projects/{project}', [ProjectController::class, 'destroy']);

    // Liens sociaux
    Route::post('portfolios/{portfolio}/social-links', [SocialLinkController::class, 'store']);
    Route::put('social-links/{socialLink}', [SocialLinkController::class, 'update']);
    Route::delete('social-links/{socialLink}', [SocialLinkController::class, 'destroy']);

    // Liens personnalisés
    Route::post('portfolios/{portfolio}/custom-links', [CustomLinkController::class, 'store']);
    Route::put('custom-links/{customLink}', [CustomLinkController::class, 'update']);
    Route::delete('custom-links/{customLink}', [CustomLinkController::class, 'destroy']);

    // NFC
    Route::post('nfc/cards', [NfcController::class, 'storeCard']);
    Route::delete('nfc/cards/{nfcCard}', [NfcController::class, 'destroyCard']);
    Route::post('nfc/cards/{nfcCard}/dissociate', [NfcController::class, 'dissociateCard']);
    Route::get('me/nfc-reads', [NfcController::class, 'myReads']);
    Route::get('me/nfc-cards', [NfcController::class, 'myCards']);

    // Visites (pour le propriétaire)
    Route::get('visits', [VisitController::class, 'index']);
    Route::put('visits/{visit}', [VisitController::class, 'updateVisitorInfo']);
    Route::get('visits/export', [VisitController::class, 'export']);
    Route::get('portfolios/{portfolio}/visits', [VisitController::class, 'portfolioVisits']);

    // Modèles de carte (templates)
    Route::get('card-templates', [CardTemplateController::class, 'index']);
    Route::get('card-templates/{template}', [CardTemplateController::class, 'show']);

    // Designs de carte utilisateur
    Route::get('my-card-designs', [CardDesignController::class, 'index']);
    Route::post('card-designs', [CardDesignController::class, 'store']);
    Route::put('card-designs/{design}', [CardDesignController::class, 'update']);
    Route::delete('card-designs/{design}', [CardDesignController::class, 'destroy']);
    Route::post('card-designs/{design}/export-pdf', [CardDesignController::class, 'exportPdf']);

    // Fusion visiteur (enregistrement depuis historique)
    Route::post('visitor/merge', [VisitController::class, 'mergeVisitorHistory']);

    // Routes Admin (avec middleware spécifique)
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('users', [AdminController::class, 'index']);
        Route::put('users/{id}/validate', [AdminController::class, 'validateUser']);
        Route::put('users/{id}/reject', [AdminController::class, 'rejectUser']);
        Route::put('users/{id}/block', [AdminController::class, 'blockUser']);
        Route::put('users/{id}/restore', [AdminController::class, 'restoreUser']);
        Route::delete('users/{id}', [AdminController::class, 'destroyUser']);

        Route::get('reports', [AdminController::class, 'getReports']);
        Route::put('reports/{id}/resolve', [AdminController::class, 'resolveReport']);

        Route::post('users/{id}/quota', [AdminController::class, 'setQuota']);
        Route::get('users/{id}/quota', [AdminController::class, 'getQuota']);

        Route::get('dashboard', [AdminController::class, 'dashboard']);
    });
});