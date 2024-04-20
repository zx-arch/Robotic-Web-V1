@extends('themes.ezone.layout')

@section('content')
    <div class="shop-page-wrapper ptb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="shop-sidebar">
                        <div class="sidebar-widget mb-40">
                            <h3 class="sidebar-title">Filter by Price</h3>
                            <div class="price_filter">
                                <div id="slider-range"></div>
                                <div class="price_slider_amount">
                                    <div class="label-input">
                                        <label>price : </label>
                                        <input type="text" id="amount" name="price" placeholder="Add Your Price" />
                                    </div>
                                    <button type="button">Filter</button>
                                </div>
                            </div>
                        </div>
                        <div class="sidebar-widget mb-45">
                            <h3 class="sidebar-title">Categories</h3>
                            <div class="sidebar-categories">
                                <ul>
                                    <p>category</p>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="shop-product-wrapper">
                        <!-- Product Bar -->
                        <div class="shop-bar-area">
                            <div class="shop-bar pb-60">
                                <div class="shop-found-selector">
                                    <div class="shop-found">
                                        <p><span>1</span> Product Found of <span>50</span></p>
                                    </div>
                                    <div class="shop-selector">
                                        <label>Sort By : </label>
                                        <select name="select">
                                            <option value="">Default</option>
                                            <option value="">A to Z</option>
                                            <option value="">Z to A</option>
                                            <option value="">In stock</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="shop-filter-tab">
                                    <div class="shop-tab nav" role="tablist">
                                        <a href="#grid-sidebar9" data-toggle="tab" role="tab" aria-selected="false">
                                            <i class="ti-layout-grid4-alt"></i>
                                        </a>
                                        <a class="active" href="#grid-sidebar10" data-toggle="tab" role="tab"
                                            aria-selected="true">
                                            <i class="ti-menu"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="product-list-area pt-30 pb-50">
                                <div class="ps-container">
                                    <div class="row">
                                        {{-- @if ($products->isNotEmpty())
                                            @foreach ($products as $product) --}}
                                        <div class="col-lg-12">
                                            <div
                                                class="product-wrapper mb-30 single-product-list product-list-right-pr mb-60">
                                                <div class="product-img list-img-width">
                                                    <a href="#">
                                                        {{-- @if ($product->path)
                                                                    <img src="{{ asset('images/' . $product->path) }}"
                                                                        alt="{{ $product->name }}">
                                                                @else
                                                                    <p>Product Belum Tersedia</p>
                                                                @endif --}}
                                                    </a>
                                                </div>
                                                <div class="product-content-list">
                                                    <div class="product-list-info">
                                                        <h4><a href="#">name</a>
                                                        </h4>
                                                        <span>Rp. 9999</span>
                                                        <p>deskripsi</p>
                                                    </div>
                                                    <div class="product-list-cart-wishlist">
                                                        <div class="product-list-cart">
                                                            <a class="btn-hover list-btn-style"
                                                                href="{{ route('cart.detail') }}">Add to cart</a>
                                                        </div>
                                                        <div class="product-list-wishlist">
                                                            <a class="btn-hover list-btn-wishlist"
                                                                href="{{ route('favorites.index') }}">
                                                                <i class="pe-7s-like"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pagination-style mt-10 text-center">
                        <ul>
                            <li><a href="#"><i class="ti-angle-left"></i></a></li>
                            <li class="active"><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">...</a></li>
                            <li><a href="#">19</a></li>
                            <li><a href="#"><i class="ti-angle-right"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
