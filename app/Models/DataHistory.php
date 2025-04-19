<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'action',
        'data',
        'old_data',
        'new_data',
        'ip',
        'description',
        'data_json',
        'domain',
    ];

    protected $hidden = [
        'domain'
    ];
}
