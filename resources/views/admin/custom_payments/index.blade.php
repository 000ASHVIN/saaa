@extends('admin.layouts.master')

@section('title', 'Custom Payment Options')
@section('description', 'All Custom Payment Options')
@section('styles')
    <!-- Data Tables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">

    <style>
        .dt-buttons{
            margin-top: 10px;
        }
    </style>
@endsection

@section('content')
    <section class="container-fluid container-fullw bg-white ng-scope">
        <br>
        <div class="container">
            <div class="row">
                {!! Form::open() !!}
                <div class="form-group @if ($errors->has('list')) has-error @endif">
                    {!! Form::label('list', 'Select your list to export') !!}
                    {!! Form::select('list', [
                        'debit' => $debits.' Debit Orders',
                        'eft' => $eft.' EFT Payments',
                    ],null, ['class' => 'form-control']) !!}
                    @if ($errors->has('list')) <p class="help-block">{{ $errors->first('list') }}</p> @endif
                </div>

                {!! Form::submit('Export Data', ['class' => 'btn btn-primary' ]) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
    <script src="/js/app.js"></script>
@stop