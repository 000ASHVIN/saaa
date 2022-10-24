@extends('app')

@section('title')
    Account Overview
@stop

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li class="active">General</li>
                    </ol>
                </li>
            </ol>
        </li>
    </ol>
@stop

@section('styles')
    <style type="text/css">
        .bootstrap-dialog-message form, .bootstrap-dialog-message .form-group {
            margin-bottom: 0;
        }
    </style>
@endsection

@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')

            <div class="col-lg-9 col-md-9 col-sm-8">

                <div class="row">
                    <div class="col-md-6">
                        <div class="heading-title heading-dotted text-center">
                            <h4>CPD <span>Hours</span></h4>
                        </div>
                        <div class="border-box">
                            <h4 style="text-align: center; font-weight: normal;">Continuing Professional
                                Development</h4>
                            <hr>
                            <p style="text-align: center"><i class="fa fa-clock-o"></i> {{ $user->cpds->sum('hours') }}
                                Hours Total</p>
                            <hr>

                            <p style="text-align: center">
                                <a class="btn btn-primary" href="#" data-target="#ajax" data-toggle="modal">Allocate
                                    CPD</a>
                                <a href="{{ route('dashboard.cpd') }}" class="btn btn-default">Manage CPD</a>
                            </p>
                        </div>
                    </div>

                    @include('dashboard.includes.cpd')
                    @include('dashboard.includes.invoices')
                </div>

                <div class="divider"></div>

                <div class="row">
                    <div class="col-md-12">

                        <div class="heading-title heading-dotted text-center">
                            <h4>My <span>Events</span></h4>
                        </div>

                        @include('dashboard.includes.tickets',['tickets' => $tickets, 'user' => $user])
                    </div>
                </div>
            </div>
        </div>
    </section>


@stop

@section('scripts')
    @if($promptForContactDetails)
        <script id="contact-details-modal" type="text/html">
            @include('dashboard.includes.contact-details-modal')
        </script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="/assets/frontend/js/bootstrap-dialog.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                var modalMessageContent = $('#contact-details-modal').html();
                var dialog = new BootstrapDialog({
                    size: BootstrapDialog.SIZE_SMALL,
                    message: modalMessageContent,
                    closable: false,
                    closeByBackdrop: false,
                    closeByKeyboard: false
                });
                dialog.realize();
                dialog.getModalHeader().hide();
                dialog.getModalFooter().hide();
                dialog.open();
            });
        </script>
    @endif
    <script>
        $(document).ready(function () {
            $('#cpd_date').datepicker();

            $(window).load(function () {
                $('#renew_subscription_modal').modal('show');
            });
        });
    </script>
@stop