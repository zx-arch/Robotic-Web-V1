@extends('themes.ezone.layout')

@section('content')
    <img src="{{ url('/assets/img/logo/Absah-logo.png') }}" alt="Logo"
        style="display: block; margin: 40px auto 0; max-width: 150%; height: auto;">
    <div class="product-details ptb-100 pb-90">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-7 col-12">
                    <div class="product-details-img-content">
                        <div class="product-details-tab mr-70">
                            <div class="product-details-large tab-content">
                                @php
                                    $i = 1;
                                @endphp
                                @forelse ($product->productImages as $image)
                                    <div class="tab-pane fade {{ $i == 1 ? 'active show' : '' }}"
                                        id="pro-details{{ $i }}" role="tabpanel">
                                        <div class="easyzoom easyzoom--overlay">
                                            @if ($image->large && $image->extra_large)
                                                <a href="{{ asset('storage/' . $image->extra_large) }}">
                                                    <img src="{{ asset('storage/' . $image->large) }}"
                                                        alt="{{ $product->name }}">
                                                </a>
                                            @else
                                                <a href="{{ asset('themes/ezone/assets/img/product-details/bl1.jpg') }}">
                                                    <img src="{{ asset('themes/ezone/assets/img/product-details/l1.jpg') }}"
                                                        alt="{{ $product->name }}">
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    @php
                                        $i++;
                                    @endphp
                                @empty
                                    No image found!
                                @endforelse
                            </div>
                            <div class="product-details-small nav mt-12" role=tablist>
                                @php
                                    $i = 1;
                                @endphp
                                @forelse ($product->productImages as $image)
                                    <a class="{{ $i == 1 ? 'active' : '' }} mr-12" href="#pro-details{{ $i }}"
                                        data-toggle="tab" role="tabpanel" aria-selected="true">
                                        @if ($image->small)
                                            <img src="{{ asset('storage/' . $image->small) }}" alt="{{ $product->name }}">
                                        @else
                                            <img src="{{ asset('themes/ezone/assets/img/product-details/s1.jpg') }}"
                                                alt="{{ $product->name }}">
                                        @endif
                                    </a>
                                    @php
                                        $i++;
                                    @endphp
                                @empty
                                    No image found!
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-5 col-12">
                    <div class="product-details-content">
                        <h3>{{ $product->name }}</h3>
                        <div class="details-price">
                            <span>Rp. {{ number_format($product->priceLabel()) }}</span>
                        </div>
                        <p>{{ $product->short_description }}</p>
                        {!! Form::open(['url' => route('cart.store', ['product' => $product->id])]) !!}
                        {{ Form::hidden('product_id', $product->id) }}
                        @if ($product->type == 'configurable')
                            <div class="quick-view-select">
                                <div class="select-option-part">
                                    <label>Size*</label>
                                    {!! Form::select('size', $sizes, null, [
                                        'class' => 'select',
                                        'placeholder' => '- Please Select -',
                                        'required' => true,
                                    ]) !!}
                                </div>
                                <div class="select-option-part">
                                    <label>Color*</label>
                                    {!! Form::select('color', $colors, null, [
                                        'class' => 'select',
                                        'placeholder' => '- Please Select -',
                                        'required' => true,
                                    ]) !!}
                                </div>
                            </div>
                        @endif
                        <div class="quickview-plus-minus">
                            <div class="quickview-plus-minus">
                                {!! Form::number('qty', 1, ['class' => 'cart-plus-minus', 'placeholder' => 'qty']) !!}
                            </div>
                            <div class="quickview-btn-cart">
                                <button type="submit" class="submit contact-btn btn-hover">Add to Cart</button>
                            </div>
                            <div class="quickview-btn-wishlist">
                                <a class="btn-hover" typehref="{{Route('favorites.store')}}"><i class="pe-7s-like"></i></a>
                            </div>
                        </div>

                        {!! Form::close() !!}
                        <div class="container-fluid">
                            <div class="product-share" style="margin-top:20%">
                                <ul>
                                    <li class="categories-title">Share :</li>
                                    <li>
                                        <a href="#">
                                            <i class="icofont icofont-social-facebook"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="icofont icofont-social-twitter"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="icofont icofont-social-pinterest"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="icofont icofont-social-flikr"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="product-description-review-area pb-90">
        <div class="container">
            <div class="product-description-review text-center">
                <div class="description-review-title nav" role=tablist>
                    <a class="active" href="#pro-dec" data-toggle="tab" role="tabpanel" aria-selected="true">
                        Description
                    </a>
                    <a href="#pro-review" data-toggle="tab" role="tabpanel" aria-selected="false">
                        Reviews (0)
                    </a>
                </div>
                <div class="description-review-text tab-content">
                    <div class="tab-pane active show fade" id="pro-dec" role="tabpanel">
                        <p>{{ $product->description }} </p>
                    </div>
                    <div class="tab-pane fade" id="pro-review" role="tabpanel">
                        <a href="#">Be the first to write your review!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
