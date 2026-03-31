<?php

namespace App\Actions;

use App\Models\Card;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class BogWebhookAction
{
    public function handle(array $payload): void
    {
        $transaction = Transaction::where('transaction_id', $payload['order_id'])->lockForUpdate()->firstOrFail();

        DB::transaction(function () use ($transaction, $payload) {
            $status = $payload['order_status']['key'];

            if ($transaction->status === $status) {
                return;
            }

            $transaction->update([
                'status' => $status,
                'log' => $payload
            ]);

            if($status !== 'completed')
            {
                return;
            }

            match ($transaction->type) {
                'saveCard' => $this->handleSaveCard($transaction, $payload),
                'subscribe' => $this->handleSubscription($transaction, $payload),
                default => null,
            };
        });
    }

    private function handleSaveCard(Transaction $transaction, array $payload): void
    {
        $this->createCard($transaction, $payload);
    }

    private function handleSubscription(Transaction $transaction, array $payload): void
    {
        $this->createCard($transaction, $payload);
        $this->createSubscription($transaction);
    }

    private function createCard(Transaction $transaction, array $payload): void
    {
        Card::create([
            'user_id' => $transaction->user_id,
            'parent_order_id' => $transaction->transaction_id,
            'provider' => 'bog',
            'status' => 'active',
            'number' => mask_card_number($payload['payment_detail']['payer_identifier']),
            'type' => $payload['payment_detail']['card_type'],
            'expiry_date' => $payload['payment_detail']['card_expiry_date'],
        ]);
    }

    private function createSubscription(Transaction $transaction): void
    {
        Subscription::create([
            'user_id' => $transaction->user_id,
            'parent_order_id' => $transaction->transaction_id,
            'amount' => $transaction->amount,
            'status' => 'active',
            'next_charge_at' => now()->addMonth()
        ]);
    }
}