<div class="col-lg-3 col-md-3 col-sm-3 col-lg-pull-9 col-md-pull-9 col-sm-pull-9">
    <div class="side-nav margin-bottom-30">
        <div class="side-nav-head">
            <button class="fa fa-bars"></button>
            <h4>Shop Categories</h4>
        </div>

        <ul class="list-group list-group-bordered list-group-noicon uppercase">
            @foreach($categories as $category)
                <li class="list-group-item">
                    <a href="{{ route('store.search',$category->id) }}">
                        <span class="size-11 text-muted pull-right"></span>
                        {{ $category->title }}
                    </a>
                </li>
            @endforeach
            {{--<li class="list-group-item"><a href="#"><span class="size-11 text-muted pull-right">(1)</span> Course Notes</a></li>--}}
        </ul>
    </div>

    <div class="margin-bottom-40"></div>
    {!! Form::open(['method' => 'POST', 'route' => 'store.search']) !!}

    <div class="form-group">
        {!! Form::label('keyword','Keywords') !!}
        {!! Form::input('text','search', null, ['class' => 'form-control', 'placeholder' => 'Search for products']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('category','Please select category') !!}
        {!! Form::select('category', [0 => 'All categories'] + $categories->keyBy('id')->pluck('title','id')->toArray(),null, ['class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        {!! Form::submit('Search', ['class' => 'btn btn-primary, btn-block']) !!}
    </div>
    {!! Form::close() !!}

    <hr>

    <div class="margin-bottom-60">
        @include('partials.newsletter_form')
    </div>
</div>