@extends('layouts.app')
@section('content')
    <!-- main-area -->
    <main>

        <!-- breadcrumb-area -->
        <div class="breadcrumb-area breadcrumb-bg-two">
            <div class="container custom-container">
                <div class="row">
                    <div class="col-12">
                        <div class="breadcrumb-content">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                                    <li class="breadcrumb-item"><a href="/">Pages</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- breadcrumb-area-end -->

        <!-- checkout-area -->
        <div class="checkout-area pt-90 pb-90 shop-cart-widget">
            <form id="place-order" action="{{ route('orders.store') }}" method="POST">
                @csrf
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="checkout-progress-wrap">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="checkout-progress-step">
                                    <ul>
                                        <li class="active">
                                            <div class="icon"><i class="fas fa-check"></i></div>
                                            <span>Shipping</span>
                                        </li>
                                        <li>
                                            <div class="icon">2</div>
                                            <span>Order Successful</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="checkout-form-wrap">
                                {{-- <input type="hidden" name="total" value="{{ $cart->total }}"> --}}
                                <div class="checkout-form-top">
                                    <h5 class="title">Delivery information</h5>
                                    {{-- <p>Already have an account? <a href="contact-us">Log in</a></p> --}}
                                </div>
                                <div class="row">
                                    
                                    <div class="col-sm-12 col-md-6 col-lg-4 mb-4">
                                        <label class="form-label">Phone<span class="text-danger scale5 ms-2">*</span></label>
                                        <input type="text" class="form-control" required  placeholder="Phone" aria-label="phone" 
                                            value="{{ old('phone', $selected_user->phone ?? '') }}" name="phone">
                                        <x-input-error :messages="$errors->get('phone')" class="mt-2 text-danger" />
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-4 mb-4">
                                        <label class="form-label">Alternative Phone<span class="text-danger scale5 ms-2"></span></label>
                                        <input type="text" class="form-control"   placeholder="Alternative Phone" aria-label="alt_phone" 
                                            value="{{ old('alt_phone', $selected_user->alt_phone ?? '') }}" name="alt_phone">
                                        <x-input-error :messages="$errors->get('alt_phone')" class="mt-2 text-danger" />
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-4 mb-4">
                                        <label class="form-label">Email<span class="text-danger scale5 ms-2"></span></label>
                                        <input type="text" class="form-control" required placeholder="Email" aria-label="email" 
                                            value="{{ old('email', $selected_user->email ?? '') }}" name="email">
                                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                                    </div>
                                </div>
                                <div class="news-and-offers custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="nao">
                                    <label class="custom-control-label" for="nao">Keep me up to date on news and offers</label>
                                </div>
                                
                                <div class="building-info-wrap">
                                    <h5 class="title">Billing Information</h5>
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-6 mb-4">
                                                <label class="form-label">First Name<span class="text-danger scale5 ms-2">*</span></label>
                                                <input type="text" class="form-control" placeholder="First Name" aria-label="fname" 
                                                    value="{{ old('fname', $selected_user->fname ?? '') }}" name="fname">
                                                <x-input-error :messages="$errors->get('fname')" class="mt-2 text-danger" />
                                            </div>
                                            <div class="col-6 mb-4">
                                                <label class="form-label">Lasty Name<span class="text-danger scale5 ms-2">*</span></label>
                                                <input type="text" class="form-control" placeholder="Last Name" aria-label="lname" 
                                                    value="{{ old('lname', $selected_user->lname ?? '') }}" name="lname">
                                                <x-input-error :messages="$errors->get('lname')" class="mt-2 text-danger" />
                                            </div>
                                        </div>
                                        
                                    </div>
                                    

                                    <div class="row">
                                        <div class=" col-md-12 mb-4">
                                            <label class="form-label">Address<span class="text-danger scale5 ms-2">*</span></label>
                                            <textarea class="form-control" required placeholder="Address" aria-label="address" 
                                                name="address">{{ old('address', $selected_user->address ?? '') }}</textarea>
                                            <x-input-error :messages="$errors->get('address')" class="mt-2 text-danger" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 mb-4">
                                            <label class="form-label">County<span class="text-danger scale5 ms-2">*</span></label>
                                            <input type="text" class="form-control" required placeholder="Nairobi" aria-label="city" 
                                                value="{{ old('city', $selected_user->city ?? '') }}" name="city">
                                            <x-input-error :messages="$errors->get('city')" class="mt-2 text-danger" />
                                        </div>
                                        <div class="col-md-4 col-sm-12 mb-4">
                                            <label class="form-label">Estate<span class="text-danger scale5 ms-2">*</span></label>
                                            <select id="select_delivery_zone" required class="nice-select default-select wide form-control" name="estate_id">
                                                <option value="" disabled {{ old('estate_id', $selected_user->estate_id ?? null) === null ? 'selected' : '' }}>Choose...</option>
                                                @forelse($estates as $estate)
                                                <option value="{{ $estate->id }}" data-fee="{{ $estate->delivery_fees }}" data-destination="{{ $estate->name }}" {{ old('estate_id', $selected_user->estate_id ?? null) == $estate->id ? 'selected' : '' }}>
                                                    {{ $estate->name }}
                                                </option>
                                                @empty
                                                <option disabled>No estates available</option>
                                                @endforelse
                                            </select>
                                            <x-input-error :messages="$errors->get('estate_id')" class="mt-2 text-danger" />
                                        </div>
                                        <div class="col-md-4 col-sm-12 mb-4">
                                            <label class="form-label">Delivery Zone<span class="text-danger scale5 ms-2">*</span></label>
                                            <select id="select_delivery_zone" required class="nice-select default-select wide form-control" name="delivery_zone_id">
                                                <option value="" disabled {{ old('delivery_zone_id', $selected_user->delivery_zone_id ?? null) === null ? 'selected' : '' }}>Choose...</option>
                                                @forelse($delivery_zones as $delivery_zone)
                                                <option value="{{ $delivery_zone->id }}" data-fee="{{ $delivery_zone->delivery_fees }}" data-destination="{{ $delivery_zone->name }}" {{ old('delivery_zone_id', $selected_user->delivery_zone_id ?? null) == $delivery_zone->id ? 'selected' : '' }}>
                                                    {{ $delivery_zone->name }}
                                                </option>
                                                @empty
                                                <option disabled>No zones available</option>
                                                @endforelse
                                            </select>
                                            <x-input-error :messages="$errors->get('delivery_zone_id')" class="mt-2 text-danger" />
                                        </div>
                                    </div>
                                    {{-- <div class="different-address-wrap">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="sda">
                                            <label class="custom-control-label" for="sda">Ship to a Different Address?</label>
                                        </div>
                                        <div class="account-create-info">
                                            <a href="contact-us">Create an Account <i class="fas fa-user"></i></a>
                                        </div>
                                    </div> --}}
                                    <div class="row">
                                        <div class=" col-md-12 mb-4">
                                            <label class="form-label">Extra Notes<span class="text-danger scale5 ms-2"></span></label>
                                            <textarea class="form-control" placeholder="Extra Notes ( Optional )" aria-label="message" 
                                                name="message">{{ old('message', $selected_order->message ?? '') }}</textarea>
                                            <x-input-error :messages="$errors->get('message')" class="mt-2 text-danger" />
                                        </div>
                                    </div>
                                
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="shop-cart-total order-summary-wrap">
                                <h3 class="title">Order Summary</h3>
                                @foreach ($cart->cartItems as $cart_item)
                                    <div class="os-products-item">
                                        <div class="thumb">
                                            <a href="shop-details.html"><img src="{{ Str::startsWith($cart_item->product->image_url, ['http://', 'https://']) ? $cart_item->product->image_url : asset('storage/' . $cart_item->product->image_url) }}" alt="no image"></a>
                                        </div>
                                        <div class="content">
                                            <h6 class="title"><a href="shop-details.html">{{$cart_item->quantity}} x {{$cart_item->product->name}}</a></h6>
                                            <span class="price">KSHS {{$cart_item->total }}</span>
                                        </div>
                                        <div class="remove"><i class="far fa-trash-alt"></i></div>
                                    </div>
                                @endforeach
                            
                                <div class="shop-cart-widget">
                                    <ul>
                                        <li class="sub-total"><span>Subtotal</span> KSHS <span id="cart_total">{{ $cart->total }}</span> </li>
                                        <li>
                                            <span>Shipping to <span id="shipping_to"></span></span>
                                            <div class="shop-check-wrap">
                                                <div class="custom-control custom-checkbox">
                                                    <label for="customCheck1">KSHS <span id="shipping_fee"></span></label>
                                                </div>
                                                
                                            </div>
                                        </li>
                                        <li class="cart-total-amount"><span>Total Price</span> <span class="amount">KSH <span id="order_total"></span></span></li>
                                    </ul>
                                    <div class="payment-method-info">
                                        <div class="paypal-method-flex">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" disabled checked class="custom-control-input" id="customCheck5">
                                                <label class="custom-control-label" for="customCheck5">Cash on delivery</label>
                                                <p>Pay with cash upon delivery.</p>
                                            </div>
                                        </div>
                                        <div class="paypal-method-flex">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" disabled class="custom-control-input" id="customCheck6">
                                                <label class="custom-control-label" for="customCheck6">Coming soon</label>
                                            </div>
                                            <div class="paypal-logo"><img src="img/images/card.png" alt=""></div>
                                        </div>
                                    </div>                                        
                                    
                                    <div class="form-group">
                                        <label for="ref_id">Select Referrer</label>
                                        <select class="nice-select default-select wide form-control" name="ref_id">
                                            <option value="" disabled {{ old('ref_id', $selected_user->ref_id ?? null) === null ? 'selected' : '' }}>Choose...</option>
                                            @forelse($refs as $ref)
                                            <option value="{{ $ref->id }}" {{ old('ref_id', $selected_user->ref_id ?? null) == $ref->id ? 'selected' : '' }}>
                                                {{ $ref->user->name }}
                                            </option>
                                            @empty
                                            <option disabled>No referrers available</option>
                                            @endforelse
                                        </select>
                                        <x-input-error :messages="$errors->get('ref_id')" class="mt-2 text-danger" />
                                      </div>
                                    <div class="payment-terms">
                                        <p>Deliveries can take up to 24 hrs.</p>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck7" required name="accept_terms">
                                            <label class="custom-control-label" for="customCheck7">I agree to the website terms and conditions<span class="text-danger scale5 ms-2">*</span></label>
                                        </div>
                                    </div>
                                    {{-- <button class="btn btn-primary" onclick="document.getElementById('place-order').submit();" class="btn">Place order</button> --}}
                                    <button type="submit"  class="btn btn-primary" class="btn">Place order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- checkout-area-end -->

    </main>
@endsection

@push('custom_scripts')
    <script>
        // $(document).ready(function(){
        //     $('#place-order').submit(function(e){
        //         e.preventDefault();
        //         var form = $(this);
        //         var url = form.attr('action');
        //         var type = form.attr('method');
        //         var data = form.serialize();
        //         $.ajax({
        //             url: url,
        //             type: type,
        //             data: data,
        //             success: function(response){
        //                 console.log(response);
        //             }
        //         });
        //     });
        // });

        document.addEventListener("DOMContentLoaded", function () {
            let deliveryZoneSelect = document.getElementById("select_delivery_zone");
            let shippingFeeSpan = document.getElementById("shipping_fee");
            let shippingToSpan = document.getElementById("shipping_to");
            let orderTotalSpan = document.getElementById("order_total");
            let cartTotalSpan = document.getElementById("cart_total");

            deliveryZoneSelect.addEventListener("change", function () {
                let selectedOption = deliveryZoneSelect.options[deliveryZoneSelect.selectedIndex];
                let fee = selectedOption.getAttribute("data-fee") || 0;
                let destination = selectedOption.getAttribute("data-destination") || '';
                shippingFeeSpan.textContent = fee;
                shippingToSpan.textContent = destination;
                orderTotalSpan.textContent = parseInt(cartTotalSpan.textContent) + parseInt(fee);
            });

            // Trigger change event on page load if a value is preselected
            deliveryZoneSelect.dispatchEvent(new Event("change"));
        });
    </script>
@endpush


