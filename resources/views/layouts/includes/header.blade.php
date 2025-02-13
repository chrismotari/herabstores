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
                                    <a class="dropdown-item" href="index-2.html">USD - US Dollar</a>
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
                        @if(auth()->user()->is_admin)<li><a href="{{ route('dashboard') }}">Backend</a></li>@endif
                        <li><a href="about-us.html">About Us</a></li>
                        <li><a href="contact.html">Contact</a></li>
                        <li><a href="contact.html">FAQ</a></li>
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
                                <a href="tel:1234566789"><span>Call Us Now</span>+254 712 345 678</a>
                            </li>
                            <li class="header-user"><a href="{{ route('login') }}"><i class="flaticon-user"></i></a></li>
                            @if(auth()->user()->is_admin)<li class="header-user"><a href="{{ route('dashboard') }}"><i class="fa fa-cog"></i></a></li>@endif
                            <li class="header-wishlist">
                                <a href="#"><i class="flaticon-heart-shape-outline"></i></a>
                                <span class="item-count">0</span>
                            </li>
                            <li class="header-cart-action">
                                <div class="header-cart-wrap">
                                    <a href="cart.html"><i class="flaticon-shopping-basket"></i></a>
                                    <span class="item-count">2</span>
                                    <ul class="minicart">
                                        <li class="d-flex align-items-start">
                                            <div class="cart-img">
                                                <a href="shop-details.html"><img src="img/product/cart_p01.jpg" alt=""></a>
                                            </div>
                                            <div class="cart-content">
                                                <h4><a href="shop-details.html">Organic Farm Fresh Nuts</a></h4>
                                                <div class="cart-price">
                                                    <span class="new">Ksh 229.9</span>
                                                    <span><del>Ksh 229.9</del></span>
                                                </div>
                                            </div>
                                            <div class="del-icon">
                                                <a href="#"><i class="far fa-trash-alt"></i></a>
                                            </div>
                                        </li>
                                        <li class="d-flex align-items-start">
                                            <div class="cart-img">
                                                <a href="shop-details.html"><img src="img/product/cart_p02.jpg" alt=""></a>
                                            </div>
                                            <div class="cart-content">
                                                <h4><a href="shop-details.html">Organic Fresh Nuts Vanla Butter</a></h4>
                                                <div class="cart-price">
                                                    <span class="new">Ksh 229.9</span>
                                                    <span><del>Ksh 229.9</del></span>
                                                </div>
                                            </div>
                                            <div class="del-icon">
                                                <a href="#"><i class="far fa-trash-alt"></i></a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="total-price">
                                                <span class="f-left">Total:</span>
                                                <span class="f-right">Ksh 239.9</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="checkout-link">
                                                <a href="cart.html">Shopping Cart</a>
                                                <a class="black-color" href="checkout.html">Checkout</a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="cart-amount">Ksh 1000.00</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- header-search-area-end -->