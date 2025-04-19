<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    public $table = 'orders';

    protected $fillable = [
        'username',
        'service_id',
        'service_name',
        'server_service',
        'price',
        'quantity',
        'total_payment',
        'order_code',
        'order_link',
        'start',
        'buff',
        'actual_service',
        'actual_path',
        'actual_server',
        'status',
        'action',
        'dataJson',
        'error',
        'isShow',
        'note',
        'history',
        'refund',
        'domain',
    ];

    protected $hidden = [
        'domain',
        'order_code',
    ];

}
