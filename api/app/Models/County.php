<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    use UUID;

    protected $fillable = [
        'id',
        'name',
        'code',
        'description',
        'store_id',
        'is_active',
    ];
}
