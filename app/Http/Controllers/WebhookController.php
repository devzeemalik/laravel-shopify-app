<?php

namespace App\Http\Controllers;

use App\Jobs\AppUninstalledJob;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Osiset\ShopifyApp\Objects\Values\ShopDomain;

class WebhookController extends Controller
{
     function verify_webhook($data, $hmac_header)
        {
          $calculated_hmac = base64_encode(hash_hmac('sha256', $data, $this->API_SECRET_KEY, true));
          return hash_equals($hmac_header, $calculated_hmac);
        }
    public $API_SECRET_KEY;

    public function handleThemePublish(Request $request, $shop) {
        $shop = User::where(['name' => $shop ])->first();
        ensureAssets($shop);
        Log::info("api webhook shop:" . $shop);
        Log::info("api webhook request:" . $request);
    }

    public function handleTestPublish(Request $request) {
        Log::info("webhook request all data:" . $request->all());
        Log::info("webhook request:" . $request);
    }

    public function appUninstall(Request $request) {
        // Get the job class and dispatch
        $jobClass = 'AppUninstalledJob';
        $jobData = json_decode($request->getContent());

        AppUninstalledJob::dispatch(
            new ShopDomain($request->header('x-shopify-shop-domain')),
            $jobData
        );

        return Response::make('', 201);
    }
}
