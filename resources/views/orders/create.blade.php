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
                                    <li class="breadcrumb-item"><a href="index-2.html">Home</a></li>
                                    <li class="breadcrumb-item"><a href="index-2.html">Pages</a></li>
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
        <div class="checkout-area pt-90 pb-90">
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
                            <form id="place-order" action="{{ route('orders.store') }}" method="POST">
                                @csrf
                                {{-- <input type="hidden" name="total" value="{{ $cart->total }}"> --}}
                                <div class="checkout-form-top">
                                    <h5 class="title">Delivery information</h5>
                                    {{-- <p>Already have an account? <a href="contact.html">Log in</a></p> --}}
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-4">
                                        <label class="form-label">Email<span class="text-danger scale5 ms-2">*</span></label>
                                        <input type="text" class="" placeholder="Email" aria-label="fname" 
                                            value="{{ old('fname', $selected_user->fname ?? '') }}" name="fname">
                                        <x-input-error :messages="$errors->get('fname')" class="mt-2 text-danger" />
                                    </div>
                                    <div class="col-sm-6 mb-4">
                                        <label class="form-label">Phone<span class="text-danger scale5 ms-2">*</span></label>
                                        <input type="text" class="" placeholder="Phone" aria-label="lname" 
                                            value="{{ old('lname', $selected_user->lname ?? '') }}" name="lname">
                                        <x-input-error :messages="$errors->get('lname')" class="mt-2 text-danger" />
                                    </div>
                                </div>
                                <div class="news-and-offers custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="nao">
                                    <label class="custom-control-label" for="nao">Keep me up to date on news and offers</label>
                                </div>
                                
                                <div class="building-info-wrap">
                                    <h5 class="title">Billing Information</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label">First Name<span class="text-danger scale5 ms-2">*</span></label>
                                            <input type="text" class="" placeholder="First Name" aria-label="fname" 
                                                value="{{ old('fname', $selected_user->fname ?? '') }}" name="fname">
                                            <x-input-error :messages="$errors->get('fname')" class="mt-2 text-danger" />
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label">Last Name<span class="text-danger scale5 ms-2">*</span></label>
                                            <input type="text" class="" placeholder="Last Name" aria-label="phone" 
                                                value="{{ old('phone', $selected_user->phone ?? '') }}" name="phone">
                                            <x-input-error :messages="$errors->get('phone')" class="mt-2 text-danger" />
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label">Company Name<span class="text-danger scale5 ms-2">*</span></label>
                                            <input type="text" class="" placeholder="Company Name" aria-label="company_name" 
                                                value="{{ old('company_name', $selected_user->company_name ?? '') }}" name="company_name">
                                            <x-input-error :messages="$errors->get('company_name')" class="mt-2 text-danger" />
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label">Country<span class="text-danger scale5 ms-2">*</span></label>
                                            <select class="nice-select default-select wide form-control" name="country_id">
                                                <option value="" disabled {{ old('country_id', $selected_user->country_id ?? null) === null ? 'selected' : '' }}>Choose...</option>
                                                @forelse($countries as $country)
                                                <option value="{{ $country->id }}" {{ old('country_id', $selected_user->country_id ?? null) == $country->id ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                                @empty
                                                <option disabled>No countries available</option>
                                                @endforelse
                                            </select>
                                            <x-input-error :messages="$errors->get('country_id')" class="mt-2 text-danger" />
                                        </div>
                                    </div>
                                   

                                    <div class=" col-md-12 mb-4">
                                        <label class="form-label">Address<span class="text-danger scale5 ms-2">*</span></label>
                                        <input type="text" class="" placeholder="Address" aria-label="address" 
                                            value="{{ old('address', $selected_user->address ?? '') }}" name="address">
                                        <x-input-error :messages="$errors->get('address')" class="mt-2 text-danger" />
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 mb-4">
                                            <label class="form-label">City<span class="text-danger scale5 ms-2">*</span></label>
                                            <input type="text" class="" placeholder="City" aria-label="city" 
                                                value="{{ old('city', $selected_user->city ?? '') }}" name="city">
                                            <x-input-error :messages="$errors->get('city')" class="mt-2 text-danger" />
                                        </div>
                                        <div class="col-sm-6 mb-4">
                                            <label class="form-label">Estate<span class="text-danger scale5 ms-2">*</span></label>
                                            <input type="text" class="" placeholder="Estate" aria-label="estate" 
                                                value="{{ old('estate', $selected_user->estate ?? '') }}" name="estate">
                                            <x-input-error :messages="$errors->get('estate')" class="mt-2 text-danger" />
                                        </div>
                                    </div>
                                    {{-- <div class="different-address-wrap">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="sda">
                                            <label class="custom-control-label" for="sda">Ship to a Different Address?</label>
                                        </div>
                                        <div class="account-create-info">
                                            <a href="contact.html">Create an Account <i class="fas fa-user"></i></a>
                                        </div>
                                    </div> --}}
                                    <textarea name="message" id="message" placeholder="Extra Notes ( Optional )"></textarea>
                                    <div class="payment-terms">
                                        <p>Deliveries can take up to 24 hrs.</p>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck7" required name="accept_terms">
                                            <label class="custom-control-label" for="customCheck7">I agree to the website terms and conditions</label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="shop-cart-total order-summary-wrap">
                            <h3 class="title">Order Summary</h3>
                            @foreach ($cart->cartItems as $cart_item)
                                <div class="os-products-item">
                                    <div class="thumb">
                                        <a href="shop-details.html"><img src="{{ asset('storage/' . $cart_item->product->image_url) }}" alt="no image"></a>
                                    </div>
                                    <div class="content">
                                        <h6 class="title"><a href="shop-details.html">{{$cart_item->quantity}} x {{$cart_item->product->name}}</a></h6>
                                        <span class="price">KSHS {{$cart_item->total }}</span>
                                    </div>
                                    <div class="remove"><i class="fa fa-times-circle"></i></div>
                                </div>
                            @endforeach
                        
                            <div class="shop-cart-widget">
                                <ul>
                                    <li class="sub-total"><span>Subtotal</span> KSHS {{ $cart->total }} </li>
                                    <li>
                                        <span>Shipping to Area 57</span>
                                        <div class="shop-check-wrap">
                                            <div class="custom-control custom-checkbox">
                                                <label for="customCheck1">KSHS 150</label>
                                            </div>
                                            
                                        </div>
                                    </li>
                                    <li class="cart-total-amount"><span>Total Price</span> <span class="amount">KSH {{$cart->total+150}}</span></li>
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
                                <div class="payment-terms">
                                    <p>Deliveries can take up to 24 hrs.</p>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck7" required>
                                        <label class="custom-control-label" for="customCheck7">I agree to the website terms and conditions</label>
                                    </div>
                                </div>
                                <button class="btn btn-primary" onclick="document.getElementById('place-order').submit();" class="btn">Place order</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- checkout-area-end -->

    </main>
@endsection


