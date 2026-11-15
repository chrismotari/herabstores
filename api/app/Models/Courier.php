<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'phone',
        'alt_phone',
        'email',
        'address',
        'country_id',
        'is_active',
    ];
}
