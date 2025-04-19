<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
        use HasFactory;
    
        protected $table = 'refunds';
    
        protected $fillable = [
        'user_id',
        'order_id',
        'reason',
        'status',
        'refund_amount',  // Thêm vào
        'balance_before',  // Thêm vào
        'balance_after',  // Thêm vào
        'created_at',
        'updated_at'
    ];
}

