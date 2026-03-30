<?php

namespace App\Listeners;

use App\Events\UpdateTransactionStatus;
use App\Services\Bog\TransactionService;

// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Queue\InteractsWithQueue;

class TransactionStatusUpdated
{
    public function __construct(protected TransactionService $transactionService) {}

    public function handle(UpdateTransactionStatus $event): void
    {
        $this->transactionService->update($event->payload);
    }
}
