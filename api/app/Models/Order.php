<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Order extends Model
{
    use HasFactory, UUID;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (! $order->id) {
                $order->id = Str::uuid()->toString();
            }
            $order->order_number = self::generateNHexa();
        });
    }

    private static function generateNHexa($length = 5)
    {
        do {
            $code = substr(bin2hex(random_bytes(ceil($length / 2))), 0, $length);
        } while (self::where('order_number', $code)->exists()); // Ensure uniqueness

        return $code;
    }
    //

    protected $fillable = [
        'order_number',
        'user_id',
        'total',
        'order_items',
        'status', // New,pending,processing,completed,failed,refunded,canceled,expired, in transit
        'payment_status',
        'payment_method',
        'shipping_method',
        'shipping_address',
        'billing_address',
        'shipped_at',
        'delivered_at',
        'canceled_at',
        'refunded_at',
        'failed_at',
        'paid_at',
        'completed_at',
        'expired_at',
        'fname',
        'lname',
        'company',
        'phone',
        'alt_phone',
        'email',
        'address',
        'city',
        'shipping_fee',
        'courier_id',
        'offer_id',
        'discount_code',
        'discount',
        'store_id',
        'delivery_zone',
        'ref_id',
        'country_id',
        'message',
        'referee_code',
    ];

    protected $casts = [
        'order_items' => 'array',
        'status' => OrderStatus::class,
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'canceled_at' => 'datetime',
        'paid_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function estate()
    {
        return $this->belongsTo(Estate::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function getFullNameAttribute()
    {
        $fname = $this->fname ?? $this->user->fname ?? '';
        $lname = $this->lname ?? $this->user->lname ?? '';

        return "{$fname} {$lname}";
    }

    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->city}, {$this->estate->name}, {$this->country->name}";
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 2);
    }

    public function getItemsAttribute()
    {
        return collect($this->order_items);
    }

    public function getProductsAttribute(): Collection
    {
        $items = $this->order_items;

        $products = collect($items)->map(function ($item) {
            $product = Product::find($item['product_id'] ?? null);

            // If it doesn't exist, create an instance (not saved in DB)
            if (! $product) {
                $product = new Product([
                    'id' => null,  // Explicitly null to indicate it’s not in DB
                    'name' => $item['name'] ?? null,
                    'sku' => $item['sku'] ?? null,
                    'price' => $item['price'] ?? null,
                    'description' => $item['description'] ?? null,
                    'category_id' => $item['category_id'] ?? null,
                ]);
            }

            return $product;
        });

        return $products;

    }

    public function deliveryZone()
    {
        return $this->belongsTo(DeliveryZone::class, 'delivery_zone');
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * @throws ValidationException when the move isn't allowed
     */
    public function transitionTo(OrderStatus $next): void
    {
        $current = $this->status ?? OrderStatus::New;

        if (! $current->canTransitionTo($next)) {
            throw ValidationException::withMessages([
                'status' => "A {$current->value} order can't be marked {$next->value}.",
            ]);
        }

        $this->status = $next;
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    /** What the customer pays on delivery: items - discount + delivery. */
    public function getAmountDueAttribute(): float
    {
        return round((float) $this->total - (float) $this->discount + (float) $this->shipping_fee, 2);
    }
}
