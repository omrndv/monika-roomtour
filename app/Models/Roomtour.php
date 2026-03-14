<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roomtour extends Model
{
    protected $fillable = [
        'customer_wa', 
        'duration', 
        'schedule', 
        'nominal', 
        'staff', 
        'status', 
        'note', 
        'is_split',
        'gaji_status'
    ];

    protected $casts = [
        'schedule' => 'datetime',
    ];
}