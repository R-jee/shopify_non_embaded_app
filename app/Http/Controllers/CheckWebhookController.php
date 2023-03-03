<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckWebhookController extends Controller
{
    public function check_webHooks(Request $request)
    {
        if (isset(auth()->user()->name)) {
            $shop = Auth::user();
            $domain = $shop->getDomain()->toNative();
            $configuration = Configuration::where('shop_url', $domain)->first();
            if (empty($configuration)) {
                $this->Create_allWebHooks($request);
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
        //  return redirect()->route('configuration' , array_merge($request->input(), ['request' => $request ]));
    }
}
