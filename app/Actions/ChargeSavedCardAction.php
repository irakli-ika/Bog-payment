<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Transaction;
use App\Services\Bog\BogOrder;
use Illuminate\Support\Str;

class ChargeSavedCardAction
{
    public function handle(string $parent_order_id): array
    {
        $transaction = Transaction::create([
            'idempotency_key' => Str::uuid(),
            'user_id' => 1,
            'amount' => 500000,
            'payment_method' => 'bog',
        ]);

        $paymentDetails = BogOrder::make()
            ->withIdempotencyKey($transaction->idempotency_key)
            ->totalAmount($transaction->amount)
            ->externalOrderId($transaction->id)
            ->basket(['quantity' => 1, 'unit_price' => 0, 'product_id' => 1])
            ->chargeCard($parent_order_id);

        $transaction->update([
            'transaction_id' => $paymentDetails['id'],
        ]);

        return $paymentDetails;
    }
}
