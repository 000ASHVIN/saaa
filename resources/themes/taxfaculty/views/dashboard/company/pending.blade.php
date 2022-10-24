@extends('app')

@section('title')
    Company Invitations
@stop

@section('content')
    <section>
        @include('dashboard.company.includes.bulk_invite')
        <div class="container">
            @include('dashboard.includes.sidebar')
            <div class="col-lg-9 col-md-9 col-sm-8">

                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12 col-sm-12">
                        @if(! $user->company_admin())
                            <a href="{{ route('dashboard.company.create') }}" class="btn btn-primary pull-right"><i class="fa fa-building"></i> Setup My Company</a>
                        @elseif($user->company_admin())
                            @if(auth()->user()->isPracticePlan() > auth()->user()->totalStaff())
                            <a href="#bulkInvite" data-toggle="modal" data-title="bulkInvite" class="btn btn-warning pull-right"><i class="fa fa-users"></i> Bulk Invitation</a>
                            <a href="{{ route('dashboard.company.invite') }}" class="btn btn-primary pull-right"><i class="fa fa-envelope"></i> Send Invitation</a>
                            @endif
                            <a href="{{ route('dashboard.company.index') }}" class="btn btn-default pull-right"><i class="fa fa-arrow-left"></i> Back</a>
                        @endif
                    </div>
                </div>

                <br>
                <div class="heading-title heading-dotted text-center" style="margin-bottom: 10px">
                    <h4>All Company Invitations</h4>
                </div>

                @include('dashboard.company.includes.invites')
                {!! $invites->render() !!}
            </div>
        </div>
    </section>
@stop