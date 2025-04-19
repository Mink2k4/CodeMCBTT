<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activities extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content',
        'status',
        'domain'
    ];

    protected $table = 'activities';

    protected $hidden = ['domain'];
}
