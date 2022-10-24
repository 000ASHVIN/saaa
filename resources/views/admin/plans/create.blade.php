@extends('admin.layouts.master')

@section('title', 'Membership Plan')
@section('description', 'Create Membership Plan')

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::open(['method' => 'post']) !!}
                        @include('admin.plans.form', ['SubmitButton' => 'Create Plan'])
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

            $('#max_staff').hide();
            $('#pricing_group').hide();
            $(document).on('change','#is_practice',function(){
                 $value = $(this).val();
                 if($value == 1){
                    $('#max_staff').show();
                    $('#pricing_group').show();
                 }
                 else{
                    $('#max_staff').hide();
                    $('#pricing_group').hide();
                 }
            });

            // Show number of features on custom  plan checkbox
            $("#chk_custom_plan").on('change', function(){
                check_custom_plan();
            });

            function check_custom_plan() {
                if($("#chk_custom_plan").val()=='1') {
                    $("#max_no_of_features").show();
                }
                else {
                    $("#max_no_of_features").hide();
                    $("#max_no_of_features input").val('0');
                }
            }

        });
    </script> 

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript">
        $('select').select2();
    </script>
@stop