@extends('layouts.app')
@section('content')
<main>

    <!-- breadcrumb-area -->
    {{-- <section class="breadcrumb-area breadcrumb-bg">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-content">
                        <h2 class="title">About Us</h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">About us</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <!-- breadcrumb-area-end -->

    <!-- ingredients-area -->
    <section class="ingredients-area pt-90 pb-90">
        <div class="container">
            <div class="ingredients-inner-wrap">
                <div class="row align-items-center">
                    <div class="col-7">
                        <div class="ingredients-img">
                            <img src="img/images/ingredients_img.jpg" alt="">
                            <div class="active-years">
                                <h2 class="title">99+ <span>Products</span></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="ingredients-content-wrap">
                            <div class="ingredients-section-title">
                                <span class="sub-title">HERAB GROCERIES</span>
                                <h2 class="title">Your Go-To Store for Fresh & Quality Groceries</h2>
                            </div>
                            <p>Herab Groceries is your ultimate destination for farm-fresh produce, premium nuts & grains, and flavorful herbs & spices. We are committed to bringing you the best quality groceries with convenience and affordability.

                                With a focus on freshness and sustainability, we partner with trusted farmers and suppliers to ensure that every product meets high standards. Whether you need fresh vegetables, wholesome grains, or aromatic spices, we’ve got you covered!.</p>
                            <div class="ingredients-fact">
                                <ul>
                                    <li>
                                        <div class="icon"><img src="img/icon/ing_icon01.png" alt=""></div>
                                        <div class="content">
                                            <h4>Best</h4>
                                            <span>Guarantee on Quality</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="icon"><img src="img/icon/ing_icon02.png" alt=""></div>
                                        <div class="content">
                                            <h4>Approach</h4>
                                            <span>Customer-first</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="ingredients-btn-wrap">
                                <a href="index" class="btn">Shop now</a>
                                <a href="index" class="store-link">our Super Store <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ingredients-area-end -->

    <!-- services-area -->
    <section class="services-area services-bg">
        <div class="container">
            <div class="container-inner-wrap">
                <div class="row justify-content-center">
                    <div class="col-xl-7 col-lg-9">
                        <div class="services-section-title text-center mb-55">
                            <h2 class="title">What We Provide?</h2>
                            <p>At Herab Groceries, we strive to make your grocery shopping experience seamless, convenient, and rewarding. Our platform is designed to provide top-quality fresh produce and essential grocery items while ensuring affordability and excellent service. Here’s what you can expect:</p>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-10">
                        <div class="services-item">
                            <a href="{{route('contact.us')}}" class="services-link"></a>
                            <div class="icon"><i class="flaticon-return"></i></div>
                            <div class="content">
                                <h5>Fresh & Organic Produce<span class="new">NEW</span></h5>
                                <p>Sourced directly from trusted local farmers to ensure quality and sustainability.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-10">
                        <div class="services-item">
                            <a href="{{route('contact.us')}}" class="services-link"></a>
                            <div class="icon"><i class="flaticon-delivery"></i></div>
                            <div class="content">
                                <h5>Fast Delivery</h5>
                                <p>Get your groceries delivered to your doorstep quickly and at no extra cost on eligible orders. Terms and conditions apply</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-10">
                        <div class="services-item">
                            <a href="{{route('contact.us')}}" class="services-link"></a>
                            <div class="icon"><i class="flaticon-like-1"></i></div>
                            <div class="content">
                                <h5>Exclusive Deals & Discounts</h5>
                                <p>Enjoy discounts and special promotions to make your grocery shopping more affordable.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-10">
                        <div class="services-item">
                            <a href="{{route('contact.us')}}" class="services-link"></a>
                            <div class="icon"><i class="flaticon-clutch-disc"></i></div>
                            <div class="content">
                                <h5>Automated Order Management </h5>
                                <p>Our system ensures efficient processing and tracking of your orders in real time</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-10">
                        <div class="services-item">
                            <a href="{{route('contact.us')}}" class="services-link"></a>
                            <div class="icon"><i class="flaticon-settings"></i></div>
                            <div class="content">
                                <h5>Secure Payment Options<span class="new">NEW</span></h5>
                                <p>Multiple payment methods with enhanced security for a safe and smooth checkout experience.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-10">
                        <div class="services-item">
                            <a href="{{route('contact.us')}}" class="services-link"></a>
                            <div class="icon"><i class="flaticon-online-service"></i></div>
                            <div class="content">
                                <h5>Wide Range of Products</h5>
                                <p>Explore a diverse selection of fresh fruits & vegetables, premium nuts & grains, and flavorful herbs & spices – all carefully sourced for quality and freshness!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- services-area-emd -->

    <!-- newsletter-area -->
    <section class="newsletter-area pt-90 pb-90">
        <div class="container">
            <div class="container-inner-wrap">
                <div class="row">
                    <div class="col-12">
                        <div class="newsletter-wrap">
                            <h2 class="title">Are you ready to get your <span>TASTE OF GREATNESS</span></h2>
                            <div class="newsletter-form">
                                <form action="{{route('contacts.store')}}" method="post">
                                    @csrf
                                    <input type="email" placeholder="Email address">
                                    <button typee="submit" class="btn">subscribe</button>
                                </form>
                            </div>
                            <img src="img/images/newsletter_shape01.png" alt="" class="newsletter-shape top-shape wow fadeInDownBig" data-wow-delay=".3s">
                            <img src="img/images/newsletter_shape02.png" alt="" class="newsletter-shape bottom-shape wow fadeInUpBig" data-wow-delay=".3s">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- newsletter-area-end -->

    <!-- online-support-area -->
    <section class="online-support-area">
        <div class="container">
            <div class="container-inner-wrap">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-5">
                        <div class="online-support-img">
                            <img src="img/images/support_img.png" alt="">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-7">
                        <div class="online-support-content">
                            <h2 class="title">Herab's Online Support</h2>
                            <p>At Herab Groceries, we prioritize customer satisfaction by providing reliable and responsive online support. Whether you need help with an order, have a question about a product, or require assistance with our services, our dedicated support team is here for you.</p>
                            <div class="support-info-wrap">
                                <ul>
                                    <li>
                                        <div class="support-info-item">
                                            <p>24/7 Customer Support</p>
                                            <h2><i class="flaticon-24-hour-service"></i> 24/7</h2>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="support-info-item">
                                            <p>Personalized Service</p>
                                            <h2><i class="flaticon-happy-1"></i> Yay! </h2>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- online-support-area-end -->

</main>
@endsection