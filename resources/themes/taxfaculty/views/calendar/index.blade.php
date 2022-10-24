@extends('app')

@section('title')
    Events Calendar {{Date('Y')}} 
@stop

@section('intro')
    View our latest and upcoming events
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('events') !!}
@stop

@section('styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
    <style>
        p, pre, ul, ol, dl, dd, blockquote, address, table, fieldset, form {
            margin-bottom: 0px;
        }
        .fc-day-grid-event>.fc-content {
            /*white-space: normal!important;*/
        }
    </style>
@endsection


@section('content')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    {!! $calendar->calendar() !!}
                </div>
                <div class="col-md-3">
                    <div class="border-box" style="background-color: #fafafa; border: 1px solid #eee;">
                        <h5>Colour Code Guide</h5>
                        <hr>
                        <span style="background-color: #173175; padding: 0px 10px; border-radius: 50%; margin-right: 12px;"></span><strong>Seminar</strong>
                        <br>
                        <span style="background-color: green; padding: 0px 10px; border-radius: 50%; margin-right: 12px;"></span><strong>Online Webinar</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
    {!! $calendar->script() !!}
@stop