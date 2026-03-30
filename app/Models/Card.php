<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'user_id',
        'parent_order_id',
        'provider',
        'number',
        'expiry_date',
        'type',
        'status',
    ];
}
