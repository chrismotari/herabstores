<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, UUID;

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

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    protected static function booted(): void
    {
        static::deleting(function (Category $category) {
            if ($category->products()->exists()) {
                throw new \RuntimeException('Cannot delete a category that still has products.');
            }
            $category->children()->update(['parent_id' => null]);
        });
    }
}
