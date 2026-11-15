<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A discount code. Applies to the whole cart, one category, or one product
 * (set category_id or product_id; neither = whole cart). Delivery is never discounted.
 */
class Offer extends Model
{
    use HasFactory, UUID;

    public const PERCENT = 'percent';

    public const FIXED = 'fixed';

    protected $fillable = [
        'name',
        'description',
        'image',
        'code',
        'discount_type',
        'value',
        'category_id',
        'product_id',
        'min_order_value',
        'start_at',
        'end_at',
        'max_uses',
        'max_uses_per_user',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_active' => 'boolean',
        'max_uses' => 'integer',
        'max_uses_per_user' => 'integer',
    ];

    /** Codes are case-insensitive: stored upper-case. */
    public function setCodeAttribute(?string $value): void
    {
        $this->attributes['code'] = $value === null ? null : strtoupper(trim($value));
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /** Uses that count towards limits (canceled orders give their use back). */
    public function redemptions()
    {
        return $this->orders()->where('status', '!=', OrderStatus::Canceled);
    }

    public function appliesToLabel(): string
    {
        return match (true) {
            $this->product_id !== null => 'Product: '.($this->product?->name ?? '?'),
            $this->category_id !== null => 'Category: '.($this->category?->name ?? '?'),
            default => 'Whole cart',
        };
    }

    public function discountLabel(): string
    {
        return $this->discount_type === self::FIXED
            ? 'KES '.number_format((float) $this->value).' off'
            : rtrim(rtrim(number_format((float) $this->value, 2), '0'), '.').'% off';
    }
}
