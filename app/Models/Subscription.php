<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'parent_order_id',
        'amount',
        'status',
        'next_charge_at'
    ];

    protected function casts(): array
    {
        return [
            // 'status' => 'enum',
            'next_charge_at' => 'datetime',
        ];
    }
}
