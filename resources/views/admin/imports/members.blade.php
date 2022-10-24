@extends('admin.layouts.master')

@section('title', 'Members')
@section('description', 'Import New Members')

@section('content')
    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            {!! Former::open_for_files()->method('POST')->route('admin.import.provider',[$importProvider->id]) !!}
            <div class="col-sm-12 col-md-6">
                {!! Former::text('title', 'Import Title') !!}
                {!! Former::textarea('description','Import Description', null, ['rows' => '4']) !!}
                {!! Former::file('data','CSV File')->accept('csv') !!}
            </div>
            <div class="col-sm-12 col-md-6">
                {!! Former::select('plan_id','Please select your plan')->options($planOptions)->select(0) !!}

                <div class="checkbox clip-check check-primary">
                    <input type="checkbox" id="generate_temp_passwords" value="1" name="generate_temp_passwords">
                    <label for="generate_temp_passwords">
                        Generate temporary passwords
                    </label>
                </div>

{{--                <div class="checkbox clip-check check-primary">--}}
{{--                    <input type="checkbox" id="update_existing_members" value="1" name="update_existing_members">--}}
{{--                    <label for="update_existing_members">--}}
{{--                        Update existing members (<small style="color: red; font-weight: bold">This will effect current CPD subscribers if they do have plans.</small>)--}}
{{--                    </label>--}}
{{--                </div>--}}

                <div class="checkbox clip-check check-primary">
                    <input type="checkbox" id="free_subscription" value="1" name="free_subscription">
                    <label for="free_subscription">
                        Free Subscription (<small style="color: red; font-weight: bold">Enable billing for subscription</small>)
                    </label>
                </div>

                <div class="checkbox clip-check check-primary">
                    <input type="checkbox" id="exiting_account" value="1" name="exiting_account">
                    <label for="exiting_account">
                        Exiting Account (This will allocate selected plan to existing user)
                    </label>
                </div>


                {!! Former::select('mailer_id','Mailer to send')->options($mailerOptions)->select(0) !!}
            </div>

            <div class="col-md-12">
                <hr>
            </div>

            <div class="col-md-12">
                <button class="btn btn-primary btn-block" onclick="spin(this)"><i class="fa fa-upload"></i> Import Members</button>
            </div>
            {!! Former::close() !!}
        </div>
    </div>

@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });

        function spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
        }
    </script>
@stop