<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppProxyController;
use App\Http\Controllers\WebhooksController;
use App\Http\Controllers\ConfigurationController;
use \Exception;
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
    try {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        return "Cache is cleared";
    }catch(Exception $e){
        return dd( "\nMESSAGE :: ". $e->getMessage() ."\nCODE :: ". $e->getCode() ."\nLINE :: ". $e->getLine()  );
    }

});
Route::get('optimize', function () {
    try {
        Artisan::call('optimize');
        return "Cache is Optimized!";
    }
    catch(Exception $e){
        return dd( "\nMESSAGE :: ". $e->getMessage() ."\nCODE :: ". $e->getCode() ."\nLINE :: ". $e->getLine()  );
    }
});
Route::get('fresh-migrate', function () {
    Artisan::call('migrate:fresh');
    return "Migrated is fresh created";
});
Route::get('refresh-migrate', function () {
    try {
        Artisan::call('migrate:refresh');
        return "Migrated is fresh created";
    }
    catch(Exception $e){
        return dd( "\nMESSAGE :: ". $e->getMessage() ."\nCODE :: ". $e->getCode() ."\nLINE :: ". $e->getLine()  );
    }
});

Route::middleware(['auth.shopify'])->group(function () {

    Route::get('/checkSetupStatus', [ConfigurationController::class, 'checkSetupStatus'])->name('check.SetupStatus');

    Route::get('/', [WebhooksController::class, 'checkWebHooks'])->name('home'); // # ->middleware(['custom.billable']);
    Route::get('/login', function () { return view('login'); })->name('login');
    Route::get('/configuration', [ConfigurationController::class, 'index'])->name('configuration'); // # ->middleware(['custom.billable']);
    Route::get('/create-webhooks', [WebhooksController::class, 'createAllWebhooks'])->name('create_webhooks');
    Route::get('/get-webhooks', [WebhooksController::class, 'getAllWebhooks'])->name('get_webhooks');
    Route::get('/enable_module', [ConfigurationController::class, 'enableModule'])->name('enable_module');

    Route::get('/free-plan', [ConfigurationController::class, 'freePlan'])->name('free.plan');
    Route::get('/check-webhooks-status', [CheckWebhookController::class, 'index'])->name('setup.status.webhook');


});


Route::get('/proxy/check-status', function (\http\Env\Request $request) { return dd($request); })->name('check.proxy')->middleware(['auth.proxy']);
