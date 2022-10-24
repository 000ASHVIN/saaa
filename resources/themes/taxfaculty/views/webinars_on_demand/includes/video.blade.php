<div class="col-md-3 col-sm-6">
    <div class="card">
        <?php
            $category_image = $video->categoryImage();
            $image = "";
            if($category_image != '' && $category_image != null) {
                $image = asset('storage/'.$category_image);
            } elseif($video->cover != "" || $video->cover != null) {
                $image = $video->cover;
            } else {
                $image = asset('assets\themes\taxfaculty\img\No_Image_Available.jpg');
            }
        ?>
        
        <div class="wod_card_image">
            <div class="overlay_image">
                <img class="card-img-top" src="{{ $image }}" alt="">
            </div>
            <div class="overlay_play_icon" onclick="webinar_read_more('{{ route('webinars_on_demand.show', $video->slug) }}')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><path d="M838 162C746 71 633 25 500 25c-129 0-242 46-337 137C71 254 25 367 25 500s46 246 138 337c91 92 204 142 337 142s246-46 338-142c91-91 137-204 137-337s-46-246-137-338m-30 30c84 87 125 187 125 308s-41 225-125 308c-83 84-187 130-308 130s-221-42-304-130C113 725 67 621 67 500s41-221 129-308c83-84 187-130 304-130 121 0 221 46 308 130M438 392v250l204-125-204-125z" fill="#ffffff"/></svg>
            </div>
        </div>

        <div class="card-body">
          <h5 class="card-title">{{ trimString($video->title, 74) }}</h5>
          <div class="price-wrapper">
            <h5 style="margin-bottom:0px;">
                <i class="fa fa-plus"></i> {{ ($video->hours ? : 0) }} Hours |
                R{{ number_format($video->amount, 2, ".", "") }} 
                <!-- @if($video->categories){{ ucfirst($video->categories->title) }} | @endif -->
            </h5>
        </div>
          {{-- <p class="card-text">Price: R{{number_format($video->amount, 2, '.', '')}}</p> --}}
          <div class="read_more">
            <a href="{{ route('webinars_on_demand.show', $video->slug) }}" class="btn">Read More</a>
          </div>
          
          <div class="card_button">
              
            @if(auth()->user() && auth()->user()->webinars->contains($video->id))
                <div class="already_owned">
                    <a href="#" class="btn btn-warning disabled">Already Owned</a>
                </div>
            @else
                <a href="{{ route('webinars_on_demand.checkout', $video->slug) }}" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Buy Now</a>    
                @if($video->videoInCart())
                    <a href="#" class="btn btn-info"><i class="fa fa-shopping-cart"></i> Already In cart</a>
                @else
                    <a href="{{ route('webinars_on_demand.add-to-cart', $video->slug) }}" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Add To Cart</a>    
                @endif
            @endif
          </div>
        </div>
    </div>
</div>