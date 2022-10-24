@extends('admin.layouts.master')

@section('title', 'Mark Commission')
@section('description', 'This will mark the given invoices as Commission Claimed')

@section('content')
    <section class="container-fluid container-fullw bg-white ng-scope">
        <br>
        <div class="container">
            <div class="panel-group" id="accordion">
                <div class="col-md-6 col-md-offset-3">



                    <div class="panel panel-default">
                        <div class="panel-body">
                            {!! Form::open(['method' => 'Post', 'route' => 'admin.reports.payments.claim_invoices_post', 'files' => true]) !!}

                            <div class="form-group @if ($errors->has('file')) has-error @endif">
                                {!! Form::label('file', 'Please upload your .xlsx file') !!}
                                <span class="pull-right"><a href="https://www.dropbox.com/s/yyj8vonz89yvd1b/template-comm-claimed.xlsx?dl=1"><i class="fa fa-download"></i> Download Template</a></span>
                                {!! Form::file('file',['class' => 'form-control']) !!}
                                @if ($errors->has('file')) <p class="help-block">{{ $errors->first('file') }}</p> @endif
                            </div>

                            <div class="alert alert-success text-center">
                                Only (.xlsx) files will be processed with 1 column only <br> that contains reference field
                            </div>

                            <hr>

                            <div class="form-group">
                                <div class="clip-radio radio-primary">
                                    <input type="radio" id="yes" name="com_claimed" value="yes">
                                    <label for="yes">Mark as claimed</label>
                                    <input type="radio" id="no" name="com_claimed" value="no">
                                    <label for="no">Mark as un-claimed</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-o btn-primary" onclick="spin(this)" ;>
                                <i class="fa fa-check"></i> Submit File
                            </button>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>

    <script type="text/javascript">
        $('.select2').select2();
    </script>

    <script>
        function spin(this1) {
            this1.closest("form").submit();
            this1.disabled = true;
            this1.innerHTML = `<i class="fa fa-spinner fa-spin"></i> Working..`;
        }
    </script>
@stop