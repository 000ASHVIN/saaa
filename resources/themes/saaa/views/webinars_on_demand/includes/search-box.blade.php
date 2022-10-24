<section class="search-box" style="padding: 10px;">
    <div class="row">
        <div class="col-md-12 search-text">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="text-center">
                    <br>
                    <div class="form-group @if ($errors->has('title')) has-error @endif">
                        {!! Form::input('text', 'title', (isset($title) ? $title : ""), ['class' => 'form-control','placeholder' => 'Search for content', 'style'=>'text-align:center','id' => 'title' ]) !!}
                        @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
                    <button onclick="spin(this)" style="color: #173175" class="btn-block btn btn-default"><i class="fa fa-search"></i> Search</button>

                    </div>  
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
</section>