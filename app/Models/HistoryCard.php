<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryCard extends Model
{
    use HasFactory;

    protected $table = 'history_cards';

    protected $fillable = [
        'username',
        'card_type',
        'card_code',
        'card_serial',
        'card_amount',
        'card_real_amount',
        'status',
        'note',
        'tranid',
        'promotion',
        'discount',
        'domain',
    ];

    protected $hidden = [
        'domain',
    ];
}
