<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            if($shop->status){
                return response()->json(['status' => true]);
            }else{
                return response()->json(['status' => false]);
            }
        }
        catch(Exception $e){
            return dd( "\nMESSAGE :: ". $e->getMessage() ."\nCODE :: ". $e->getCode() ."\nLINE :: ". $e->getLine()  );
        }
    }

}
