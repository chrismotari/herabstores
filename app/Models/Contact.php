<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class Contact extends Model
{
    use UUID;
    use HasFactory;
    protected $fillable = [
        'email',
        'name',
        'phone',
        'consented',
        'message',
        'source',
        'referree_code',
        'country_id',
        'city',
        'region',
    ];
}
