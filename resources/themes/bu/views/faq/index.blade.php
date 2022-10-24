@extends('app')

@section('content')

@section('title')
    Frequently asked questions (FAQ)
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('faq') !!}
@stop

<section>
    <div class="container">

        <div class="row mix-grid">
        <div class="col-md-9">
            <ul class="nav nav-pills mix-filter" style="text-transform: capitalize;">
                @if(count($tags))
                    @foreach($tags as $tag)
                        <li data-filter="{{ $tag->slug }}" class="filter"><a id="faqs_tags" href="#">{{ $tag->title }}</a></li>
                    @endforeach
                @else
                    <li data-filter="#" class="filter active"><a href="#">No Tags</a></li>
                @endif
            </ul>

            <div class="divider" style="margin: 0px"></div>
                @if(count($tags))
                    @foreach($tags as $tag)
                        @foreach($tag->questions as $question)
                            <div class="panel panel-default {{ $tag->salug }} mix">
                                <div class="panel-heading"><a href="{{ route('resource_centre.technical_faqs.general_show', $question->id) }}">{{ $question->question }}</a></div>
                            </div>
                        @endforeach
                    @endforeach
                @endif
        </div>

            <div class="col-md-3">
                <h4>Need some help ? </h4>
                <p>
                    Please send us an email on <a href="mailto: info@accountingacademy.co.za">info@accountingacademy.co.za</a>
                    or contact us on 010 593 0466 for immediate assistance
                </p>
            </div>
        </div>
    </div>
</section>
@endsection