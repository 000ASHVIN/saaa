@extends('admin.layouts.master')

@section('title', 'Edit Agent Group')
@section('description', 'Agent Group: Edit')

@section('content')
    <section>
        <br>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    {!! Form::model($agentGroup,['method' => 'POST']) !!}
                        @include('admin.agent_groups.partials.form', ['button' => 'Update'])
                    {!! Form::close() !!}
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript">
        $('select').select2();
    </script>
@stop