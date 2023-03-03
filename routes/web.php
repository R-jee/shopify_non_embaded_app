<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppProxyController;
use App\Http\Controllers\WebhookJobsController;
use App\Http\Controllers\ConfigurationController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::get('clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    return "Cache is cleared";
});
Route::get('clear-cache', function () {
    Artisan::call('optimize:clear');
    return "Cache is Optimized!";
});
Route::get('fresh-migrate', function () {
    Artisan::call('migrate:fresh');
    return "Migrated is fresh created";
});
Route::get('refresh-migrate', function () {
    Artisan::call('migrate:refresh');
    return "Migrated is fresh created";
});

Route::middleware(['auth.shopify'])->group(function () {

    Route::get('', [WebhookJobsController::class, 'checkWebHooks'])->name('setup.webhook'); // # ->middleware(['custom.billable']);

    Route::get('configuration', [ConfigurationController::class, 'index'])->name('configuration'); // # ->middleware(['custom.billable']);
    Route::get('create-webhooks', [WebhookJobsController::class, 'createAllWebhooks'])->name('create_webhooks');
    Route::get('get-webhooks', [WebhookJobsController::class, 'getAllWebhooks'])->name('get_webhooks');

});

Route::get('/proxy/check-status', function (\http\Env\Request $request) { return dd($request); })->name('check.SetupStatus')->middleware(['auth.proxy']);



