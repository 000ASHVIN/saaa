@extends('app')

@section('content')
    <section>
        <div class="container">
            <div class="row">
                {!! Form::open(['method' => 'post', 'route' => 'testingapi']) !!}
                <div class="form-group">
                    <div class="form-group">
                        {!! Form::label('email', 'Email') !!}
                        {!! Form::input('text', 'email', null, ['class' => 'form-control']) !!}
                    </div>
                    {!! Form::submit('submit', ['class' => 'btn btn-default']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@endsection