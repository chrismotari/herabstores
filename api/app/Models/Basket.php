<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class Basket extends Model
{
    use UUID;

    protected $fillable = [
        'name',
        'slug',
        'stock',
        'is_active',
        'description',
        'price',
        'image',
        'effective_from',
        'effective_to'
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
        {{return $this->effective_from->format('Y-m-d') . ' to ' . $this->effective_to->format('Y-m-d');}};
    }
}
