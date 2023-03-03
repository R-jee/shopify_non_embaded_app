<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppProxyController;
use App\Http\Controllers\WebhookJobsController;
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


Route::get('/', function () { return view('welcome'); })->middleware(['auth.shopify'])->name('home');

Route::middleware(['auth.shopify'])->group(function () {
    Route::get('/', function () { return view('welcome'); })->name('home');
    Route::get('create-webhooks', [WebhookJobsController::class, 'createAllWebhooks'])->name('create_webhooks');
    Route::get('get-webhooks', [WebhookJobsController::class, 'getAllWebhooks'])->name('get_webhooks');


});

Route::get('/proxy/1', [AppProxyController::class, 'index'])->name('proxy')->middleware('auth.proxy');
Route::get('/proxy/check-setup-status', [AppProxyController::class, 'checkSetupStatus'])->name('check.setup.status')->middleware(['auth.proxy']);



