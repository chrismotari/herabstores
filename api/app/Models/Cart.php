<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
    use UUID;
    
    protected $fillable = [
        'user_id',
        'status',
    ];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    public function  getItemsAttribute()
    {
        return $this->hasMany(CartItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalAttribute()
    {
        $grand_total  = 0;
        foreach ($this->cartItems as $cartItem) {
            $grand_total += $cartItem->total;
        }
        // $grand_total = $this->cartItems()->sum(DB::raw('price * quantity'));
        return $grand_total; 
    }

    public function getItemsCountAttribute()
    {
        return $this->cartItems()->count();
    }
}
