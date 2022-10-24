@extends('app')

@section('content')
    <section style="background-image: url('/assets/frontend/images/demo/wall2.jpg');">
        <app-simple-registration-screen inline-template>
            <div id="app-register-screen" class="container-fluid">
                <!-- Basic Information -->
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        @include('auth.registration.simple.basic')
                    </div>
                </div>
            </div>
        </app-simple-registration-screen>
    </section>
@endsection


