<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, UUID;

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
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public const LOW_STOCK_THRESHOLD = 5;

    /**
     * Stock label for the storefront. `total_stock` isn't maintained yet, so
     * "few left" uses 10% of it when set, otherwise a fixed threshold.
     */
    public function getIsInStockAttribute(): string
    {
        if ($this->stock < 1) {
            return 'Out of Stock';
        }

        $lowStock = $this->total_stock > 0
            ? $this->total_stock * 0.1
            : self::LOW_STOCK_THRESHOLD;

        return $this->stock <= $lowStock ? 'Few Items Left' : 'In Stock';
    }

    public function getIsFavouriteAttribute()
    {
        return $this->favourites()->where('user_id', auth()->id())->exists();
    }

    /**
     * Products customers can see and buy: active, in an active category.
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('products.is_active', true)
            ->whereHas('category', fn (Builder $q) => $q->where('is_active', true));
    }

    protected static function booted(): void
    {
        // Orders keep their own snapshot, so only live references need removing.
        static::deleting(function (Product $product) {
            $product->images()->delete();
            CartItem::where('product_id', $product->id)->delete();
            $product->favourites()->delete();
            BasketProduct::where('product_id', $product->id)->delete();
        });
    }
}
