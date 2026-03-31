<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Transaction;
use App\Services\Bog\BogOrder;
use Illuminate\Support\Str;

class ChargeSavedCardAction
{
    public function handle(string $parentOrderId, int $userId, int $amount, array $basket = []): array
    {
        $transaction = Transaction::create([
            'idempotency_key' => Str::uuid(),
            'user_id' => $userId,
            'amount' => $amount,
            'payment_method' => 'bog',
            'type' => 'chargeCard'
        ]);

        $paymentDetails = BogOrder::make()
            ->withIdempotencyKey($transaction->idempotency_key)
            ->totalAmount($amount)
            ->externalOrderId($transaction->id)
            ->basket($basket)
            ->chargeCard($parentOrderId);

        $transaction->update([
            'transaction_id' => $paymentDetails['id'],
        ]);

        return $paymentDetails;
    }
}
