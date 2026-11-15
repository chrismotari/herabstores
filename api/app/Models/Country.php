<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
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
