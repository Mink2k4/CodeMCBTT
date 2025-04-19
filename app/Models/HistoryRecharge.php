<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryRecharge extends Model
{
    use HasFactory;

    protected $table = 'history_recharges';

    protected $fillable = [
        'username',
        'name_bank',
        'type_bank',
        'tranid',
        'amount',
        'promotion',
        'real_amount',
        'status',
        'note',
        'domain',
    ];

    protected $hidden = [
        'domain',
    ];
}
