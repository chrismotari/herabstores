<!-- header-top-start -->
<div class="header-top-wrap">
    <div class="container custom-container">
        <div class="row align-items-center">
            <div class="col-md-7">
                <div class="header-top-left">
                    <ul>

                        <li class="header-top-currency">
                            <div class="dropdown">
                                <button class="dropdown-toggle" type="button" id="dropdownMenuButton3" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">KSH - KE Shilling</button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                                    <a class="dropdown-item" href="#">USD - US Dollar</a>
                                </div>
                            </div>
                        </li>
                        <li class="header-work-time">
                            Working time: <span> Mon - Fri : 8:00 - 21:0</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-5">
                <div class="header-top-right">
                    <ul>
                        @if(auth()->user())<li><a href="{{ route('users.show', Auth::user()) }}">Hello, {{Auth::user()->name}}!</a></li>@endif
                        <li><a href="{{ route('about.us')}}">About Us</a></li>
                        <li><a href="{{ route('contact.us')}}">Contact Us</a></li>
                        <li><a href="{{ route('faq')}}"></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- header-top-end -->

<!-- header-search-area -->
<div class="header-search-area">
    <div class="container custom-container">
        <div class="row align-items-center">
            <div class="col-xl-2 col-lg-3 d-none d-lg-block">
                <div class="logo">
                    <a href="/"><img src="img/logo/logo.png" alt="Logo"></a>
                </div>
            </div>
            <div class="col-xl-10 col-lg-9">
                <div class="d-block d-sm-flex align-items-center justify-content-end">
                    <div class="header-search-wrap">
                        <form action="#">
                            <select class="custom-select">
                                <option selected="">All Categories</option>
                                <option>-- Grocery & Frozen</option>
                                <option>-- Fresh Fruits</option>
                                <option>-- Fresh Fish</option>
                                <option>-- Fresh Nuts</option>
                                <option>-- Fresh Meats</option>
                                <option>-- Bread & Bakery</option>
                                <option>-- Vegetable</option>
                                <option>-- Kids Food</option>
                                <option>-- Dried Fruits</option>
                                <option>-- Others Food</option>
                            </select>
                            <input type="text" placeholder="Search Product...">
                            <button><i class="flaticon-loupe-1"></i></button>
                        </form>
                    </div>
                    <div class="header-action">
                        <ul>
                            <li class="header-phone">
                                <div class="icon"><i class="flaticon-telephone"></i></div>
                                <a href="tel:+254789454242"><span>Call Us Now</span>+254 789 45 42 42</a>
                            </li>
                            <li class="header-cart-action">
                                <div class="header-cart-wrap">
                                    <a href="login"><i class="flaticon-user"></i></a>
                                    @auth
                                        <ul class="minicart">  
                                            <li class="d-flex align-items-start">
                                                <div class="cart-img">
                                                    <a href="{{route('users.show', auth()->user())}}"><img src="{{ asset('backend/images/user.png') }}" alt=""></a>
                                                </div>
                                                <div class="cart-content">
                                                    <h4><a href="{{route('users.show', auth()->user())}}">{{auth()->user()->name}}</a></h4>
                                                </div>
                                                
                                            </li>
                                            <li>
                                                <div class="checkout-link">
                                                
                                                    <a href=" {{ route('users.show', auth()->user()) }} ">My Profile</a>
                                                
                                                    <form id="logout_form" action="{{ route('logout') }}" method="POST">
                                                        @csrf
                                                        {{-- <a class="black-color" href="#">Logout</a> --}}
                                                        <a class="black-color" href="javascript:{}" onclick="document.getElementById('logout_form').submit();"> Logout <i class="far fa-sign-out"></i></a>
                                                    </form>
                                                </div>
                                            </li>
                                        </ul>
                                    @endauth
                                </div>
                            </li>
                            @if(auth()->user()?->is_admin)
                                <li class="header-user">
                                    <a href="{{ route('dashboard') }}"><i class="fa fa-cog"></i></a>
                                </li>
                            @endif
                            
                            <li class="header-wishlist">
                                <a href="#"><i class="flaticon-heart-shape-outline"></i></a>
                                <span class="item-count">{{ $favourites?->count() }}</span>
                            </li>
                            
                            <li class="header-cart-action">
                                <div class="header-cart-wrap">
                                    @if ($cart)
                                    <a href="{{ route('carts.show', $cart) }}"><i class="flaticon-shopping-basket"></i></a>
                                    <span class="item-count">{{ $cart->cartItems()->count() }}</span>
                                    @else
                                    <a href="#"><i class="flaticon-shopping-basket"></i></a>
                                    <span class="item-count">0</span>
                                    @endif
                                    <ul class="minicart">
                                        @forelse ($cart?->cartItems ? $cart->cartItems : [] as $cart_item)    
                                        <li class="d-flex align-items-start">
                                            <div class="cart-img">
                                                <a href="{{route('products.show', $cart_item->product)}}"><img src="{{ asset($cart_item->product->image_url) }}" alt=""></a>
                                            </div>
                                            <div class="cart-content">
                                                <h4><a href="{{route('products.show', $cart_item->product)}}">{{$cart_item->product->name}}</a></h4>
                                                <div class="cart-price">
                                                    <span class="new">KShs {{$cart_item->product->price}}</span>
                                                </div>
                                            </div>
                                            <div class="del-icon">
                                                <a href="#"><i class="far fa-trash-alt"></i></a>
                                            </div>
                                        </li>
                                        @empty
                                            Empty cart
                                        @endforelse
                                        <li>
                                            <div class="total-price">
                                                <span class="f-left">Total:</span>
                                                <span class="f-right">Ksh {{$cart?->total}}</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="checkout-link">
                                                @if ($cart)    
                                                    <a href=" {{ route('carts.show', $cart) }} ">Go to Cart</a>
                                                    <a class="black-color" href="#">Checkout</a>
                                                @endif
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="cart-amount">KSHS {{$cart?->total}}</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- header-search-area-end -->