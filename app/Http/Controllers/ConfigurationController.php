<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\User;
use Illuminate\Http\RedirectResponse as RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as Auth;
use Illuminate\Support\Facades\DB as DB;
use Osiset\ShopifyApp\Actions\CancelCurrentPlan as CancelCurrentPlan;
use Osiset\ShopifyApp\Storage\Commands\Shop as IShopCommand;
use Osiset\ShopifyApp\Storage\Models\Plan as Plan;
use Osiset\ShopifyApp\Storage\Queries\Shop as IShopQuery;


class ConfigurationController extends Controller
{
    //
    public function index(Request $request)
    {
        try{
            if( Auth::user() ){
                //return dd($request);
                $shop = Auth::user();
                $domain = $shop->getDomain()->toNative();
                $configuration = Configuration::where('shop_url', $domain)->first();
                $userSelected_Plan = $this->userSelectedPlan($request);
                return view('configuration')->with(
                    array_merge($request->input(),
                        [
                            'user_plan' => $userSelected_Plan,
                            'configuration' => $configuration,
                            'shop' => $domain,
                            'request' => $request
                        ]
                    )
                );
            }
            return "";
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
            $domain = $shop->getDomain()->toNative();
            // return dd($shop);
            $cancelRecurring_application_charges = DB::table('charges')->where('user_id' , $shop->id)->where('status' , "ACTIVE")->where('plan_id', $shop->plan_id)->first();
            if( isset($cancelRecurring_application_charges) ){
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
            return redirect()->route('configuration' , array_merge($request->input(), ['request' => $request , 'shop' => $domain]));
        }
        catch(Exception $e){
            return dd( "\nMESSAGE :: ". $e->getMessage() ."\nCODE :: ". $e->getCode() ."\nLINE :: ". $e->getLine()  );
        }
    }

    /**
     * Get all plans and view plans page
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function getAllPlans(Request $request){
        try {
            $shop = Auth::user();
            $domain = $shop->getDomain()->toNative();
            $plans = Plan::all();
            return view("billing")->with(array_merge($request->input(), ['plans' => $plans, 'shop' => $domain, 'request' => $request]));
        }catch (\Exception $e){
            return dd( "\nMESSAGE :: ". $e->getMessage() ."\nCODE :: ". $e->getCode() ."\nLINE :: ". $e->getLine()  );
        }
    }

    /**
     * Get User selected plan
     *
     * @param Request $request
     * @return "" | null | mixed;
     */
    public function userSelectedPlan(Request $request)
    {

        if( Auth::user() ){
            if( Auth::user()->shopify_freemium == 1 ){
                $shop = Auth::user()->shop;
                return view('liquid.active_plan')->with(['shop'=> $shop , 'activePlan' => null ])->render();
            }else{
                $shop = Auth::user()->shop;
                $plan_id = Auth::user()->plan_id;
                if( !empty($plan_id)  ){
                    $config = Configuration::where('shop_url', $shop)->where('');
                    $plan = DB::table('charges')->where('plan_id', $plan_id)->where('user_id', Auth::user()->id )->where('status', "ACTIVE")->first();
                    if( $plan ){
                        return view('liquid.active_plan')->with(['shop'=> $shop , 'activePlan' => $plan ])->render();
                    }
                }else{
                    return "";
                }
            }
        }
        return '';
    }


}
