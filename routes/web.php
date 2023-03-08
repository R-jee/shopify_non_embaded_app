<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppProxyController;
use App\Http\Controllers\WebhooksController;
use App\Http\Controllers\ConfigurationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OauthController;
use Osiset\ShopifyApp\Storage\Models\Plan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str as Str;
use App\Models\Configuration;
use Osiset\ShopifyApp\Http\Controllers\BillingController;
use Osiset\ShopifyApp\Actions\CancelCurrentPlan as CancelCurrentPlan;
use Osiset\ShopifyApp\Contracts\Commands\Charge as IChargeCommand;
use Osiset\ShopifyApp\Contracts\Queries\Shop as IShopQuery;
use Osiset\ShopifyApp\Objects\Values\ShopId as ShopId;
use Osiset\ShopifyApp\Services\ChargeHelper as ChargeHelper;
use Osiset\ShopifyApp\Http\Controllers\AuthController as AuthController;
use Osiset\ShopifyApp\Http\Controllers\HomeController;
use Osiset\ShopifyApp\Util;
use Illuminate\Support\Facades\DB as DB;

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
Route::get('privacy', function (Request $request) {
});
Route::get('terms', function (Request $request) {
});
Route::get('contacts', function (Request $request) {
});
Route::get('clear-cache', function () {
    try {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        return "Cache is cleared";
    } catch (Exception $e) {
        return dd("\nMESSAGE :: " . $e->getMessage() . "\nCODE :: " . $e->getCode() . "\nLINE :: " . $e->getLine());
    }
});
Route::get('optimize', function () {
    try {
        Artisan::call('optimize');
        return "Cache is Optimized!";
    } catch (Exception $e) {
        return dd("\nMESSAGE :: " . $e->getMessage() . "\nCODE :: " . $e->getCode() . "\nLINE :: " . $e->getLine());
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
    } catch (Exception $e) {
        return dd("\nMESSAGE :: " . $e->getMessage() . "\nCODE :: " . $e->getCode() . "\nLINE :: " . $e->getLine());
    }
});

Route::middleware(['auth.shopify'])->group(function () {
    Route::get('checkSetupStatus', [ConfigurationController::class, 'checkSetupStatus'])->name('check.SetupStatus');
    Route::get('', [WebhooksController::class, 'checkWebHooks'])->name('home')->middleware(['custom.billable']);
    Route::get('login', function () {
        return view('login');
    })->name('login');
    Route::get('configuration', [ConfigurationController::class, 'index'])->name('configuration')->middleware(['custom.billable']);
    Route::get('create-webhooks', [WebhooksController::class, 'createAllWebhooks'])->name('create_webhooks');
    Route::get('get-webhooks', [WebhooksController::class, 'getAllWebhooks'])->name('get_webhooks');
    Route::get('enable_module', [ConfigurationController::class, 'enableModule'])->name('enable_module');
    // Route::get('/billing/{plan?}', [BillingController::class . 'index'])->where('plan', '^([0-9]+|)$')->name(Util::getShopifyConfig('route_names.billing'));
    // to show all billing plans
    Route::get('plans', [ConfigurationController::class, 'getAllPlans'])->name('plans');
    // to get free plan
    Route::get('free-plan', [ConfigurationController::class, 'freePlan'])->name('free.plan');
    Route::get('check-webhooks-status', [WebhooksController::class, 'checkWebHooks'])->name('setup.status.webhook');
    // to get selected plan name
    Route::get('selected_plan', [ConfigurationController::class, 'userSelectedPlan'])->name('selected.plan');
});

// add app proxy
//Route::get('proxy/check-status', function (\http\Env\Request $request) { return dd($request); })->name('check.proxy')->middleware(['auth.proxy']);
