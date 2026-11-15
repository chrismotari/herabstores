<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    use HasFactory, UUID;

    protected $fillable = [
        'name',
        'slug',
        'stock',
        'is_active',
        'description',
        'price',
        'image',
        'effective_from',
        'effective_to',
    ];

    protected $casts = [
        'effective_from' => 'date:Y-m-d',
        'effective_to' => 'date:Y-m-d',
    ];

    public function basket_products()
    {
        return $this->hasMany(BasketProduct::class);
    }

    public function getEffectiveDateAttribute()
    {
        return $this->effective_from->format('Y-m-d').' to '.$this->effective_to->format('Y-m-d');
    }
}
