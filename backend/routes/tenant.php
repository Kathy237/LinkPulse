<?php
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('api')->group(function () {
    Route::get('/tenant-info', function () {
        return response()->json([
            'message' => 'Ceci est votre application multi-tenant isolée.',
            'tenant_id' => tenant('id')
        ]);
    });
});
