<div class="shop-sidebar mr-50">
    <form method="GET" action="{{ url('products') }}">
        <div class="sidebar-widget mb-40">
            <h3 class="sidebar-title">Search by Price</h3>
            <div class="price_filter">
                <div id="slider-range"></div>
                <div class="price_slider_amount">
                    <div class="label-input">
                        <label>Price : </label>
                        <input type="text" id="amount" name="price" placeholder="Type your range price"
                            style="width:170px" />
                        <input type="hidden" id="productMinPrice" value="{{ $minPrice }}" />
                        <input type="hidden" id="productMaxPrice" value="{{ $maxPrice }}" />
                    </div>
                    <button type="submit">Filter</button>
                </div>
            </div>
        </div>
    </form>

    @if ($categories)
        <div class="sidebar-widget mb-45">
            <h3 class="sidebar-title">Categories</h3>
            <div class="sidebar-categories">
                <ul>
                    @foreach ($categories as $category)
                        <li>
                            <a href="{{ url('products?category=' . $category->slug) }}"
                                class="{{ request('category') === $category->slug ? 'active-category' : '' }}">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
</div>
