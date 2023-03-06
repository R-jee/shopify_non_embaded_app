<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Osiset\ShopifyApp\Actions\CancelCurrentPlan;
use Osiset\ShopifyApp\Storage\Commands\Shop as IShopCommand;
use Osiset\ShopifyApp\Storage\Queries\Shop as IShopQuery;

class ConfigurationController extends Controller
{
    //
    public function index(Request $request)
    {
        try{
            return dd($request);
        }
        catch(Exception $e){
            return dd( "\nMESSAGE :: ". $e->getMessage() ."\nCODE :: ". $e->getCode() ."\nLINE :: ". $e->getLine()  );
        }

    }

    /**
     * Enable module and create pages on storeFront
     *
     * @param Request $request
     * @return void
     */
    public function enableModule(Request $request)
    {
        try{
            $shop = Auth::user();
            $domain = $shop->getDomain()->toNative();
            $configuration = Configuration::where('shop_url', $domain)->first();
            if ($configuration == null) {
                $configuration = new Configuration();
            }
            if ($request->is_enabled == "true") {
                // $this->createPage();
                $configuration->is_enabled = 1;
            } else {
                // $this->deletePage();
                $configuration->is_enabled = 0;
            }
            if ($configuration->save()) {
                return redirect()->route('configuration' , array_merge($request->input(), ['request' => $request ]));
            }
        }
        catch(Exception $e){
            return dd( "\nMESSAGE :: ". $e->getMessage() ."\nCODE :: ". $e->getCode() ."\nLINE :: ". $e->getLine()  );
        }

    }

    public function checkSetupStatus(Request $request){
        try{
            $shop = User::where('name', $request->input('shop') )->first();
            // dd($shop);
            if(isset($shop->status)){
                return response()->json(['status' => true]);
            }else{
                 return response()->json(['status' => false]);
            }
        }
        catch(Exception $e){
            return dd( "\nMESSAGE :: ". $e->getMessage() ."\nCODE :: ". $e->getCode() ."\nLINE :: ". $e->getLine()  );
        }
    }

    public function freePlan( Request $request, IShopCommand $shopCommand , IShopQuery $shopQuery, CancelCurrentPlan $cancelCurrentPlanAction ){
        try{
            $shop = Auth::user();
            $cancelRecurring_application_charges = DB::table('charges')->where('user_id' , $shop->id)->where('status' , "ACTIVE")->where('plan_id', $shop->plan_id)->first();
            if( $cancelRecurring_application_charges ){
                $charge_canceled = $shop->api()->rest('DELETE', "/admin/api/". env('SHOPIFY_API_VERSION') ."/recurring_application_charges/". $cancelRecurring_application_charges->charge_id .".json", array());
                $get_charge_data = $shop->api()->rest('PUT', "/admin/api/". env('SHOPIFY_API_VERSION') ."/recurring_application_charges/". $cancelRecurring_application_charges->charge_id ."/customize.json", array(
                    "recurring_application_charge" => [
                        'status' => "cancelled"
                    ]
                ));
                DB::table('charges')->where([ 'user_id' => auth()->user()->id, 'status' => "ACTIVE"] )->update(
                    array('cancelled_on' => date('Y-m-d 00:00:00'),
                        'expires_on' => date('Y-m-d 00:00:00'),
                        'trial_days' => 0,
                        'status' => "CANCELLED"
                    )
                );
                User::where('id' , auth()->user()->id )->update(['plan_id' => NULL, 'shopify_freemium' => 1]);
            }else{
                User::where('id' , auth()->user()->id )->update(['plan_id' => NULL, 'shopify_freemium' => 1]);
            }
            return redirect()->route('home', array_merge($request->input(), ['request' => $request, 'shop' => $shop->name]) );
        }
        catch(Exception $e){
            return dd( "\nMESSAGE :: ". $e->getMessage() ."\nCODE :: ". $e->getCode() ."\nLINE :: ". $e->getLine()  );
        }
    }



}
