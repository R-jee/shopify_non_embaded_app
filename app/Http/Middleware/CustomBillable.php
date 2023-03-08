<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
class CustomBillable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            info(json_encode($request));
            if( Config::get('shopify-app.billing_enabled') === true  ){
                $shop = auth()->user();
                if(!$shop->isFreemium() && !$shop->isGrandfathered() && $shop->plan == null ){
                    // if(!$shop->shopify_freemium && !$shop->shopify_grandfathered && $shop->plan == null ){
                    return redirect()->route('billing.plans');
                }
            }
            return $next($request);
        }catch (\Exception $e){
            return dd( "\nMESSAGE :: ". $e->getMessage() ."\nCODE :: ". $e->getCode() ."\nLINE :: ". $e->getLine()  );
        }
    }
}
