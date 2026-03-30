<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Transaction;
use App\Services\Bog\BogOrder;
use Illuminate\Support\Str;

class CreateBogOrderAction
{
    public function handle(bool $saveCard = false): array
    {
        $transaction = Transaction::create([
            'idempotency_key' => Str::uuid(),
            'user_id' => 1,
            'amount' => 500000,
            'payment_method' => 'bog',
        ]);

        $bogOrder = BogOrder::make()
            ->withIdempotencyKey($transaction->idempotency_key)
            ->totalAmount($transaction->amount)
            ->externalOrderId($transaction->id)
            ->basket(['quantity' => 1, 'unit_price' => 0, 'product_id' => 1])
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