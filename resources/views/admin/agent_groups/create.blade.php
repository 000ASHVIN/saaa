@extends('admin.layouts.master')

@section('title', 'Agent Group')
@section('description', 'Agent Group: Create New')

@section('content')
    <section>
        <br>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    {!! Form::open(['method' => 'POST']) !!}
                        @include('admin.agent_groups.partials.form', ['button' => 'Create'])
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