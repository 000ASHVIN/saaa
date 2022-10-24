@extends('app')

@section('title')
    New Company Invitation
@stop

@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')
            <div class="col-lg-9 col-md-9 col-sm-8">
                <div class="alert alert-info">
                    <p><strong>Bulk Imports</strong></p>
                    <p>
                        Should you have more than 10 staff members you would like to add to your company, please download and complete
                        the following file. Once completed, please send it to {{ config('app.email') }} for bulk imports.
                    </p>
                    <p><a href="https://www.dropbox.com/s/g627muf0m03vwa4/imports-template.xlsx?dl=1"><i class="fa fa-download"></i> Download Form</a></p>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-envelope"></i> Send New Invitation
                    </div>

                    <br>
                    {!! Form::open() !!}
                    <div class="panel-body">
                        @include('dashboard.company.forms.invite', ['button' => 'Send Invite'])
                    </div>
                    {!! Form::close() !!}
                </div>

                <div class="heading-title heading-dotted text-center" style="margin-bottom: 10px">
                    <h4>Recent 10 Invitations</h4>
                </div>

                @include('dashboard.company.includes.invites')
                {!! $invites->render() !!}
            </div>
        </div>
    </section>
@stop