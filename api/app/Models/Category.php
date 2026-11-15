<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use UUID;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'is_active',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
