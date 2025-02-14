<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
    use UUID;
    //

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalAttribute()
    {
        return $this->cartItems()->sum(DB::raw('price * quantity'));
    }

    public function getItemsCountAttribute()
    {
        return $this->cartItems()->count();
    }
}
