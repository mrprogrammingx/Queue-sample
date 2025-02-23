<?php

namespace App\Models;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'order_number',
        'total_amount',
        'ordered_at'
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
