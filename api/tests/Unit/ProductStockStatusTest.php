<?php

use App\Models\Product;

function productWithStock(int $stock, int $total = 0): Product
{
    return (new Product(['stock' => $stock]))->forceFill(['total_stock' => $total]);
}

it('labels stock status', function (int $stock, int $total, string $label) {
    expect(productWithStock($stock, $total)->is_in_stock)->toBe($label);
})->with([
    'none left' => [0, 0, 'Out of Stock'],
    'negative' => [-1, 100, 'Out of Stock'],
    'at fixed threshold' => [5, 0, 'Few Items Left'],
    'above fixed threshold' => [6, 0, 'In Stock'],
    'under 10% of total' => [9, 100, 'Few Items Left'],
    'over 10% of total' => [11, 100, 'In Stock'],
]);
