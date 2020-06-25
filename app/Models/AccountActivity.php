<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountActivity extends Model
{
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m',
    ];

    protected $fillable = [
        'user_id',
        'description',
        'amount',
        'activity_date'
    ];
}
