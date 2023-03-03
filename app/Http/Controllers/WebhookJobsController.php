<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;


class WebhookJobsController extends Controller
{


    public function createAllWebhooks(Request $request)
    {
        try{
            $shop = Auth::user();
            $domain = $shop->getDomain()->toNative();
            $configuration = Configuration::where('shop_url', $domain)->first();
            //return $request->input();
            //die();
            $webhook_array = array(
                "webhook" => array(
                    "topic"   => "app/uninstalled",
                    "address" =>  env('APP_URL') . "/webhook/app-uninstalled",
                    "format"  => "json"
                )
            );
            $webhook_result = $shop->api()->rest('POST', '/admin/api/'. env('SHOPIFY_API_VERSION') .'/webhooks.json', $webhook_array );
            $webhook_array = array(
                "webhook" => array(
                    "topic"   => "products/create",
                    "address" =>  env('APP_URL') . "/webhook/products-create",
                    "format"  => "json"
                )
            );
            $webhook_result = $shop->api()->rest('POST', '/admin/api/'. env('SHOPIFY_API_VERSION') .'/webhooks.json', $webhook_array );

            info('Webhooks Created.');
            return redirect()->route('configuration' , array_merge($request->input(), ['request' => $request , 'shop' => $domain]) );

        }
        catch(Exception $e){
            return dd( "\nMESSAGE :: ". $e->getMessage() ."\nCODE :: ". $e->getCode() ."\nLINE :: ". $e->getLine()  );
        }
    }

    public function checkWebHooks(Request $request)
    {
        try{
            if (isset(auth()->user()->name)) {
                $shop = Auth::user();
                $domain = $shop->getDomain()->toNative();
                $configuration = Configuration::where('shop_url', $domain)->first();
                if (empty($configuration)) {
                    $this->createAllWebhooks($request);
                    $new_config = new Configuration();
                    $new_config->shop_url = $domain;
                    $new_config->webhook_status = true;
                    if ($new_config->save()) {
                        return redirect()->route('configuration' , array_merge($request->input(), ['request' => $request , 'shop' => $domain]) );
                    }
                } else {
                    if ($configuration['webhook_status'] == 0) {
                        $this->Create_allWebHooks($request);
                        $edit_config = Configuration::where('shop_url',  $domain)->first();
                        $edit_config->webhook_status = 1;
                        if ($edit_config->update()) {
                            return redirect()->route('configuration' , array_merge($request->input(), ['request' => $request , 'shop' => $domain]));
                        }
                    }
                }
            }
            return redirect('/configuration');
        }
        catch(Exception $e){
            return dd( "\nMESSAGE :: ". $e->getMessage() ."\nCODE :: ". $e->getCode() ."\nLINE :: ". $e->getLine()  );
        }
    }

    public function getAllWebhooks(Request $request)
    {
        try{
            $shop = Auth::user();
            $webhook_result = $shop->api()->rest('GET', '/admin/api/'. env('SHOPIFY_API_VERSION') .'/webhooks.json', array() );
            echo '<pre>';
            print_r( ($webhook_result['body']['container']['webhooks']) );
            echo '</pre>';
        } catch(Exception $e){
            return dd( "\nMESSAGE :: ". $e->getMessage() ."\nCODE :: ". $e->getCode() ."\nLINE :: ". $e->getLine()  );
        }
    }
}
