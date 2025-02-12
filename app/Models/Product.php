<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

class Product extends Model
{
    use UUID;
    protected $fillable = [
        'name',
        'description',
        'slug',
        'price',
        'stock',
        'is_active',
        'category_id',
        'user_id',
        'image_url',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
