<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'service_social',
        'status',
        'file',
        'category',
        'domain',
    ];

    protected $hidden = [
        'domain'
    ];
}
