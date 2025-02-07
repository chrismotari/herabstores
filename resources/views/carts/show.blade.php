
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
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item"><a href="index.html">Pages</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Our Blog</li>
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
                                        <tr>
                                            <td class="product-thumbnail"><a href="shop-details.html"><img src="img/product/cart_img01.jpg" alt=""></a></td>
                                            <td class="product-name">
                                                <h4><a href="shop-details.html">Orange Bens Vanla</a></h4>
                                            </td>
                                            <td class="product-price">$ 27.00</td>
                                            <td class="product-quantity">
                                                <div class="cart--plus--minus">
                                                    <form action="#" class="num-block">
                                                        <input type="text" class="in-num" value="1" readonly="">
                                                        <div class="qtybutton-box">
                                                            <span class="plus"><i class="fas fa-angle-up"></i></span>
                                                            <span class="minus dis"><i class="fas fa-angle-down"></i></span>
                                                        </div>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="product-subtotal"><span>$ 27.99</span></td>
                                            <td class="product-delete"><a href="#"><i class="far fa-trash-alt"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td class="product-thumbnail"><a href="shop-details.html"><img src="img/product/cart_img02.jpg" alt=""></a></td>
                                            <td class="product-name">
                                                <h4><a href="shop-details.html">Dannon Capcicam</a></h4>
                                            </td>
                                            <td class="product-price">$ 40.00</td>
                                            <td class="product-quantity">
                                                <div class="cart--plus--minus">
                                                    <form action="#" class="num-block">
                                                        <input type="text" class="in-num" value="1" readonly="">
                                                        <div class="qtybutton-box">
                                                            <span class="plus"><i class="fas fa-angle-up"></i></span>
                                                            <span class="minus dis"><i class="fas fa-angle-down"></i></span>
                                                        </div>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="product-subtotal"><span>$ 40.99</span></td>
                                            <td class="product-delete"><a href="#"><i class="far fa-trash-alt"></i></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="shop-cart-bottom">
                            <div class="cart-coupon">
                                <form action="#">
                                    <input type="text" placeholder="Enter Coupon Code...">
                                    <button class="btn">Apply Coupon</button>
                                </form>
                            </div>
                            <div class="continue-shopping">
                                <a href="shop.html" class="btn">update Cart</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-12">
                        <div class="shop-cart-total">
                            <h3 class="title">Cart Totals</h3>
                            <div class="shop-cart-widget">
                                <form action="#">
                                    <ul>
                                        <li class="sub-total"><span>Subtotal</span> $ 136.00</li>
                                        <li>
                                            <span>Shipping</span>
                                            <div class="shop-check-wrap">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                    <label class="custom-control-label" for="customCheck1">Free Shipping</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck2">
                                                    <label class="custom-control-label" for="customCheck2">LOCAL PICKUP: $5.00</label>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="cart-total-amount"><span>Total Price</span> <span class="amount">$ 151.00</span></li>
                                    </ul>
                                    <a href="checkout.html" class="btn">PROCEED TO CHECKOUT</a>
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