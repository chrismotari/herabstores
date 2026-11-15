<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory, UUID;

    protected $fillable = [
        'id',
        'name',
        'iso3',
        'num_code',
        'phone_code',
    ];
}
