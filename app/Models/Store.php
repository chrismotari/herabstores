<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use UUID;

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
        'estate_id',
        'country_id',
        'is_active',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
