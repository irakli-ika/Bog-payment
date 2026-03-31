<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Transaction;
use App\Services\Bog\BogOrder;
use Illuminate\Support\Str;

class RefundAction
{
    public function handle(string $parentOrderId, int $userId): void
    {
        $parentTransaction = Transaction::where('transaction_id', $parentOrderId)->firstOrFail();

        $transaction = Transaction::create([
            'idempotency_key' => Str::uuid(),
            'user_id' => $userId,
            'amount' => $parentTransaction->amount,
            'payment_method' => 'bog',
            'type' => 'refund'
        ]);

        $paymentDetails = BogOrder::make()
            ->withIdempotencyKey($transaction->idempotency_key)
            ->externalOrderId($transaction->id)
            ->refund($parentOrderId);

        $transaction->update([
            'transaction_id' => $paymentDetails['id'],
        ]);

        // Do something here, (e.g send email/sms notifications)
    }
}
