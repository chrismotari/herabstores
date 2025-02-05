<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class Category extends Model
{
    use UUID;
    protected $fillable = [
        'name',
        'description',
        'active'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
