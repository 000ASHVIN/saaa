@extends('admin.layouts.master')

@section('title', 'Course')
@section('description', 'Import Course')

@section('content')
    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            {!! Former::open_for_files()->method('POST')->route('admin.import.provider',2) !!}
            <div class="col-sm-12 col-md-6">
                {!! Former::text('title', 'Import Title') !!}
                {!! Former::select('plan_id','Please select your Course')->options($course)->select(0) !!}
                {!! Former::textarea('description','Import Description', null, ['rows' => '4']) !!}
                {!! Former::file('data','CSV File')->accept('csv') !!}
            </div>
            <!-- <div class="col-sm-12 col-md-6">
          

                <div class="checkbox clip-check check-primary">
                    <input type="checkbox" id="exiting_account" value="1" name="exiting_account">
                    <label for="exiting_account">
                        Exiting Account (This will allocate selected plan to existing user)
                    </label>
                </div>


            </div> -->

            <div class="col-md-12">
                <hr>
            </div>

            <div class="col-md-12">
                <button class="btn btn-primary btn-block" onclick="spin(this)"><i class="fa fa-upload"></i> Import Users</button>
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