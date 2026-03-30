<?php

namespace App\Services\Bog;

use App\Models\Transaction;

class TransactionService
{
    public function create(array $data): Transaction
    {
        return Transaction::create([]);
    }

    public function update(array $data): ?Transaction
    {
        return Transaction::create([]);
    }
}
