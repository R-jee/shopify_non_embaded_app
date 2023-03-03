<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class WebhookJobsController extends Controller
{
    public function createAllWebhooks(Request $request)
    {
        $shop = Auth::user();

        return $request->input();
        die();

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

        $webhook_array = array(
            "webhook" => array(
                "topic"   => "customers/create",
                "address" =>  env('APP_URL') . "/webhook/customers-create",
                "format"  => "json"
            )
        );
        $webhook_result = $shop->api()->rest('POST', '/admin/api/'. env('SHOPIFY_API_VERSION') .'/webhooks.json', $webhook_array );

        info('Webhooks Created.');
        return view('configuration');
    }
    public function getAllWebhooks(Request $request)
    {
        $shop = Auth::user();
        $webhook_result = $shop->api()->rest('GET', '/admin/api/'. env('SHOPIFY_API_VERSION') .'/webhooks.json', array() );
        echo '<pre>';
        print_r( ($webhook_result['body']['container']['webhooks']) );
        echo '</pre>';
    }
}
