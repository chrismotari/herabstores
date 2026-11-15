<?php

use App\Filament\Imports\ProductImporter;
use App\Filament\Resources\Baskets\Pages\CreateBasket;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Models\Basket;
use App\Models\Category;
use App\Models\Favourite;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Filament\Actions\Imports\Models\Import;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['is_admin' => true]);
    $this->actingAs($this->admin);
    Filament::setCurrentPanel('admin');
});

test('catalogue pages render for admins', function (string $url) {
    Product::factory()->count(2)->create();
    Basket::factory()->create();

    $this->get($url)->assertOk();
})->with(['/admin/products', '/admin/products/create', '/admin/categories', '/admin/categories/create', '/admin/baskets', '/admin/baskets/create']);

test('admins can create a product; slug and creator are set', function () {
    $category = Category::factory()->create();

    Livewire::test(CreateProduct::class)
        ->fillForm([
            'name' => 'Kericho Gold Tea', 'slug' => 'kericho-gold-tea', 'category_id' => $category->id,
            'description' => 'Black tea', 'price' => 450, 'stock' => 12, 'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $product = Product::firstWhere('slug', 'kericho-gold-tea');
    expect($product)->not->toBeNull()
        ->and((float) $product->price)->toBe(450.0)
        ->and($product->user_id)->toBe($this->admin->id);
});

test('product slugs must be unique and prices non-negative', function () {
    $existing = Product::factory()->create(['slug' => 'taken']);

    Livewire::test(CreateProduct::class)
        ->fillForm(['name' => 'X', 'slug' => 'taken', 'category_id' => $existing->category_id, 'description' => 'x', 'price' => -1, 'stock' => 0])
        ->call('create')
        ->assertHasFormErrors(['slug' => 'unique', 'price']);
});

test('admins can edit price and stock', function () {
    $product = Product::factory()->create(['price' => 100, 'stock' => 1]);

    Livewire::test(EditProduct::class, ['record' => $product->getRouteKey()])
        ->fillForm(['price' => 120, 'stock' => 30])
        ->call('save')
        ->assertHasNoFormErrors();

    expect((float) $product->fresh()->price)->toBe(120.0)->and($product->fresh()->stock)->toBe(30);
});

test('bulk hide removes products from the shop', function () {
    $products = Product::factory()->count(2)->create();

    Livewire::test(ListProducts::class)->callTableBulkAction('hide', $products);

    expect(Product::where('is_active', true)->count())->toBe(0);
});

test('deleting a product cleans up live references', function () {
    $product = Product::factory()->create();
    $product->images()->create(['image_url' => 'products/a.jpg', 'sort_order' => 1]);
    Favourite::create(['user_id' => $this->admin->id, 'product_id' => $product->id]);

    $product->delete();

    expect(ProductImage::count())->toBe(0)->and(Favourite::count())->toBe(0);
});

test('admins can create a basket with contents', function () {
    [$tea, $sugar] = Product::factory()->count(2)->create();

    Livewire::test(CreateBasket::class)
        ->fillForm([
            'name' => 'Breakfast Box', 'slug' => 'breakfast-box', 'description' => 'Tea and sugar',
            'price' => 900, 'stock' => 10, 'effective_from' => now()->subDay(), 'effective_to' => now()->addMonth(),
            'is_active' => true,
            'basket_products' => [
                ['product_id' => $tea->id, 'quantity' => 2, 'is_active' => true],
                ['product_id' => $sugar->id, 'quantity' => 1, 'is_active' => true],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Basket::firstWhere('slug', 'breakfast-box')->basket_products)->toHaveCount(2);
});

test('categories with products cannot be deleted', function () {
    $product = Product::factory()->create();

    Livewire::test(EditCategory::class, ['record' => $product->category->getRouteKey()])
        ->assertActionHidden('delete');

    expect(fn () => $product->category->delete())->toThrow(RuntimeException::class);
});

test('the CSV importer creates and updates products by slug', function () {
    $tea = Category::factory()->create(['slug' => 'tea', 'name' => 'Tea']);
    $existing = Product::factory()->for($tea)->create(['slug' => 'ketepa', 'price' => 100, 'stock' => 3]);

    $import = Import::create([
        'file_name' => 'products.csv', 'file_path' => 'products.csv', 'importer' => ProductImporter::class,
        'total_rows' => 2, 'user_id' => $this->admin->id,
    ]);
    $columns = ['name' => 'name', 'slug' => 'slug', 'description' => 'description', 'price' => 'price', 'stock' => 'stock', 'category' => 'category', 'is_active' => 'is_active'];
    $importer = new ProductImporter($import, $columns, []);

    $importer(['name' => 'Kericho Gold', 'slug' => '', 'description' => '', 'price' => '450', 'stock' => '20', 'category' => 'Tea', 'is_active' => 'yes']);
    $importer(['name' => 'Ketepa Pride', 'slug' => 'ketepa', 'description' => 'Updated', 'price' => '120', 'stock' => '', 'category' => 'tea', 'is_active' => '']);

    $new = Product::firstWhere('slug', 'kericho-gold');
    expect($new)->not->toBeNull()
        ->and((float) $new->price)->toBe(450.0)
        ->and($new->category_id)->toBe($tea->id)
        ->and($new->user_id)->toBe($this->admin->id)
        ->and((float) $existing->fresh()->price)->toBe(120.0)
        ->and($existing->fresh()->description)->toBe('Updated')
        ->and($existing->fresh()->stock)->toBe(3)
        ->and($existing->fresh()->is_active)->toBeTruthy()
        ->and(Product::count())->toBe(2);
});
