<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    use HasFactory, UUID;

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function estates()
    {
        return $this->hasMany(Estate::class);
    }

    public function deliveryZones()
    {
        return $this->hasMany(DeliveryZone::class);
    }
}
