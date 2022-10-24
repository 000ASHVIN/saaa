@extends('admin.layouts.master')

@section('title', 'My Sales')
@section('description', 'Track My Sales')

@section('content')
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <h4>Event Registrations</h4>
                    </div>
                    {!! Form::open(['method' => 'post', 'route' => 'my_sales_export_event_registrations']) !!}
                    <div class="col-md-6">
                        <div class="form-inline pull-right">
                            <div class="form-group @if ($errors->has('from')) has-error @endif">
                                {!! Form::label('from', 'From') !!}
                                {!! Form::input('text', 'from', null, ['class' => 'form-control is-date']) !!}
                                @if ($errors->has('from')) <p class="help-block">{{ $errors->first('from') }}</p> @endif
                            </div>

                            <div class="form-group @if ($errors->has('to')) has-error @endif">
                                {!! Form::label('to', 'To') !!}
                                {!! Form::input('text', 'to', null, ['class' => 'form-control is-date']) !!}
                                @if ($errors->has('to')) <p class="help-block">{{ $errors->first('to') }}</p> @endif
                            </div>
                            {!! Form::submit('Export Events', ['class' => 'btn btn-primary']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <br>
                    <br>
                    <hr>
                    <table class="table table-striped text-left">
                        <thead>
                            <th>Date</th>
                            <th>Member</th>
                            <th>Invoice</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Balance</th>
                            <th>Event</th>
                            <th>Venue</th>
                        </thead>
                        <tbody>
                            @if(count($events))
                                @foreach($events as $event)
                                    @if($event->user && $event->invoice)
                                        <tr>
                                            <td>{{ date_format($event->created_at, 'd F Y') }}</td>
                                            <td>{{ $event->user->first_name }} {{ $event->user->last_name }}</td>
                                            <td>#{{ $event->invoice->reference }}</td>
                                            <td><div class="label label-info">{{ $event->invoice->status }}</div></td>
                                            <td>{{ $event->invoice->total }}</td>
                                            <td>{{ money_format('%.2n', $event->invoice->total - $event->invoice->transactions->where('type', 'credit')->sum('amount')) }}</td>

                                            @if ($event->invoice && $event->invoice->ticket)
                                                <td>{{ $event->invoice->ticket->event->name }}</td>
                                                <td>{{ $event->invoice->ticket->name }}</td>

                                            @elseif($event->order && $event->order->ticket)
                                                <td>{{ $event->order->ticket->event->name }}</td>
                                                <td>{{ $event->order->ticket->name }}</td>
                                            @else
                                                <td><div class="label label-danger">Ticket Cancelled</div></td>
                                                <td><div class="label label-danger">Ticket Cancelled</div></td>
                                            @endif
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                            <tr>
                                <td colspan="8">You have no event entries yet.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <br>
            <br>

            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <h4>CPD Subscription Registrations</h4>
                    </div>
                    {!! Form::open(['method' => 'post', 'route' => 'my_sales_export_cpd_subscription_registrations']) !!}
                    <div class="col-md-6">
                        <div class="form-inline pull-right">
                            <div class="form-group @if ($errors->has('from')) has-error @endif">
                                {!! Form::label('from', 'From') !!}
                                {!! Form::input('text', 'from', null, ['class' => 'form-control is-date']) !!}
                                @if ($errors->has('from')) <p class="help-block">{{ $errors->first('from') }}</p> @endif
                            </div>

                            <div class="form-group @if ($errors->has('to')) has-error @endif">
                                {!! Form::label('to', 'To') !!}
                                {!! Form::input('text', 'to', null, ['class' => 'form-control is-date']) !!}
                                @if ($errors->has('to')) <p class="help-block">{{ $errors->first('to') }}</p> @endif
                            </div>
                            {!! Form::submit('Export Subscriptions', ['class' => 'btn btn-primary']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}

                    <hr>
                    <table class="table table-striped">
                        <thead>
                            <th>Member</th>
                            <th>Old Subscription</th>
                            <th>New Subscription</th>
                            <th>Completed</th>
                            <th>Invoice</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Balance</th>
                        </thead>
                        <tbody>
                            @if(count($cpds))
                                @foreach($cpds as $cpd)
                                    <tr>
                                        <td>{{ $cpd->user->first_name." ".$cpd->user->last_name }}</td>
                                        @if($cpd->upgrade)
                                            <td>{{ \App\Subscriptions\Models\Plan::find($cpd->upgrade->old_subscription_package)->name." ".\App\Subscriptions\Models\Plan::find($cpd->upgrade->old_subscription_package)->interval }}</td>
                                            <td>{{ \App\Subscriptions\Models\Plan::find($cpd->upgrade->new_subscription_package)->name." ".\App\Subscriptions\Models\Plan::find($cpd->upgrade->new_subscription_package)->interval }}</td>
                                            <td>{{ ($cpd->upgrade->completed ? "Yes" : "False") }}</td>
                                        @else
                                            <td> - </td>
                                            <td>{{ ($cpd->user->subscribed('cpd')? $cpd->user->subscription('cpd')->plan->name : "No Subscription Plan") }}</td>
                                            <td>Yes</td>
                                        @endif
                                        @if ($cpd->invoice)
                                            <td>#{{ $cpd->invoice->reference }}</td>
                                            <td><div class="label label-info">{{ $cpd->invoice->status }}</div></td>
                                            <td>{{ $cpd->invoice->total }}</td>
                                            <td>{{ money_format('%.2n', $cpd->invoice->total - $cpd->invoice->transactions->where('type', 'credit')->sum('amount')) }}</td>
                                        @else
                                            <td># </td>
                                            <td><div class="label label-info"> - </div></td>
                                            <td> - </td>
                                            <td> - </td>
                                        @endif

                                    </tr>
                                @endforeach
                            @else
                            <tr>
                                <td colspan="8">You have no cpd subscription entries yet.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop