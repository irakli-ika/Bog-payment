<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\ChargeSavedCardAction;
use App\Actions\ChargeSubscriptionAction;
use App\Actions\CreateBogOrderAction;
use App\Actions\CreateSubscriptionAction;
use App\Actions\RefundAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BogController extends Controller
{
    public function createOrder(Request $request, CreateBogOrderAction $action): RedirectResponse
    {
        try {
            $response = $action->handle(1, 1, ['quantity' => 1, 'unit_price' => 0, 'product_id' => 1], $request->boolean('save_card'));

            return redirect()->away($response['redirect_url']);

        } catch (\Exception $e) {
            Log::error('BOG order creation failed:', ['error' => $e->getMessage()]);

            return abort(500);
        }
    }

    public function chargeSavedCard(ChargeSavedCardAction $action)
    {
        try {
            $response = $action->handle('0aab479f-0ada-4c91-9780-898971d6531f', 1, 0, ['quantity' => 1, 'unit_price' => 0, 'product_id' => 1]);

            return redirect()->route('payment.processing', $response['id']);
        } catch (\Exception $e) {
            Log::error('BOG order creation failed:', ['error' => $e->getMessage()]);

            return abort(500);
        }
    }

    public function subscription(CreateSubscriptionAction $action): RedirectResponse
    {
        try {
            $response = $action->handle(1, 0, ['quantity' => 1, 'unit_price' => 0, 'product_id' => 1]);

            return redirect()->away($response['redirect_url']);

        } catch (\Exception $e) {
            Log::error('BOG order creation failed:', ['error' => $e->getMessage()]);

            return abort(500);
        }
    }

    public function chargeSubscription(ChargeSubscriptionAction $action)
    {
        $action->handle('c9c0abe1-fd21-42bd-8772-7c9815eca5a5', 1);
    }

    public function refund(RefundAction $action)
    {
        $action->handle('e5e62026-e383-40bc-b7c0-ca110e7f0d8f', 1);
    }
}
