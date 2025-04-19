<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSocial extends Model
{
    use HasFactory;

    protected $table = 'service_socials';

    protected $fillable = [
        'name',
        'slug',
        'image',
        'folder',
        'status',
        'domain',
    ];

    protected $hidden = ['domain', 'folder'];
}
