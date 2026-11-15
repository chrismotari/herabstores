<?php

namespace App\Models;

use App\Support\MediaUrl;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A cart line: either a product or a basket (exactly one is set).
 */
class CartItem extends Model
{
    use HasFactory, UUID;

    protected $fillable = [
        'cart_id',
        'product_id',
        'basket_id',
        'quantity',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function basket()
    {
        return $this->belongsTo(Basket::class);
    }

    /** The product or basket on this line. */
    public function getPurchasableAttribute(): Product|Basket|null
    {
        return $this->product_id ? $this->product : $this->basket;
    }

    public function getTypeAttribute(): string
    {
        return $this->basket_id ? 'basket' : 'product';
    }

    public function getNameAttribute(): ?string
    {
        return $this->purchasable?->name;
    }

    public function getUnitPriceAttribute(): float
    {
        return (float) ($this->purchasable?->price ?? 0);
    }

    public function getImageUrlAttribute(): ?string
    {
        return MediaUrl::for($this->product_id ? $this->product?->image_url : $this->basket?->image);
    }

    /** Storefront link (baskets have no web page yet). */
    public function getUrlAttribute(): ?string
    {
        return $this->product_id && $this->product ? route('products.show', $this->product) : null;
    }

    public function getTotalAttribute(): float
    {
        return $this->unit_price * $this->quantity;
    }
}
