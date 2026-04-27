<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\User\UserController;

/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthController::class, 'home'])->name('home');

// Authentification
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register'])->name('register.post');
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

// Mot de passe oublié
Route::get('/forgot-password',  [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetCode'])->name('password.email');
Route::get('/reset-password',   [AuthController::class, 'showResetPassword'])->name('password.reset.form');
Route::post('/reset-password',  [AuthController::class, 'resetPassword'])->name('password.reset');

// Pages publiques
Route::get('/p/{slug}',           [PublicController::class, 'showPortfolio'])->name('public.portfolio');
Route::get('/nfc/{uid}',          [PublicController::class, 'readNfc'])->name('nfc.read');
Route::get('/cv/{portfolioId}',   [PublicController::class, 'downloadCv'])->name('public.cv');
Route::get('/vcard/{portfolioId}',[PublicController::class, 'downloadVcard'])->name('public.vcard');

// Espace visiteur
Route::prefix('visiteur')->name('visitor.')->group(function () {
    Route::get('/',         [PublicController::class, 'visitorHome'])->name('home');
    Route::get('/signaler', [PublicController::class, 'showReport'])->name('report');
    Route::post('/signaler',[PublicController::class, 'submitReport'])->name('report.post');
});

/*
|--------------------------------------------------------------------------
| Routes ADMINISTRATEUR
|--------------------------------------------------------------------------
*/
Route::middleware(['CheckAdmin'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {
    Route::get('/',          [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard']);

    Route::get('/utilisateurs',                      [AdminController::class, 'users'])->name('users');
    Route::post('/utilisateurs/{id}/approuver',      [AdminController::class, 'validateUser'])->name('users.validate');
    Route::post('/utilisateurs/{id}/rejeter',        [AdminController::class, 'rejectUser'])->name('users.reject');
    Route::post('/utilisateurs/{id}/bloquer',        [AdminController::class, 'blockUser'])->name('users.block');
    Route::post('/utilisateurs/{id}/restaurer',      [AdminController::class, 'restoreUser'])->name('users.restore');
    Route::delete('/utilisateurs/{id}',              [AdminController::class, 'destroyUser'])->name('users.destroy');

    Route::get('/alertes',                           [AdminController::class, 'reports'])->name('reports');
    Route::post('/alertes/{id}/resoudre',            [AdminController::class, 'resolveReport'])->name('reports.resolve');

    Route::get('/forfaits/{userId}',                 [AdminController::class, 'getQuota'])->name('quotas.edit');
    Route::post('/forfaits/{userId}',                [AdminController::class, 'setQuota'])->name('quotas.update');

    Route::get('/configuration',                     [AdminController::class, 'configForm'])->name('config');
    Route::post('/configuration',                    [AdminController::class, 'updateConfig'])->name('config.update');
    Route::post('/configuration/mot-de-passe',       [AdminController::class, 'updateOwnPassword'])->name('config.password');
    Route::post('/configuration/ajouter-admin',      [AdminController::class, 'addAdmin'])->name('config.add_admin');
});

/*
|--------------------------------------------------------------------------
| Routes UTILISATEUR (connecté)
|--------------------------------------------------------------------------
*/
Route::middleware(['CheckAuth'])
     ->prefix('espace')
     ->name('user.')
     ->group(function () {
    Route::get('/',           [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard',  [UserController::class, 'dashboard']);

    Route::get('/portfolios',                        [UserController::class, 'portfolios'])->name('portfolios');
    Route::get('/portfolios/nouveau',                [UserController::class, 'createPortfolio'])->name('portfolios.create');
    Route::post('/portfolios',                       [UserController::class, 'storePortfolio'])->name('portfolios.store');
    Route::get('/portfolios/{id}/modifier',          [UserController::class, 'editPortfolio'])->name('portfolios.edit');
    Route::post('/portfolios/{id}',                  [UserController::class, 'updatePortfolio'])->name('portfolios.update');
    Route::delete('/portfolios/{id}',                [UserController::class, 'destroyPortfolio'])->name('portfolios.destroy');
    Route::get('/portfolios/{id}/qrcode',            [UserController::class, 'qrcode'])->name('portfolios.qrcode');
    Route::post('/portfolios/{id}/dissocier-nfc',    [UserController::class, 'dissociateNfc'])->name('portfolios.dissociate');

    Route::post('/portfolios/{portfolioId}/projets', [UserController::class, 'storeProject'])->name('projects.store');
    Route::delete('/projets/{projectId}',            [UserController::class, 'destroyProject'])->name('projects.destroy');

    Route::post('/portfolios/{portfolioId}/liens-sociaux', [UserController::class, 'storeSocialLink'])->name('social-links.store');
    Route::delete('/liens-sociaux/{linkId}',               [UserController::class, 'destroySocialLink'])->name('social-links.destroy');

    Route::post('/portfolios/{portfolioId}/liens',   [UserController::class, 'storeCustomLink'])->name('custom-links.store');
    Route::delete('/liens/{linkId}',                 [UserController::class, 'destroyCustomLink'])->name('custom-links.destroy');

    Route::get('/nfc',                               [UserController::class, 'nfc'])->name('nfc');
    Route::post('/nfc/cartes',                       [UserController::class, 'storeNfcCard'])->name('nfc.store');
    Route::delete('/nfc/cartes/{cardId}',            [UserController::class, 'destroyNfcCard'])->name('nfc.destroy');
    Route::post('/nfc/cartes/{cardId}/dissocier',    [UserController::class, 'dissociateNfcCard'])->name('nfc.dissociate');

    Route::get('/visiteurs',                         [UserController::class, 'visits'])->name('visits');
    Route::post('/visiteurs/{visitId}',              [UserController::class, 'updateVisit'])->name('visits.update');
    Route::get('/visiteurs/{visitId}/exporter',      [UserController::class, 'exportVisit'])->name('visits.export');

    Route::get('/historique',                        [UserController::class, 'history'])->name('history');

    Route::get('/modeles-carte',                     [UserController::class, 'cardModels'])->name('card-models');
    Route::get('/modeles-carte/{templateId}/editer', [UserController::class, 'editCardModel'])->name('card-models.edit');
    Route::post('/mes-cartes',                       [UserController::class, 'storeCardDesign'])->name('card-designs.store');
    Route::get('/mes-cartes/{designId}/exporter',    [UserController::class, 'exportCardDesign'])->name('card-designs.export');
    Route::delete('/mes-cartes/{designId}',          [UserController::class, 'destroyCardDesign'])->name('card-designs.destroy');

    Route::get('/configuration',                     [UserController::class, 'config'])->name('config');
    Route::post('/configuration/profil',             [UserController::class, 'updateProfile'])->name('config.profile');
});