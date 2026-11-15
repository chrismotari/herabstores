<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    use HasFactory, UUID;

    protected $fillable = [
        'id',
        'name',
        'code',
        'description',
        'store_id',
        'is_active',
    ];
}
