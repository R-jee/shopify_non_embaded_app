<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AppProxyController extends Controller
{
    //
    public function index(Request $request)
    {
        return response("Hello, world! It's proxy testing ...")->withHeaders(['Content-Type' => 'application/liquid']);
    }

    public function checkSetupStatus(Request $request)
    {
        $shop = User::where('name', $request->shop)->first();
        if($shop->status){
            return response()->json(['status' => true]);
        }else{
            return response()->json(['status' => false]);
        }
    }
}
