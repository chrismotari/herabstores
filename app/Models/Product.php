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
        'price',
        'stock',
        'active',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
