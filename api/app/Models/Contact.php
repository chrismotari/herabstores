<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    use UUID;

    protected $fillable = [
        'email',
        'name',
        'phone',
        'consented',
        'subject',
        'message',
        'source',
        'referree_code',
        'country_id',
        'city',
        'region',
    ];
}
