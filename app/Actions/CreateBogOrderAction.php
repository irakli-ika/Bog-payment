<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Transaction;
use App\Services\Bog\BogOrder;
use Illuminate\Support\Str;

class CreateBogOrderAction
{
    public function handle(int $userId, int $amount, array $basket = [], bool $saveCard = false): array
    {
        $transaction = Transaction::create([
            'idempotency_key' => Str::uuid(),
            'user_id' => $userId,
            'amount' => $amount,
            'payment_method' => 'bog',
        ]);

        $bogOrder = BogOrder::make()
            ->withIdempotencyKey($transaction->idempotency_key)
            ->totalAmount($amount)
            ->externalOrderId($transaction->id)
            ->basket($basket)
            ->redirectUrls(route('payment.success'), route('payment.fail'));

        try {
            $paymentDetails = $saveCard
                ? $bogOrder->saveCard()
                : $bogOrder->process();
        } catch (\Exception $e) {
            $transaction->update(['status' => 'failed']);
            throw $e;
        }

        $transaction->update([
            'transaction_id' => $paymentDetails['id'],
            'save_card' => $saveCard,
        ]);

        return $paymentDetails;
    }
}