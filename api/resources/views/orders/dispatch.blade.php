@extends('layouts.backend', ['activePage' => 'dispatch order', 'title' => 'Dispatch Order'])
@section('title', 'Dispatch Order')
@section('content')
<div class="row">
    <div class="col-xl-8">
        <form action=" {{ route('orders.dispatch.save') }} " method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="order_number" class="form-label">Order Number</label>
                <input type="text" class="form-control" disabled placeholder="Order Number" aria-label="order_number"
                    value="{{ old('order_number', $selected_order->order_number ?? '') }}" name="order_number">
                <x-input-error :messages="$errors->get('order_number')" class="mt-2 text-danger" />
            </div>

            <div class="mb-3">
                <label for="courier_id" class="form-label">Assign Courier</label>
                <select class="form-control" id="courier_id" name="courier_id" required>
                    @forelse($couriers as $courier)
                    <option value="{{ $courier->id }}" {{ old('courier_id', $selected_order->courier_id ?? null) == $courier->id ? 'selected' : '' }}>
                        {{ $courier->name }}
                    </option>
                    @empty
                    <option disabled>No couriers available</option>
                    @endforelse
                </select>
                <x-input-error :messages="$errors->get('courier_id')" />
            </div>

            <div class="mb-3">
                <label for="store_id" class="form-label">Assign Store</label>
                <select class="form-control" id="store_id" name="store_id" required>
                    @forelse($stores as $store)
                    <option value="{{ $store->id }}" {{ old('store_id', $selected_order->store_id ?? null) == $store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                    @empty
                    <option disabled>No couriers available</option>
                    @endforelse
                </select>
                <x-input-error :messages="$errors->get('store_id')" />
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Extra notes</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $selected_product->description ?? '') }}</textarea>
                <x-input-error :messages="$errors->get('description')" />
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
	</div>
</div>
@endsection