<div class="col-md-6 col-xl-4">
	<div class="product-wrapper mb-30">
		<div class="product-img">
			<a href="{{ url('product/'. $product->slug) }}">
				@if ($product->productImages->first())
				<img src="{{ asset('storage') . '/' . $product->productImages->first()->path }}" alt="{{ $product->name }}">
				@else
					<p>No image available</p>
				@endif
			</a>
			<span>hot</span>
			<div class="product-action">
				<a class="animate-left add-to-fav" title="Favorite"  product-slug="{{ $product->slug }}" href="">
					<i class="pe-7s-like"></i>
				</a>
				<a class="animate-right add-to-card" title="Add To Cart" href="" product-id="{{ $product->id }}" product-type="{{ $product->type }}" product-slug="{{ $product->slug }}">
					<i class="pe-7s-cart"></i>
				</a>
				{{-- <a class="animate-right quick-view" title="Quick View" product-slug="{{ $product->slug }}" href="">
					<i class="pe-7s-look"></i>
				</a> --}}
			</div>
		</div>
		<div class="product-content">
			<h4><a href="{{ url('product/'. $product->slug) }}">{{ $product->name }}</a></h4>
			<span>{{ number_format($product->priceLabel()) }}</span>
		</div>
	</div>
</div>