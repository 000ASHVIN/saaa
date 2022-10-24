@extends('layouts.app')

@section('title')
    {{ $user->first_name }} {{ $user->last_name }}
@stop

@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')

            <div class="col-lg-9 col-md-9 col-sm-8">

                <div class="row">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias animi dolorem eveniet inventore laudantium odio pariatur praesentium reiciendis velit? Beatae commodi corporis dolore dolorum magni quae ratione repellendus veritatis voluptatem?</p>
                </div>

            </div>
        </div>
    </section>
@stop