<section>
    <div class="container">
        <div class="row">
            <div class="heading-title heading-dotted text-center">
                <h3>STORE PRODUCTS / <span>Listings</span></h3>
            </div>

                <div class=" shop-item owl-carousel owl-padding-10 buttons-autohide controlls-over" data-plugin-options='{"singleItem": false, "items":"5", "autoPlay": 4000, "navigation": true, "pagination": false}'>
                    @if(count($listings))
                        @foreach($listings as $listing)
                            <div class="shop-item">
                                <a class="shop-item-image" href="{{ route('store.show',$listing->id) }}">
                                    @if($listing->image_url)
                                        <img class=" thumbnail img-responsive"
                                             src="{{ $listing->image_url }}"
                                             alt="{{ $listing->title }}"/>
                                    @else
                                        <img class=" thumbnail img-responsive"
                                             src="/assets/frontend/images/shop/products/background.png"
                                             alt="shop first image"/>
                                    @endif
                                    <span class="image-title">
                                        <p>{{ $listing->title }}</p>
                                    </span>
                                </a>
                                <p class="text-left"><i class="fa fa-check"></i> Price From: {{ money_format('%.2n', $listing->from_price) }}</p>
                                @if($listing->discount === "0.00")
                                <p class="text-left"><i class="fa fa-check"></i> Discount Available: No</p>
                                @else
                                    <p class="text-left"><i class="fa fa-check"></i> Discount Available: Yes</p>
                                @endif
                                <p class="text-left"><i class="fa fa-check"></i> Products Available: {{ count($listing->products) }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>

            <div class="text-center">
                <a href="{{ route('store.index') }}" class="btn btn-primary">Show All Listings</a>
            </div>

        </div>
    </div>
</section>