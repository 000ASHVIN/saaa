<section class="callout-dark heading-title heading-arrow-bottom" style="padding: 10px;">
    <div class="container">

        <div class="col-md-12">
            <div class="col-md-6 col-md-offset-3">
                <div class="text-center">
                    <br>
                    {!! Form::open(['method' => 'post', 'route' => 'resource_centre.search']) !!}
                    <div class="form-group @if ($errors->has('search')) has-error @endif">
                        {!! Form::input('text', 'search', null, ['class' => 'form-control', 'placeholder' => 'Search for content', 'style' => 'text-align:center']) !!}
                        @if ($errors->has('search')) <p class="help-block">{{ $errors->first('search') }}</p> @endif
                    </div>
                    <button onclick="spin(this)" style="color: #800000" class="btn-block btn btn-default"><i class="fa fa-search"></i> Search</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

    </div>
</section>