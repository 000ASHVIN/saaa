@extends('app')

@section('content')

@section('title')
    Ask an Expert
@stop


@section('styles')
    <style>
        .desclaimer-section {
            font-size: 12px;
        }
    </style>
@endsection

@if(!(Carbon\Carbon::now()->startOfDay() >= Carbon\Carbon::parse('15 December 2021')->startOfDay() && Carbon\Carbon::now()->startOfDay() <= Carbon\Carbon::parse('2 January 2022')->endOfDay()))
@if(auth()->check() && auth()->user()->ViewResourceCenter())
    <section class="alternate">
        <div class="container">

            <div class="col-md-12">
                {!! Form::open(['method' => 'post', 'route' => 'support_ticket.store']) !!}
                <div class="form-group @if ($errors->has('subject')) has-error @endif">
                    {!! Form::label('subject', 'Ticket Subject') !!}
                    {!! Form::input('text', 'subject', null, ['class' => 'form-control']) !!}
                    @if ($errors->has('subject')) <p class="help-block">{{ $errors->first('subject') }}</p> @endif
                </div>

                <div class="form-group @if ($errors->has('tag')) has-error @endif">
                    {!! Form::label('tag', 'Ticket Category') !!}
                    {!! Form::select('tag', $categories->pluck('title', 'id'),null, ['class' => 'form-control']) !!}
                    @if ($errors->has('tag')) <p class="help-block">{{ $errors->first('tag') }}</p> @endif
                </div>

                <div class="form-group @if ($errors->has('description')) has-error @endif">
                    {!! Form::label('description', 'Ticket Description') !!}
                    {!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'ticket']) !!}
                    @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
                </div>

                <p class="desclaimer-section"><strong>Disclaimer: </strong>Although this Q&A functionality facilitates technical questioning by members and clients, complex matters requiring detailed information, investigation, and/or interpretation for purposes of conclusion, will be referred to a tax professional specialising in that area. Such professional will then be able to quote on the scope of the work to be performed. This Q&A functionality is therefore not designed to provide formal tax opinions in any way.</p>

                {!! Form::submit('Submit Ticket', ['class' => 'btn btn-primary']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@else
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="border-box text-center" style="background-color: #fbfbfb; min-height:300px">
                        <i class="fa fa-lock" style="font-size: 12vh"></i>
                        <p>You do not have access to this page. <br> The Technical Resource Centre is part of the Designation CPD Subscription Packages.</p>
                        <a class="btn btn-primary" href="{{ route('resource_centre.home') }}"><i class="fa fa-arrow-left"></i> Back</a>
                        <a class="btn btn-default" href="/subscription_plans"><i class="fa fa-arrow-right"></i> View Packages</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
@else
    <section>
        <div class="container">
            <div class="row">
                <div class="col-xs-12" style="text-align: center">
                    <img src="{{ asset('assets\themes\taxfaculty\img\Office_closure_2021(web).jpg') }}" alt="" style="max-width: 100%; max-height: 100vh;">
                </div>
            </div>
        </div>
    </section>
@endif


@endsection

@section('scripts')
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="/assets/frontend/plugins/editor.summernote/summernote.js"></script>
    <script>
        $('#ticket').summernote({
            height: 350,
            fontNames: ['Arial'],
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
            ]
        });
    </script>
@stop