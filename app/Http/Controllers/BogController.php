<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\ChargeSavedCardAction;
use App\Actions\CreateBogOrderAction;
use App\Actions\CreateSubscriptionAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BogController extends Controller
{
    public function createOrder(Request $request, CreateBogOrderAction $createBogOrder): RedirectResponse
    {
        try {
            $response = $createBogOrder->handle($request->boolean('save_card'));

            return redirect()->away($response['redirect_url']);

        } catch (\Exception $e) {
            Log::error('BOG order creation failed:', ['error' => $e->getMessage()]);

            return abort(500);
        }
    }

    public function chargeSavedCard(ChargeSavedCardAction $chargeSavedCard)
    {
        try {
            $response = $chargeSavedCard->handle('6bccd89b-5d7e-41b9-a0f0-22e4d668376a');

            return redirect()->route('payment.processing', $response['id']);
        } catch (\Exception $e) {
            Log::error('BOG order creation failed:', ['error' => $e->getMessage()]);

            return abort(500);
        }
    }

    public function subscription(Request $request, CreateSubscriptionAction $createSubscription): RedirectResponse
    {
        try {
            $response = $createSubscription->handle();

            return redirect()->away($response['redirect_url']);

        } catch (\Exception $e) {
            Log::error('BOG order creation failed:', ['error' => $e->getMessage()]);

            return abort(500);
        }
    }
}