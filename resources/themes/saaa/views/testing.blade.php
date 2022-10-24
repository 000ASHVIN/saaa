@extends('app')

@section('content')

@section('title')
    Signup Page
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('test_drive') !!}
@stop

<section class="alternate" style="background-image: url('/assets/frontend/images/demo/wall2.jpg');">
    <div id="registration_page">
        <div class="container">
            <div class="row">
               <div class="">
                   <p>@{{ message }}</p>
                   <input v-model="message">
               </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
    <script>
        new Vue({
            el: "#registration_page",
            data (){
                return{
                    message: 'Hello Vue!'
                }
            }
        })
    </script>

    <script>
        $().ready(function() {
            $('#idno').rsa_id_validator({
                displayAge: [false,""],
                displayValid_id: "valid",
                displayDate_id: "#",
                displayAge_id: "#",
                displayGender_id: "#",
                displayCitizenship_id: "#"
            });
        });
    </script>
@endsection