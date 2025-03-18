<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    use UUID;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'delivery_fees',
    ];
    protected $casts = [
        'delivery_fees' => 'decimal:2',
    ];
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }

}
