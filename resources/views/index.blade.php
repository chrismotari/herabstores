@extends('layouts.app')
@section('content')
<!-- slider-area -->
<section class="slider-area" data-background="img/bg/slider_area_bg.jpg">
    <div class="container custom-container">
        <div class="row">
            <div class="col-7">
                <div class="slider-active">
                    <div class="single-slider slider-bg" data-background="img/slider/slider_bg01.jpg">
                        <div class="slider-content">
                            <h5 class="sub-title" data-animation="fadeInUp" data-delay=".2s">top deal !</h5>
                            <h2 class="title" data-animation="fadeInUp" data-delay=".4s">organic food</h2>
                            <p data-animation="fadeInUp" data-delay=".6s">Get up to 50% OFF Today Only</p>
                            <a href="shop.html" class="btn rounded-btn" data-animation="fadeInUp" data-delay=".8s">Shop Now</a>
                        </div>
                    </div>
                    <div class="single-slider slider-bg" data-background="img/slider/slider_bg01.jpg">
                        <div class="slider-content">
                            <h5 class="sub-title" data-animation="fadeInUp" data-delay=".2s">Real simple !</h5>
                            <h2 class="title" data-animation="fadeInUp" data-delay=".4s">Time Grocery</h2>
                            <p data-animation="fadeInUp" data-delay=".6s">Get up to 50% OFF Today Only</p>
                            <a href="shop.html" class="btn rounded-btn" data-animation="fadeInUp" data-delay=".8s">Shop Now</a>
                        </div>
                    </div>
                    <div class="single-slider slider-bg" data-background="img/slider/slider_bg01.jpg">
                        <div class="slider-content">
                            <h5 class="sub-title" data-animation="fadeInUp" data-delay=".2s">top deal !</h5>
                            <h2 class="title" data-animation="fadeInUp" data-delay=".4s">organic food</h2>
                            <p data-animation="fadeInUp" data-delay=".6s">Get up to 50% OFF Today Only</p>
                            <a href="shop.html" class="btn rounded-btn" data-animation="fadeInUp" data-delay=".8s">Shop Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="slider-banner-img mb-20">
                    <a href="shop.html"><img src="img/slider/slider_banner01.jpg" alt=""></a>
                </div>
                <div class="slider-banner-img">
                    <a href="shop.html"><img src="img/slider/slider_banner02.jpg" alt=""></a>
                </div>
            </div>
            <div class="col-3">
                <div class="slider-banner-img">
                    <a href="shop.html"><img src="img/slider/slider_banner03.jpg" alt=""></a>
                </div>
            </div>
        </div>
    </div>

    <!-- category-area -->
    <div class="container custom-container">
        <div class="slider-category-wrap">
            <div class="row category-active">
                <div class="col-lg-2">
                    <div class="category-item active">
                        <a href="shop.html" class="category-link"></a>
                        <div class="category-thumb">
                            <img src="img/product/category_img01.png" alt="">
                        </div>
                        <div class="category-content">
                            <h6 class="title">Juice & Drinks</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="category-item">
                        <a href="shop.html" class="category-link"></a>
                        <div class="category-thumb">
                            <img src="img/product/category_img02.png" alt="">
                        </div>
                        <div class="category-content">
                            <h6 class="title">Vegetables</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="category-item">
                        <a href="shop.html" class="category-link"></a>
                        <div class="category-thumb">
                            <img src="img/product/category_img03.png" alt="">
                        </div>
                        <div class="category-content">
                            <h6 class="title">Fresh Nuts</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="category-item">
                        <a href="shop.html" class="category-link"></a>
                        <div class="category-thumb">
                            <img src="img/product/category_img04.png" alt="">
                        </div>
                        <div class="category-content">
                            <h6 class="title">Cleaning</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="category-item">
                        <a href="shop.html" class="category-link"></a>
                        <div class="category-thumb">
                            <img src="img/product/category_img05.png" alt="">
                        </div>
                        <div class="category-content">
                            <h6 class="title">fresh meat</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="category-item">
                        <a href="shop.html" class="category-link"></a>
                        <div class="category-thumb">
                            <img src="img/product/category_img06.png" alt="">
                        </div>
                        <div class="category-content">
                            <h6 class="title">Powders</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- category-area-end -->

</section>
<!-- slider-area-end -->

<!-- discount-area -->
<section class="discount-area pt-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-4 col-lg-6 col-md-8">
                <div class="discount-item mb-20">
                    <div class="discount-thumb">
                        <img src="img/product/discount_img01.jpg" alt="">
                    </div>
                    <div class="discount-content">
                        <span>healthy food</span>
                        <h4 class="title"><a href="shop.html">100 organic UP TO 35%</a></h4>
                        <a href="shop.html" class="btn">shop now</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-8">
                <div class="discount-item mb-20">
                    <div class="discount-thumb">
                        <img src="img/product/discount_img02.jpg" alt="">
                    </div>
                    <div class="discount-content">
                        <span>healthy food</span>
                        <h4 class="title"><a href="shop.html">Hygienically Packed</a></h4>
                        <a href="shop.html" class="btn">shop now</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-8">
                <div class="discount-item style-two mb-20">
                    <div class="discount-thumb">
                        <img src="img/product/discount_img03.jpg" alt="">
                    </div>
                    <div class="discount-content">
                        <span>healthy food</span>
                        <h4 class="title"><a href="shop.html">baby favorite UP TO 15%</a></h4>
                        <a href="shop.html" class="btn">shop now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- discount-area-end -->

<!-- best-deal-area -->
<section class="best-deal-area pt-60 pb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-9">
                <div class="best-deal-top-wrap">
                    <div class="bd-section-title">
                        <h3 class="title">Best Deals <span>of this Week!</span></h3>
                        <p>A virtual assistant collects the products from your list</p>
                    </div>
                    <div class="coming-time" data-countdown="2021/10/20"></div>
                </div>
            </div>
        </div>
        <div class="row best-deal-active">
            <div class="col-xl-3">
                <div class="best-deal-item">
                    <div class="best-deal-thumb">
                        <a href="{{ route('products.show', $products->first()) }}"><img src="img/product/best_deal_product01.png" alt=""></a>
                    </div>
                    <div class="best-deal-content">
                        <div class="main-content">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <h4 class="title"><a href="{{ route('products.show', $products->first()) }}">Walnuts Max</a></h4>
                            <p> Ksh 100.50/ kg</p>
                        </div>
                        <div class="icon"><a href="{{ route('products.show', $products->first()) }}">+</a></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="best-deal-item">
                    <div class="best-deal-thumb">
                        <a href="{{ route('products.show', $products->first()) }}"><img src="img/product/best_deal_product02.png" alt=""></a>
                    </div>
                    <div class="best-deal-content">
                        <div class="main-content">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <h4 class="title"><a href="{{ route('products.show', $products->first()) }}">Fresh Nuts</a></h4>
                            <p> Ksh 300.50/ kg</p>
                        </div>
                        <div class="icon"><a href="{{ route('products.show', $products->first()) }}">+</a></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="best-deal-item">
                    <div class="best-deal-thumb">
                        <a href="{{ route('products.show', $products->first()) }}"><img src="img/product/best_deal_product03.png" alt=""></a>
                    </div>
                    <div class="best-deal-content">
                        <div class="main-content">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <h4 class="title"><a href="{{ route('products.show', $products->first()) }}">Fresh Butter</a></h4>
                            <p> Ksh 500.50/ kg</p>
                        </div>
                        <div class="icon"><a href="{{ route('products.show', $products->first()) }}">+</a></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="best-deal-item">
                    <div class="best-deal-thumb">
                        <a href="{{ route('products.show', $products->first()) }}"><img src="img/product/best_deal_product04.png" alt=""></a>
                    </div>
                    <div class="best-deal-content">
                        <div class="main-content">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <h4 class="title"><a href="{{ route('products.show', $products->first()) }}">Mat Orange</a></h4>
                            <p> Ksh 900.00/ kg</p>
                        </div>
                        <div class="icon"><a href="{{ route('products.show', $products->first()) }}">+</a></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="best-deal-item">
                    <div class="best-deal-thumb">
                        <a href="{{ route('products.show', $products->first()) }}"><img src="img/product/best_deal_product05.png" alt=""></a>
                    </div>
                    <div class="best-deal-content">
                        <div class="main-content">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <h4 class="title"><a href="{{ route('products.show', $products->first()) }}">France Potato</a></h4>
                            <p> Ksh 300.99/ kg</p>
                        </div>
                        <div class="icon"><a href="{{ route('products.show', $products->first()) }}">+</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- best-deal-area-end -->

<!-- special-products-area -->
<section class="special-products-area gray-bg pt-75 pb-60">
    <div class="container">
        <div class="row align-items-end mb-50">
            <div class="col-md-8 col-sm-9">
                <div class="section-title">
                    <span class="sub-title">Awesome Shop</span>
                    <h2 class="title">Our Special Products</h2>
                </div>
            </div>
            <div class="col-md-4 col-sm-3">
                <div class="section-btn text-left text-md-right">
                    <a href="shop.html" class="btn">View All</a>
                </div>
            </div>
        </div>
        <div class="special-products-wrap">
            <div class="row">
                <div class="col-3 d-none d-lg-block">
                    <div class="special-products-add">
                        <div class="sp-add-thumb">
                            <img src="img/product/special_products_add.jpg" alt="">
                        </div>
                        <div class="sp-add-content">
                            <span class="sub-title">healthy food</span>
                            <h4 class="title">baby favorite <b>Product</b></h4>
                            <p>Super Offer TO 50% OFF</p>
                            <a href="shop.html" class="btn rounded-btn">shop now</a>
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <div class="row justify-content-center">
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <div class="sp-product-item mb-20">
                                <div class="sp-product-thumb">
                                    <span class="batch">New</span>
                                    <a href="{{ route('products.show', $products->first()) }}"><img src="img/product/sp_products01.png" alt=""></a>
                                </div>
                                <div class="sp-product-content">
                                    <div class="rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h6 class="title"><a href="{{ route('products.show', $products->first()) }}">Uncle Bens Vanla Ready Pice</a></h6>
                                    <span class="product-status">IN Stock</span>
                                    <div class="sp-cart-wrap">
                                        <form action="#">
                                            <div class="cart-plus-minus">
                                                <input type="text" value="1">
                                            </div>
                                        </form>
                                    </div>
                                    <p> Ksh 100.50 - 1 kg</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <div class="sp-product-item mb-20">
                                <div class="sp-product-thumb">
                                    <span class="batch discount">15%</span>
                                    <a href="{{ route('products.show', $products->first()) }}"><img src="img/product/sp_products02.png" alt=""></a>
                                </div>
                                <div class="sp-product-content">
                                    <div class="rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h6 class="title"><a href="{{ route('products.show', $products->first()) }}">Dannon Max Vanla ice cream</a></h6>
                                    <span class="product-status">IN Stock</span>
                                    <div class="sp-cart-wrap">
                                        <form action="#">
                                            <div class="cart-plus-minus">
                                                <input type="text" value="1">
                                            </div>
                                        </form>
                                    </div>
                                    <p> Ksh 300.50 - 1 lt</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <div class="sp-product-item mb-20">
                                <div class="sp-product-thumb">
                                    <span class="batch discount">25%</span>
                                    <a href="{{ route('products.show', $products->first()) }}"><img src="img/product/sp_products03.png" alt=""></a>
                                </div>
                                <div class="sp-product-content">
                                    <div class="rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h6 class="title"><a href="{{ route('products.show', $products->first()) }}">Walnuts Max Vanla Greek Pice</a></h6>
                                    <span class="product-status">IN Stock</span>
                                    <div class="sp-cart-wrap">
                                        <form action="#">
                                            <div class="cart-plus-minus">
                                                <input type="text" value="1">
                                            </div>
                                        </form>
                                    </div>
                                    <p> Ksh 200.99 - 1 kg</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <div class="sp-product-item mb-20">
                                <div class="sp-product-thumb">
                                    <span class="batch">new</span>
                                    <a href="{{ route('products.show', $products->first()) }}"><img src="img/product/sp_products04.png" alt=""></a>
                                </div>
                                <div class="sp-product-content">
                                    <div class="rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h6 class="title"><a href="{{ route('products.show', $products->first()) }}">Brachs Bens Vanla Ready Pice</a></h6>
                                    <span class="product-status">IN Stock</span>
                                    <div class="sp-cart-wrap">
                                        <form action="#">
                                            <div class="cart-plus-minus">
                                                <input type="text" value="1">
                                            </div>
                                        </form>
                                    </div>
                                    <p> Ksh 200.99 - 1 kg</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <div class="sp-product-item mb-20">
                                <div class="sp-product-thumb">
                                    <span class="batch discount">25%</span>
                                    <a href="{{ route('products.show', $products->first()) }}"><img src="img/product/sp_products05.png" alt=""></a>
                                </div>
                                <div class="sp-product-content">
                                    <div class="rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h6 class="title"><a href="{{ route('products.show', $products->first()) }}">Black Lady Vanla Greek Grapes</a></h6>
                                    <span class="product-status">IN Stock</span>
                                    <div class="sp-cart-wrap">
                                        <form action="#">
                                            <div class="cart-plus-minus">
                                                <input type="text" value="1">
                                            </div>
                                        </form>
                                    </div>
                                    <p> Ksh 500.99 - 1 kg</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <div class="sp-product-item mb-20">
                                <div class="sp-product-thumb">
                                    <span class="batch">new</span>
                                    <a href="{{ route('products.show', $products->first()) }}"><img src="img/product/sp_products06.png" alt=""></a>
                                </div>
                                <div class="sp-product-content">
                                    <div class="rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h6 class="title"><a href="{{ route('products.show', $products->first()) }}">Potato Max Vanla Greek Pice</a></h6>
                                    <span class="product-status">IN Stock</span>
                                    <div class="sp-cart-wrap">
                                        <form action="#">
                                            <div class="cart-plus-minus">
                                                <input type="text" value="1">
                                            </div>
                                        </form>
                                    </div>
                                    <p> Ksh 000.99 - 1 kg</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <div class="sp-product-item mb-20">
                                <div class="sp-product-thumb">
                                    <span class="batch discount">10%</span>
                                    <a href="{{ route('products.show', $products->first()) }}"><img src="img/product/sp_products07.png" alt=""></a>
                                </div>
                                <div class="sp-product-content">
                                    <div class="rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h6 class="title"><a href="{{ route('products.show', $products->first()) }}">Uncle Bens Vanla Ready Pice</a></h6>
                                    <span class="product-status">IN Stock</span>
                                    <div class="sp-cart-wrap">
                                        <form action="#">
                                            <div class="cart-plus-minus">
                                                <input type="text" value="1">
                                            </div>
                                        </form>
                                    </div>
                                    <p> Ksh 100.99 - 1 kg</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <div class="sp-product-item mb-20">
                                <div class="sp-product-thumb">
                                    <span class="batch discount">10%</span>
                                    <a href="{{ route('products.show', $products->first()) }}"><img src="img/product/sp_products08.png" alt=""></a>
                                </div>
                                <div class="sp-product-content">
                                    <div class="rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h6 class="title"><a href="{{ route('products.show', $products->first()) }}">Organic Rice Max Greek Pice</a></h6>
                                    <span class="product-status">IN Stock</span>
                                    <div class="sp-cart-wrap">
                                        <form action="#">
                                            <div class="cart-plus-minus">
                                                <input type="text" value="1">
                                            </div>
                                        </form>
                                    </div>
                                    <p> Ksh 300.99 - 1 kg</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- special-products-area-end -->

<!-- coupon-area -->
<div class="coupon-area gray-bg pb-80">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="coupon-bg">
                    <div class="coupon-title">
                        <span>Use coupon Code</span>
                        <h3 class="title">Get Ksh  300 Discount Code</h3>
                    </div>
                    <div class="coupon-code-wrap">
                        <h5 class="code">Herabs123</h5>
                        <img src="img/images/coupon_code.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- coupon-area-end -->

<!-- best-sellers-area -->
<section class="best-sellers-area pt-75">
    <div class="container">
        <div class="row align-items-end mb-50">
            <div class="col-md-8 col-sm-9">
                <div class="section-title">
                    <span class="sub-title">Best Sellers</span>
                    <h2 class="title">Best Offers View</h2>
                </div>
            </div>
            <div class="col-md-4 col-sm-3">
                <div class="section-btn text-left text-md-right">
                    <a href="shop.html" class="btn">View All</a>
                </div>
            </div>
        </div>
        <div class="best-sellers-products">
            <div class="row justify-content-center">
                <div class="col-3">
                    <div class="sp-product-item mb-20">
                        <div class="sp-product-thumb">
                            <span class="batch">New</span>
                            <a href="{{ route('products.show', $products->first()) }}"><img src="img/product/sp_products09.png" alt=""></a>
                        </div>
                        <div class="sp-product-content">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <h6 class="title"><a href="{{ route('products.show', $products->first()) }}">Uncle Orange Vanla Ready Pice</a></h6>
                            <span class="product-status">IN Stock</span>
                            <div class="sp-cart-wrap">
                                <form action="#">
                                    <div class="cart-plus-minus">
                                        <input type="text" value="1">
                                    </div>
                                </form>
                            </div>
                            <p> Ksh 100.50 - 1 kg</p>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="sp-product-item mb-20">
                        <div class="sp-product-thumb">
                            <span class="batch discount">15%</span>
                            <a href="{{ route('products.show', $products->first()) }}"><img src="img/product/sp_products02.png" alt=""></a>
                        </div>
                        <div class="sp-product-content">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <h6 class="title"><a href="{{ route('products.show', $products->first()) }}">Dannon Max Vanla ice cream</a></h6>
                            <span class="product-status">IN Stock</span>
                            <div class="sp-cart-wrap">
                                <form action="#">
                                    <div class="cart-plus-minus">
                                        <input type="text" value="1">
                                    </div>
                                </form>
                            </div>
                            <p> Ksh 300.50 - 1 lt</p>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="sp-product-item mb-20">
                        <div class="sp-product-thumb">
                            <span class="batch discount">25%</span>
                            <a href="{{ route('products.show', $products->first()) }}"><img src="img/product/sp_products03.png" alt=""></a>
                        </div>
                        <div class="sp-product-content">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <h6 class="title"><a href="{{ route('products.show', $products->first()) }}">Walnuts Max Vanla Greek Pice</a></h6>
                            <span class="product-status">IN Stock</span>
                            <div class="sp-cart-wrap">
                                <form action="#">
                                    <div class="cart-plus-minus">
                                        <input type="text" value="1">
                                    </div>
                                </form>
                            </div>
                            <p> Ksh 200.99 - 1 kg</p>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="sp-product-item mb-20">
                        <div class="sp-product-thumb">
                            <span class="batch">new</span>
                            <a href="{{ route('products.show', $products->first()) }}"><img src="img/product/sp_products04.png" alt=""></a>
                        </div>
                        <div class="sp-product-content">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <h6 class="title"><a href="{{ route('products.show', $products->first()) }}">Brachs Bens Vanla Ready Pice</a></h6>
                            <span class="product-status">IN Stock</span>
                            <div class="sp-cart-wrap">
                                <form action="#">
                                    <div class="cart-plus-minus">
                                        <input type="text" value="1">
                                    </div>
                                </form>
                            </div>
                            <p> Ksh 200.99 - 1 kg</p>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="sp-product-item mb-20">
                        <div class="sp-product-thumb">
                            <span class="batch discount">25%</span>
                            <a href="{{ route('products.show', $products->first()) }}"><img src="img/product/sp_products05.png" alt=""></a>
                        </div>
                        <div class="sp-product-content">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <h6 class="title"><a href="{{ route('products.show', $products->first()) }}">Black Lady Vanla Greek Grapes</a></h6>
                            <span class="product-status">IN Stock</span>
                            <div class="sp-cart-wrap">
                                <form action="#">
                                    <div class="cart-plus-minus">
                                        <input type="text" value="1">
                                    </div>
                                </form>
                            </div>
                            <p> Ksh 500.99 - 1 kg</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- best-sellers-area-end -->

<!-- discount-area -->
<section class="discount-style-two pt-60 pb-50">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="discount-item-two">
                    <div class="discount-thumb">
                        <img src="img/product/s_discount_img01.jpg" alt="">
                    </div>
                    <div class="discount-content">
                        <span>healthy food</span>
                        <h4 class="title"><a href="shop.html">organic farm for ganic</a></h4>
                        <p>Super Offer TO 50% OFF</p>
                        <a href="shop.html" class="btn rounded-btn">shop now</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="discount-item-two">
                    <div class="discount-thumb">
                        <img src="img/product/s_discount_img02.jpg" alt="">
                    </div>
                    <div class="discount-content">
                        <span>healthy food</span>
                        <h4 class="title"><a href="shop.html">BABY FAVORITE UP TO 15%</a></h4>
                        <p>Super Offer TO 50% OFF</p>
                        <a href="shop.html" class="btn rounded-btn">shop now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- discount-area-end -->

@endsection