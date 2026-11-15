<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Rules for discount codes (decision log 2026-11-15):
 *
 * - One code per order; entered by the customer.
 * - Percent or fixed amount, applied to the eligible items: the whole cart,
 *   one category's products, or one product. Never the delivery fee.
 * - The discount never exceeds the eligible amount.
 * - Optional: minimum order (cart subtotal), validity window, total uses,
 *   uses per customer (logged-in: account; guest: phone number at checkout).
 * - Canceled orders give their use back.
 */
class DiscountCalculator
{
    public const FIELD = 'discount_code';

    public function find(string $code): ?Offer
    {
        return Offer::where('code', Str::upper(trim($code)))->first();
    }

    /**
     * @param  Collection<int, CartItem>  $items  cart lines with product/basket loaded
     * @return array{offer: Offer, amount: float}
     *
     * @throws ValidationException when the code can't be used for these items
     */
    public function calculate(string $code, Collection $items, ?User $user = null, ?string $phone = null, ?Offer $locked = null): array
    {
        $offer = $locked ?? $this->find($code);

        if (! $offer || ! $offer->is_active) {
            $this->fail('This discount code is not valid.');
        }
        if ($offer->start_at && $offer->start_at->isFuture()) {
            $this->fail('This discount code is not active yet.');
        }
        if ($offer->end_at && $offer->end_at->isPast()) {
            $this->fail('This discount code has expired.');
        }

        $subtotal = $items->sum(fn (CartItem $item) => $item->total);
        if ($offer->min_order_value && $subtotal < (float) $offer->min_order_value) {
            $this->fail('This code needs an order of at least KES '.number_format((float) $offer->min_order_value).'.');
        }

        $eligible = $items
            ->filter(fn (CartItem $item) => $this->appliesTo($offer, $item))
            ->sum(fn (CartItem $item) => $item->total);
        if ($eligible <= 0) {
            $this->fail('None of the items in your cart qualify for this code.');
        }

        if ($offer->max_uses !== null && $offer->redemptions()->count() >= $offer->max_uses) {
            $this->fail('This discount code has been fully used.');
        }
        if ($offer->max_uses_per_user !== null && $this->usesBy($offer, $user, $phone) >= $offer->max_uses_per_user) {
            $this->fail('You have already used this discount code.');
        }

        $amount = $offer->discount_type === Offer::FIXED
            ? min((float) $offer->value, $eligible)
            : round($eligible * (float) $offer->value / 100, 2);

        return ['offer' => $offer, 'amount' => round($amount, 2)];
    }

    private function appliesTo(Offer $offer, CartItem $item): bool
    {
        return match (true) {
            $offer->product_id !== null => $item->product_id === $offer->product_id,
            $offer->category_id !== null => $item->product_id !== null && $item->product?->category_id === $offer->category_id,
            default => true,
        };
    }

    private function usesBy(Offer $offer, ?User $user, ?string $phone): int
    {
        if (! $user && ! $phone) {
            return 0; // guest before checkout: checked again with their phone when placing the order
        }

        return $offer->redemptions()
            ->where(fn ($q) => $q
                ->when($user, fn ($q) => $q->orWhere('user_id', $user->id))
                ->when($phone, fn ($q) => $q->orWhere('phone', $phone)))
            ->count();
    }

    private function fail(string $message): never
    {
        throw ValidationException::withMessages([self::FIELD => $message]);
    }
}
