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
                                    <li class="breadcrumb-item"><a href="{{route('shop')}}">Shop</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">All Products</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- breadcrumb-area-end -->

        <!-- shop-area -->
        <section class="shop--area pt-90 pb-90">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-3 order-2 order-lg-0">
                        <aside class="shop-sidebar">
                            <div class="widget shop-widget">
                                <div class="shop-widget-title">
                                    <h6 class="title">Product Categories</h6>
                                </div>
                                <div class="shop-cat-list">
                                    <ul>
                                        <li><a href="{{route('shop')}}">Accessories <span>+</span></a></li>
                                        <li><a href="{{route('shop')}}">Vegetables <span>+</span></a></li>
                                        <li><a href="{{route('shop')}}">Spices Food <span>+</span></a></li>
                                        <li><a href="{{route('shop')}}">Dairy <span>+</span></a></li>
                                        <li><a href="{{route('shop')}}">Baby Food <span>+</span></a></li>
                                        <li><a href="{{route('shop')}}">Kitchen Accessories <span>+</span></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="widget shop-widget">
                                <div class="shop-widget-title">
                                    <h6 class="title">Filter By Price</h6>
                                </div>
                                <div class="price_filter">
                                    <div id="slider-range"></div>
                                    <div class="price_slider_amount">
                                        <span>Price :</span>
                                        <input type="text" id="amount" name="price" placeholder="Add Your Price" />
                                    </div>
                                </div>
                            </div>
                            <div class="widget shop-widget">
                                <div class="shop-widget-title">
                                    <h6 class="title">NEW PRODUCT</h6>
                                </div>
                                {{-- <div class="sidebar-product-list">
                                    <ul>
                                        <li>
                                            <div class="sidebar-product-thumb">
                                                <a href="shop"><img src="img/product/sidebar_product01.jpg" alt=""></a>
                                            </div>
                                            <div class="sidebar-product-content">
                                                <div class="rating">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                </div>
                                                <h5><a href="shop">Uncle Bens Vanla</a></h5>
                                                <span>$39.00</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sidebar-product-thumb">
                                                <a href="shop"><img src="img/product/sidebar_product02.jpg" alt=""></a>
                                            </div>
                                            <div class="sidebar-product-content">
                                                <div class="rating">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                </div>
                                                <h5><a href="shop">Dannon Max</a></h5>
                                                <span>$29.00</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sidebar-product-thumb">
                                                <a href="shop"><img src="img/product/sidebar_product03.jpg" alt=""></a>
                                            </div>
                                            <div class="sidebar-product-content">
                                                <div class="rating">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                </div>
                                                <h5><a href="shop">Vanla Greek Pice</a></h5>
                                                <span>$35.00</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div> --}}
                            </div>
                            {{-- <div class="widget shop-widget">
                                <div class="shop-widget-title">
                                    <h6 class="title">BRANDS</h6>
                                </div>
                                <div class="shop-cat-list">
                                    <ul>
                                        <li><a href="{{route('shop')}}">Adara <span>+</span></a></li>
                                        <li><a href="{{route('shop')}}">Carnation <span>+</span></a></li>
                                        <li><a href="{{route('shop')}}">We Beyond <span>+</span></a></li>
                                        <li><a href="{{route('shop')}}">Agrifram <span>+</span></a></li>
                                    </ul>
                                </div>
                            </div> --}}
                            <div class="widget">
                                <div class="shop-widget-banner text-center">
                                    <a href="{{route('shop')}}"><img src="img/product/sidebar_shop_ad.jpg" alt=""></a>
                                </div>
                            </div>
                        </aside>
                    </div>
                    <div class="col-9">
                        <div class="shop-discount-area">
                            <div class="discount-content shop-discount-content">
                                <span>healthy food</span>
                                <h4 class="title"><a href="{{route('shop')}}">organic farm for ganic</a></h4>
                                <p>Super Offer TO 50% OFF</p>
                                <a href="{{route('shop')}}" class="btn rounded-btn">shop now</a>
                            </div>
                        </div>
                        <div class="shop-top-meta mb-30">
                            <div class="row">
                                <div class="col-md-6 col-sm-7">
                                    <div class="shop-top-left">
                                        <ul>
                                            <li><a href="#"><i class="fas fa-bars"></i> FILTER</a></li>
                                            <li>Showing 1–9 of 80 results</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-5">
                                    <div class="shop-top-right">
                                        <form action="#">
                                            <select name="select">
                                                <option value="">Sort by newness</option>
                                                <option>Free Shipping</option>
                                                <option>Best Match</option>
                                                <option>Newest Item</option>
                                                <option>Size A - Z</option>
                                            </select>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="shop-products-wrap">
                            <div class="row justify-content-center">
                              @forelse ($products as $product)   
                              <div class="col-xl-3 col-md-4 col-sm-6">
                                  <div class="sp-product-item">
                                      <div class="sp-product-thumb">
                                          {{-- <span class="batch discount">25%</span> --}}
                                          <a href="{{route('products.show', $product)}}"><img src="{{ Str::startsWith($product->image_url, ['http://', 'https://']) ? $product->image_url : asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}"></a>
                                      </div>
                                      <div class="sp-product-content">
                                          <div class="rating">
                                              <i class="fas fa-star"></i>
                                              <i class="fas fa-star"></i>
                                              <i class="fas fa-star"></i>
                                              <i class="fas fa-star"></i>
                                              <i class="fas fa-star"></i>
                                          </div>
                                          <h6 class="title"><a href="{{route('products.show', $product)}}">{{$product->name}}</a></h6>
                                          <span class="product-status">{{$product->is_in_stock}}</span>
                                          <form action="{{route('cart_items.store')}}" method="POST">
                                            <div class="sp-cart-wrap">
                                                  @csrf
                                                  <input type="hidden" name="product_id" value="{{$product->id}}">
                                                  <div class="cart-plus-minus">
                                                      <input type="text" value="1" name="quantity" class="cart-plus-minus-box">
                                                  </div>
                                                  
                                               
                                                <p>KSHS {{$product->price}}</p>
                                                <div class="add-to-cart mt-3">
                                                    <button type="submit" class="bg-orange rounded btn-secondary">Add to cart</button>
                                                    @if (Auth::user() && Auth::user()->is_admin)
                                                    <a href="{{route('products.edit', $product)}}" class=" rounded btn-sm" ><i class="fas fa-cog"></i> </a>
                                                    @endif 
                                                </div>
                                            </form>
                                        </div>
                                      </div>
                                  </div>
                              </div>
                              @empty
                                  No products found
                              @endforelse
                            </div>
                        </div>
                        {{-- <div class="pagination-wrap">
                            <ul>
                                <li class="prev"><a href="{{route('shop')}}">Prev</a></li>
                                <li><a href="{{route('shop')}}">1</a></li>
                                <li class="active"><a href="{{route('shop')}}">2</a></li>
                                <li><a href="{{route('shop')}}">3</a></li>
                                <li><a href="{{route('shop')}}">4</a></li>
                                <li><a href="{{route('shop')}}">...</a></li>
                                <li><a href="{{route('shop')}}">10</a></li>
                                <li class="next"><a href="{{route('shop')}}">Next</a></li>
                            </ul>
                        </div> --}}
                    </div>
                </div>
            </div>
        </section>
        <!-- shop-area-end -->

    </main>
    <!-- main-area-end -->
@endsection