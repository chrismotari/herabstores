<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use UUID;
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->id) {
                $order->id = Str::uuid(); 
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
        'order_items' => 'array',
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
}
