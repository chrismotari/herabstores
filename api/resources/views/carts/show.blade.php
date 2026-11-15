
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
                                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">My cart</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- breadcrumb-area-end -->

        <!-- cart-area -->
        <div class="cart-area pt-90 pb-90">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-7">
                        <div class="cart-wrapper">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="product-thumbnail"></th>
                                            <th class="product-name">Product</th>
                                            <th class="product-price">Price</th>
                                            <th class="product-quantity">QUANTITY</th>
                                            <th class="product-subtotal">SUBTOTAL</th>
                                            <th class="product-delete"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ( $cart->cartItems as $cart_item )
                                            <tr>
                                                <td class="product-thumbnail"><a href="{{route('products.show', $cart_item->product)}}"><img src="{{ Str::startsWith($cart_item->product->image_url, ['http://', 'https://']) ? $cart_item->product->image_url : asset('storage/' . $cart_item->product->image_url) }}" alt="no image"></a></td>
                                                <td class="product-name">
                                                    <h4><a href="{{route('products.show', $cart_item->product)}}">{{$cart_item->product->name}}</a></h4>
                                                </td>
                                                <td class="product-price">{{$cart_item->product->price}}</td>
                                                {{-- <td class="product-quantity">
                                                    <div class="cart--plus--minus">
                                                        <form action="#" class="num-block">
                                                            <input type="text" class="in-num" value="1" readonly="">
                                                            <div class="qtybutton-box">
                                                                <span class="plus"><i class="fas fa-angle-up"></i></span>
                                                                <span class="minus dis"><i class="fas fa-angle-down"></i></span>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </td> --}}
                                                <td class="product-subtotal"><span>{{$cart_item->quantity}}</span></td>
                                                <td class="product-subtotal"><span>{{$cart_item->total}}</span></td>
                                                <td class="product-delete">
                                                    <form id="remove_item" action="{{ route('cart_items.destroy', $cart_item->product) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        {{-- <button type="submit"><i class="far fa-trash-alt"></i></button> --}}
                                                        <a href="javascript:{}" onclick="document.getElementById('remove_item').submit();"><i class="far fa-trash-alt"></i></a>
                                                    </form>
                                                </td>
                                            </tr>
                                            
                                        @empty
                                            Cart is empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- <div class="shop-cart-bottom">
                            <div class="cart-coupon">
                                <form action="#">
                                    <input type="text" placeholder="Enter Coupon Code...">
                                    <button class="btn">Apply Coupon</button>
                                </form>
                            </div>
                            <div class="continue-shopping">
                                <a href="#" class="btn">update Cart</a>
                            </div>
                        </div> --}}
                    </div>
                    <div class="col-xl-5 col-lg-12">
                        <div class="shop-cart-total">
                            <h3 class="title">Cart Totals</h3>
                            <div class="shop-cart-widget">
                                <form action="#">
                                    <ul>
                                        <li class="sub-total"><span>Subtotal</span> KSHS {{$cart->total}}</li>
                                        <li>
                                            <span>Shipping</span>
                                            <div class="shop-check-wrap">
                                                KSHS 150
                                                {{-- <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                    <label class="custom-control-label" for="customCheck1">Home Delivery: KSH 150.00 </label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck2">
                                                    <label class="custom-control-label" for="customCheck2">LOCAL PICKUP: KSH 0.00</label>
                                                </div> --}}
                                            </div>
                                        </li>
                                        <li class="cart-total-amount"><span>Cart Total</span> <span class="amount">KSHS {{$cart->total + 150}} </span></li>
                                    </ul>
                                    <a href="{{ route("orders.create") }}" class="btn">PROCEED TO CHECKOUT</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- cart-area-end -->

    </main>
    <!-- main-area-end -->
@endsection