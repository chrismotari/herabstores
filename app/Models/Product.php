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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function getIsInStockAttribute()
    {
        // skip function for now
        return 'In Stock';

        if (!$this->stock  || !$this->total_stock ) {
            return 'Out of Stock';
        }

        if ($this->stock < 1) {
            return 'Out of Stock';
        }

        $tenPercent = $this->total_stock * 0.1;

        if ($this->stock < $tenPercent) {
            return 'Few Items Left';
        }

        return 'In Stock';
    }

    public function getIsFavouriteAttribute()
    {
        return $this->favourites()->where('user_id', auth()->id())->exists();
    }
}
