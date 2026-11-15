<?php

use App\Filament\Resources\Offers\Pages\CreateOffer;
use App\Filament\Resources\Offers\Pages\EditOffer;
use App\Models\Category;
use App\Models\Offer;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create(['is_admin' => true]));
    Filament::setCurrentPanel('admin');
});

test('the discount code pages render', function (string $url) {
    Offer::factory()->create();

    $this->get($url)->assertOk();
})->with(['/admin/offers', '/admin/offers/create']);

test('admins create a category code; the code is upper-cased', function () {
    $category = Category::factory()->create();

    Livewire::test(CreateOffer::class)
        ->fillForm([
            'name' => 'Fruit week', 'code' => 'fruit-15', 'discount_type' => 'percent', 'value' => 15,
            'start_at' => now(), 'is_active' => true, 'applies_to' => 'category', 'category_id' => $category->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $offer = Offer::sole();
    expect($offer->code)->toBe('FRUIT-15')->and($offer->category_id)->toBe($category->id)->and($offer->product_id)->toBeNull();
});

test('percentages above 100 and duplicate codes are refused', function () {
    Offer::factory()->create(['code' => 'TAKEN']);

    Livewire::test(CreateOffer::class)
        ->fillForm(['name' => 'X', 'code' => 'TAKEN', 'discount_type' => 'percent', 'value' => 150, 'start_at' => now(), 'applies_to' => 'cart'])
        ->call('create')
        ->assertHasFormErrors(['code' => 'unique', 'value']);
});

test('switching a code to the whole cart clears its category', function () {
    $offer = Offer::factory()->create(['category_id' => Category::factory()->create()->id]);

    Livewire::test(EditOffer::class, ['record' => $offer->getRouteKey()])
        ->assertFormSet(['applies_to' => 'category'])
        ->fillForm(['applies_to' => 'cart'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($offer->fresh()->category_id)->toBeNull();
});
