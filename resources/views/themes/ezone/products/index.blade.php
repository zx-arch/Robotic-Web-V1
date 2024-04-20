@extends('themes.ezone.layout')

@section('content')
<img src="{{ url('/assets/img/logo/Absah-logo.png') }}" alt="Logo" style="display: block; margin: 40px auto 0; max-width: 150%; height: auto;">
	<div class="shop-page-wrapper shop-page-padding ptb-100">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-3">
					@include('themes.ezone.products.sidebar')
				</div>
				<div class="col-lg-9">
					<div class="shop-product-wrapper res-xl">
						<div class="shop-bar-area">
							<div class="shop-bar pb-60">
								<div class="shop-found-selector">
									<div class="shop-found">
										<p><span>{{ count($products) }}</span> Product Found of <span>{{ $products->total() }}</span></p>
									</div>
								</div>
								<div class="shop-filter-tab">
									<div class="shop-tab nav" role=tablist>
										<a class="active" href="#grid-sidebar3" data-toggle="tab" role="tab" aria-selected="false">
											<i class="ti-layout-grid4-alt"></i>
										</a>
									</div>
								</div>
							</div>
							<div class="shop-product-content tab-content">
								<div id="grid-sidebar3" class="tab-pane fade active show">
									@include('themes.ezone.products.grid_view')
								</div>
								<div id="grid-sidebar4" class="tab-pane fade">
									@include('themes.ezone.products.list_view')
								</div>
							</div>
						</div>
					</div>
					<div class="mt-50 text-center">
						{{ $products->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection