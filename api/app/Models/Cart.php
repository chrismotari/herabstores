<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
    use HasFactory, Prunable, UUID;

    /** Guest carts nobody has touched for this long are deleted (`model:prune`, daily). */
    public const GUEST_CART_DAYS = 30;

    protected $fillable = [
        'user_id',
        'token',
        'discount_code',
        'status',
    ];

    /** Abandoned guest carts. Customers' carts are kept. */
    public function prunable(): Builder
    {
        return static::whereNull('user_id')->where('updated_at', '<', now()->subDays(self::GUEST_CART_DAYS));
    }

    protected function pruning(): void
    {
        $this->cartItems()->delete(); // no cascade on cart_items.cart_id
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function getItemsAttribute()
    {
        return $this->hasMany(CartItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalAttribute()
    {
        $grand_total = 0;
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
