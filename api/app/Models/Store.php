<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory, UUID;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'banner',
        'manager_id',
        'email',
        'phone',
        'alt_phone',
        'address',
        'delivery_zone_id',
        'is_active',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function deliveryZone()
    {
        return $this->belongsTo(DeliveryZone::class);
    }
}
