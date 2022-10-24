@extends('admin.layouts.master')

@section('title', 'Imports')
@section('description', 'Import Claimed / Refunded Invoices')

@section('content')
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            {!! Form::open(['method' => 'Post', 'files' => true, 'route' => 'claimed_invoices_import']) !!}
               <div class="col-md-6">

                   <div class="form-group @if ($errors->has('type')) has-error @endif">
                       {!! Form::label('type', 'Please Select Type') !!}
                       {!! Form::select('type', [
                        'claim' => 'Mark Invoices as Claimed',
                        'refund' => 'Mark Invoices as Refunded'
                       ],null, ['class' => 'form-control']) !!}
                       @if ($errors->has('type')) <p class="help-block">{{ $errors->first('type') }}</p> @endif
                   </div>

                   <div class="form-group @if ($errors->has('claimed')) has-error @endif">
                       {!! Form::label('claimed', 'Mark Invoices') !!}
                       <span class="pull-right"><small><i>If selected type is "Mark Invoices as Claimed"</i></small></span>
                       {!! Form::select('claimed', [
                            null => 'Please Select',
                            true => 'Mark As Claimed',
                            false => 'Mark as Not Claimed',
                       ],null, ['class' => 'form-control']) !!}
                       @if ($errors->has('claimed')) <p class="help-block">{{ $errors->first('claimed') }}</p> @endif
                   </div>
                   <hr>
                   <div class="form-group @if ($errors->has('file')) has-error @endif">
                       {!! Form::label('file', 'Please upload your excel email address file') !!}
                       {!! Form::file('file',['class' => 'form-control']) !!}
                       @if ($errors->has('file')) <p class="help-block">{{ $errors->first('file') }}</p> @endif
                   </div>
                   <hr>
                   <div class="form-group">
                       <button class="btn btn-primary" onclick="spin(this1)">Mark Invoices</button>
                   </div>
               </div>
            {!! Form::close() !!}
        </div>
    </section>
@stop

@section('scripts')
    <script src="/assets/admin/assets/js/index.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
            Index.init();
        });
    </script>
@stop