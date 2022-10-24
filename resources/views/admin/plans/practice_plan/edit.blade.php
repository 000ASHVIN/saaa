@extends('admin.layouts.master')

@section('title', 'Practice Plan Tabs')
@section('description', 'Edit Practice Plan Tabs')

@section('content')
    <section>
        <br>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::model($practice_plan_tab, ['method' => 'post']) !!}
                        @include('admin.plans.practice_plan.form', ['SubmitButton' => 'Update Tab'])
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
    <script type="text/javascript">
        $('.select2').select2({
            placeholder: "Please select",
        });
    </script>
@stop