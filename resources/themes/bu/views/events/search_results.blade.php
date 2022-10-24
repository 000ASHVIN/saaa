@extends('app')

@section('title')
    Seminars / Webinars
@stop

@section('intro')
    Search Results
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('events') !!}
@stop

@section('styles')
    <link href="/assets/frontend/css/layout-datatables.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <section class="alternate" style="background-image: url('/assets/frontend/images/demo/wall2.jpg');">
        <div class="container">
            @include('events.includes.search_form')

            <div class="row">
                <div class="col-md-12">
                    @if(count($events))
                        @foreach($events as $event)
                            @include('events.includes.event_single_block')
                        @endforeach
                    @else
                        <div class="event-container-box clearfix">
                            <h4>No Events was found</h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script>
        function search_spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Searching..`;
        }
    </script>
@endsection