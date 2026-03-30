<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\BogWebhookAction;
use App\Services\Bog\BogPaymentCallbackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BogPaymentCallbackController extends Controller
{
    public function __invoke(Request $request, BogPaymentCallbackService $callbackVerification, BogWebhookAction $webhookAction)
    {

        $requestBody = $request->getContent();

        $signature = $request->header('Callback-Signature');
        $publicKey = config('bog_api.public_key');

        $callbackVerification->signitureIsValid($requestBody, $signature, $publicKey);
        $responseBody = json_decode($requestBody, true)['body'];

        Log::info('Callback response -> '.$requestBody);

        $webhookAction->handle($responseBody);

        return $responseBody;
    }
}
