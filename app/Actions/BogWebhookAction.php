<?php

namespace App\Actions;

use App\Models\Card;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class BogWebhookAction
{
    public function handle(array $payload): void
    {
        $transaction = Transaction::where('transaction_id', $payload['order_id'])->firstOrFail();

        DB::transaction(function () use ($transaction, $payload) {
            $status = $payload['order_status']['key'];

            if ($transaction->status === $status) {
                return;
            }

            $transaction->update(['status' => $status]);

            if ($transaction->save_card && $status === 'completed') {

                Card::create([
                    'user_id' => $transaction->user_id,
                    'parent_order_id' => $transaction->transaction_id,
                    'provider' => $payload['client']['brand_en'],
                    'status' => 'active',
                    'number' => mask_card_number($payload['payment_detail']['payer_identifier']),
                    'type' => $payload['payment_detail']['card_type'],
                    'expiry_date' => $payload['payment_detail']['card_expiry_date'],
                ]);
            }
        });
    }
}
