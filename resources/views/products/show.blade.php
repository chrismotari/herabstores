@extends('layouts.app')
@section('content')
<!-- breadcrumb-area -->
<div class="breadcrumb-area breadcrumb-bg-two">
    <div class="container custom-container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('shop')}}">Shop</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Shop Details</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumb-area-end -->

<!-- shop-details-area -->
<section class="shop-details-area pt-90 pb-90">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="shop-details-flex-wrap">
                    <div class="shop-details-nav-wrap">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="item-one-tab" data-toggle="tab"
                                    href="#item-one" role="tab" aria-controls="item-one" aria-selected="true">
                                    <img src="{{ Str::startsWith($product->image_url, ['http://', 'https://']) ? $product->image_url : asset('storage/' . $product->image_url) }}"
                                        alt="{{$product->slug}}"
                                    >
                                </a>
                            </li>
                            @forelse ($product_images = $product->images as $product_image)
                                @if ($product->image_url == $product_image->image_url)
                                    @continue
                                @endif
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="item-{{ $loop->index }}-tab" data-toggle="tab"
                                        href="#item-{{ $loop->index }}" role="tab" aria-controls="item-{{ $loop->index }}" aria-selected="true">
                                        <img src="{{ Str::startsWith($product_image->image_url, ['http://', 'https://']) ? $product_image->image_url : asset('storage/' . $product_image->image_url) }}"
                                            alt="{{$product->slug}}"
                                        >
                                    </a>
                                </li>
                            @empty

                            @endforelse
                            {{-- <li class="nav-item" role="presentation">
                                <a class="nav-link" id="item-two-tab" data-toggle="tab" href="#item-two" role="tab" aria-controls="item-two" aria-selected="false"><img src="img/product/sd_nav_img02.jpg" alt=""></a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="item-three-tab" data-toggle="tab" href="#item-three" role="tab" aria-controls="item-three" aria-selected="false"><img src="img/product/sd_nav_img03.jpg" alt=""></a>
                            </li> --}}
                        </ul>
                    </div>
                    <div class="shop-details-img-wrap">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="item-one" role="tabpanel" aria-labelledby="item-one-tab">
                                <div class="shop-details-img">
                                    <img src="{{ Str::startsWith($product->image_url, ['http://', 'https://']) ? $product->image_url : asset('storage/' . $product->image_url) }}" alt="$product->slug">
                                </div>
                            </div>

                            @forelse ($product_images as $product_image)
                                <div class="tab-pane fade" id="item-{{ $loop->index }}" role="tabpanel" aria-labelledby="item-{{ $loop->index }}-tab">
                                    <div class="shop-details-img">
                                        <img src="{{ Str::startsWith($product_image->image_url, ['http://', 'https://']) ? $product_image->image_url : asset('storage/' . $product_image->image_url) }}" alt="$product->slug">
                                    </div>
                                </div>
                            @empty

                            @endforelse
                            {{-- <div class="tab-pane fade" id="item-three" role="tabpanel" aria-labelledby="item-three-tab">
                                <div class="shop-details-img">
                                    <img src="{{ asset('storage/' . $product->image_url) }}" alt="no image">
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="shop-details-content">
                    <h4 class="title">{{$product->name}}</h4>
                    <div class="shop-details-meta">
                        <ul>
                            <li>Baands : <a href="{{route('shop')}}">Herab</a></li>
                            <li class="shop-details-review">
                                <div class="rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span>Review</span>
                            </li>
                            <li>ID : <span>QZX8VGH</span></li>
                        </ul>
                    </div>
                    <div class="shop-details-price">
                        <h2 class="price"> Ksh {{$product->price}}</h2>
                        <h5 class="stock-status">- {{$product->is_in_stock}}</h5>
                    </div>
                    <p>{{$product->description}}</p>
                    <div class="shop-details-list">
                        <ul>
                            <li>Category : <span>{{$product->category->name}}</span></li>
                        </ul>
                    </div>
                    <form action="{{ route('cart_items.store') }}" method="POST">
                        @csrf
                        <div class="shop-perched-info">
                            <div class="sd-cart-wrap">
                                
                                    <div class="cart-plus-minus">
                                        <input type="text" value="1" name="quantity">
                                    </div>
                                    
                                </div>
                                <input type="text" hidden value="{{$product->id}}" name="product_id">
                            <button type="submit" class="btn">add to cart</button>
                        </div>
                    </form>
                    <div class="shop-details-bottom">
                        @if ($product->is_Favourite)
                            <form action="{{ route('products.unfavourite', $product ) }}" method="POST">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-secondary bg-light text-danger"><i class="fas fa-heart"></i> Remove from Wish List</button>
                            </form>
                        @else  
                            <form action="{{ route('favourites.store') }}" method="POST">                              
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-secondary bg-light text-primary"><i class="far fa-heart"></i> Add to Wish List</button>
                            </form>
                        @endif
                        <ul>
                            <li>
                                <span>Tag : </span>
                                <a href="#">Green</a>
                            </li>
                            <li>
                                <span>CATEGORIES :</span>
                                <a href="#">farm,</a>
                                <a href="#">food,</a>
                                <a href="#">organic,</a>
                                <a href="#">green</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="product-desc-wrap">
                    <ul class="nav nav-tabs" id="myTabTwo" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="details-tab" data-toggle="tab" href="#details" role="tab"
                                aria-controls="details" aria-selected="true">Product Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="val-tab" data-toggle="tab" href="#val" role="tab" aria-controls="val"
                                aria-selected="false">Viewers Also Like</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="review-tab" data-toggle="tab" href="#review" role="tab"
                                aria-controls="review" aria-selected="false">Product Reviews</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContentTwo">
                        <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                            <div class="product-desc-content">
                                <h4 class="title">Product Details</h4>
                                <div class="row">
                                    <div class="col-xl-3 col-md-5">
                                        {{-- <div class="product-desc-img">
                                            <img src="img/product/desc_img.jpg" alt="">
                                        </div> --}}
                                    </div>
                                    <div class="col-xl-9 col-md-7">
                                        <h5 class="small-title">100% Natural Vitamin</h5>
                                        <p>{{$product->description}}</p>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="val" role="tabpanel" aria-labelledby="val-tab">
                            <div class="product-desc-content">
                                <h4 class="title">Product Details</h4>
                                <div class="row">
                                    <div class="col-xl-3 col-md-5">
                                        {{-- <div class="product-desc-img">
                                            <img src="img/product/desc_img.jpg" alt="">
                                        </div> --}}
                                    </div>
                                    {{-- <div class="col-xl-9 col-md-7">
                                        <h5 class="small-title">100% Natural Vitamin</h5>
                                        <p>Cramond Leopard & Pythong Print Anorak Jacket In Beige but also the leap into electronic typesetting, remaining Lorem
                                            Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy
                                            text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                                        <ul class="product-desc-list">
                                            <li>65% poly, 35% rayon</li>
                                            <li>Hand wash cold</li>
                                            <li>Partially lined</li>
                                            <li>Hidden front button closure with keyhole accents</li>
                                            <li>Button cuff sleeves</li>
                                            <li>Lightweight semi-sheer fabrication</li>
                                            <li>Made in USA</li>
                                        </ul> 
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
                            <div class="product-desc-content">
                                <h4 class="title">Product Details</h4>
                                {{-- <div class="row">
                                    <div class="col-xl-3 col-md-5">
                                        <div class="product-desc-img">
                                            <img src="img/product/desc_img.jpg" alt="">
                                        </div>
                                    </div>
                                    <div class="col-xl-9 col-md-7">
                                        <h5 class="small-title">100% Natural Vitamin</h5>
                                        <p>Cramond Leopard & Pythong Print Anorak Jacket In Beige but also the leap into electronic typesetting, remaining Lorem
                                            Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy
                                            text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                                        <ul class="product-desc-list">
                                            <li>65% poly, 35% rayon</li>
                                            <li>Hand wash cold</li>
                                            <li>Partially lined</li>
                                            <li>Hidden front button closure with keyhole accents</li>
                                            <li>Button cuff sleeves</li>
                                            <li>Lightweight semi-sheer fabrication</li>
                                            <li>Made in USA</li>
                                        </ul>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- shop-details-area-end -->

<!-- coupon-area -->
<div class="coupon-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="coupon-bg">
                    <div class="coupon-title">
                        <span>Use coupon Code</span>
                        <h3 class="title">Get Ksh 300 Discount Code</h3>
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
{{-- <section class="best-sellers-area pt-85 pb-70">
    <div class="container">
        <div class="row align-items-end mb-40">
            <div class="col-md-8 col-sm-9">
                <div class="section-title">
                    <span class="sub-title">Related Products</span>
                    <h2 class="title">From this Collection</h2>
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
                            <a href="shop-details.html"><img src="img/product/sp_products09.png" alt=""></a>
                        </div>
                        <div class="sp-product-content">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <h6 class="title"><a href="shop-details.html">Uncle Orange Vanla Ready Pice</a></h6>
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
                            <a href="shop-details.html"><img src="img/product/sp_products02.png" alt=""></a>
                        </div>
                        <div class="sp-product-content">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <h6 class="title"><a href="shop-details.html">Dannon Max Vanla ice cream</a></h6>
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
                            <a href="shop-details.html"><img src="img/product/sp_products03.png" alt=""></a>
                        </div>
                        <div class="sp-product-content">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <h6 class="title"><a href="shop-details.html">Walnuts Max Vanla Greek Pice</a></h6>
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
                            <a href="shop-details.html"><img src="img/product/sp_products04.png" alt=""></a>
                        </div>
                        <div class="sp-product-content">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <h6 class="title"><a href="shop-details.html">Brachs Bens Vanla Ready Pice</a></h6>
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
                            <a href="shop-details.html"><img src="img/product/sp_products05.png" alt=""></a>
                        </div>
                        <div class="sp-product-content">
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <h6 class="title"><a href="shop-details.html">Black Lady Vanla Greek Grapes</a></h6>
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
</section> --}}
<!-- best-sellers-area-end -->

</main>
<!-- main-area-end -->
@endsection