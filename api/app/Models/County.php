<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
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
