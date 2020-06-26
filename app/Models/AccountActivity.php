<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    protected static function booted()
    {
        if (!Auth::guest()) {
            static::addGlobalScope('user_id', function (Builder $builder) {
                $builder->where('user_id', Auth::id());
            });
        }
    }
}
