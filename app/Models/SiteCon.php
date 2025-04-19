<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteCon extends Model
{
    use HasFactory;

    protected $table = 'site_cons';

    protected $fillable = [
        'username',
        'domain_name',
        'status',
        'zone_id',
        'status_cloudflare',
        'domain',
    ];

    protected $hidden = [
        'domain'
    ];
}
