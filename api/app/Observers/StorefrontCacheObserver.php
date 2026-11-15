<?php

namespace App\Observers;

use App\Jobs\RevalidateStorefront;
use App\Models\Basket;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

/**
 * Catalog changes → revalidate the storefront pages that show them.
 * Dispatched after the DB transaction commits.
 */
class StorefrontCacheObserver
{
    public function saved(Model $model): void
    {
        $this->dispatch($model);
    }

    public function deleted(Model $model): void
    {
        $this->dispatch($model);
    }

    private function dispatch(Model $model): void
    {
        $tags = match (true) {
            $model instanceof Product => [
                'products',
                'product:'.$model->slug,
                ...($model->wasChanged('slug') ? ['product:'.$model->getOriginal('slug')] : []),
                ...array_filter([$model->category?->slug ? 'category:'.$model->category->slug : null]),
                ...($model->wasChanged('category_id') && $model->getOriginal('category_id')
                    ? array_filter(['category:'.Category::find($model->getOriginal('category_id'))?->slug])
                    : []),
            ],
            $model instanceof Category => [
                'categories', 'products', 'category:'.$model->slug,
                ...($model->wasChanged('slug') ? ['category:'.$model->getOriginal('slug')] : []),
            ],
            $model instanceof Basket => ['baskets', 'basket:'.$model->slug],
            default => [],
        };

        if ($tags) {
            RevalidateStorefront::dispatch($tags)->afterCommit();
        }
    }
}
