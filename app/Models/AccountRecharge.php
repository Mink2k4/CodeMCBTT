<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountRecharge extends Model
{
    use HasFactory;

    protected $table = 'account_recharges';

    protected $fillable = [
        'type',
        'account_name',
        'account',
        'account_number',
        'password',
        'api_token',
        'qr_code',
        'quy_doi',
        'logo',
        'domain',
    ];

    protected $hidden = [
        'password',
        'api_token',
        'domain'
    ];
}
