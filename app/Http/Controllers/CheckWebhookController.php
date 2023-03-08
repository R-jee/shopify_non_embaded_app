<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckWebhookController extends WebhooksController
{
    public function index(Request $request)
    {
        dd( $this->checkWebHooks($request));
    }
}
