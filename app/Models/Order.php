<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use UUID;
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->nhexa = self::generateNHexa();
        });
    }

    private static function generateNHexa($length = 5)
    {
        do {
            $code = substr(bin2hex(random_bytes(ceil($length / 2))), 0, $length);
        } while (self::where('nhexa', $code)->exists()); // Ensure uniqueness

        return $code;
    }
    //

    protected $fillable = [
        'order_number',
        'user_id',
        'total',
        'order_items',
        'status',
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
        'first_name',
        'last_name',
        'company',
        'phone',
        'alt_phone',
        'email',
        'address',
        'city',
        'estate_id',
        'country_id',
        'message',
        'referee_code',
    ];

    protected $casts = [
        'cart_items' => 'array', // Automatically handle JSON conversion
    ];
}
