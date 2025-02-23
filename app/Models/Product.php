<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
    ];
    
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
