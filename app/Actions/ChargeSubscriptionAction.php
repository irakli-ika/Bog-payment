<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Transaction;
use App\Services\Bog\BogOrder;
use Illuminate\Support\Str;

class ChargeSubscriptionAction
{
    public function handle(string $parentOrderId, int $userId): array
    {
        $parentTransaction = Transaction::where('transaction_id', $parentOrderId)->firstOrFail()->amount;

        $transaction = Transaction::create([
            'idempotency_key' => Str::uuid(),
            'user_id' => $userId,
            'amount' => $parentTransaction->amount,
            'payment_method' => 'bog',
        ]);

        $paymentDetails = BogOrder::make()
            ->withIdempotencyKey($transaction->idempotency_key)
            ->externalOrderId($transaction->id)
            ->chargeSubscription($parentOrderId);

        $transaction->update([
            'transaction_id' => $paymentDetails['id'],
        ]);

        // Send notification with event here

        return $paymentDetails;
    }
}

// Set type in transaction table [normall, saveCard, chargeCard, subscribe, chargeSubscription]
