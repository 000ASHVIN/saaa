@extends('admin.layouts.master')

@section('title', 'Ticket')
@section('description', 'Edit Ticket'. ' ' .$event->name)

@section('content')
    <br>
    <div class="container">
        <div class="col-md-12">
            <div class="row">
                <div class="well">
                    Please be <strong style="color: red">extremely careful</strong> when making any changes here! This will effect the user!
                </div>
            </div>
            <div class="row">
                {!! Form::open(['method' => 'post', 'route' => ['ticket_update', $ticket->id]]) !!}
                <div class="form-group">
                    {!! Form::label('first_name', 'First Name') !!}
                    {!! Form::input('text', 'first_name', $ticket->first_name, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('last_name', 'Last Name') !!}
                    {!! Form::input('text', 'last_name', $ticket->last_name, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('email', 'Email Address') !!}
                    {!! Form::input('text', 'email', $ticket->email, ['class' => 'form-control']) !!}
                </div>

                <hr>

                <div class="form-group">
                    {!! Form::label('venue_id', 'Please select new venue for this ticket.') !!}
                    {!! Form::select('venue_id', $venues, $ticket->venue_id, ['class' => 'form-control', 'style' => 'padding:0px']) !!}
                    </select>
                </div>

                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.members.show', $ticket->user_id) }}">Back to profile</a>
                    {!! Form::submit('Update Ticket', ['class' => 'btn btn-success']) !!}
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script src="/admin/assets/js/index.js"></script>
    @include('admin.members.includes.spin')
@stop