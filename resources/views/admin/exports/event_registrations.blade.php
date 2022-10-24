@extends('admin.layouts.master')

@section('title', 'Exports')
@section('description', 'Event Registration Exports')

@section('content')
<div class="container-fluid container-fullw bg-white">
    <div class="row">
        <div class="col-md-12">
            <div class="row margin-top-30">
                <div class="col-lg-8 col-lg-offset-2 col-md-12">
                    <div class="panel">
                        <div class="panel-body">
                            <form role="form" method="POST">
                                <fieldset>
                                    <legend>
                                        Membership
                                    </legend>
                                    {!! csrf_field() !!}
                                    <div class="form-group">
                                        <label for="plan">
                                            Membership Plan <span class="symbol required"></span>
                                        </label>
                                        <select name="plan[]" id="plan" class="form-control select2" multiple="true">
                                            <option value="">Please select...</option>

                                            @foreach($plans as $plan)
                                            <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </fieldset>
                                <fieldset>
                                <legend>
                                    Ticket
                                </legend>
                                <div class="form-group">
                                        <label for="attended">
                                            Does have a ticket <span class="symbol required"></span>
                                        </label>
                                        <select name="attended" id="attended" class="form-control">
                                            <option value="">Please select...</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                            </fieldset>
                            <fieldset>
                                <legend>
                                    Event
                                </legend>
                                <div class="form-group">
                                        <label for="event">
                                            Event <span class="symbol required"></span>
                                        </label>
                                        <select name="event" id="event" class="form-control">
                                            <option value="">Please select...</option>
                                            @foreach($events as $event)
                                                <option value="{{ $event->slug }}">{{ $event->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                            </fieldset>
                            <fieldset>
                                <legend>
                                    Payment Status
                                </legend>
                                <div class="form-group">
                                        <label for="payment">
                                            Payment Status <span class="symbol required"></span>
                                        </label>
                                        <select name="payment" id="payment" class="form-control">
                                            <option value="">Please select...</option>
                                            <option value="paid">Paid</option>
                                            <option value="unpaid">Unpaid</option>
                                        </select>
                                    </div>
                            </fieldset>
                            <fieldset>
                                <legend>
                                    Export Format
                                </legend>
                                <div class="form-group">
                                        <label for="format">
                                            Export Format <span class="symbol required"></span>
                                        </label>
                                        <select name="format" id="format" class="form-control">
                                            <option value="xlsx">Please select...</option>
                                            <option value="xlsx">Excel Workbook (.xlsx)</option>
                                            <option value="csv">Comma Seperated List (csv)</option>
                                        </select>
                                    </div>
                            </fieldset>
                                <div class="form-group center">
                                    <button type="submit" class="btn btn-wide btn-o btn-primary">
                                        Export
                                    </button>
                                    <a href="#" class="btn btn-wide  btn-o btn-danger">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });

        $('.select2').select2({
            placeholder: "Please select",
        });
    </script>
@stop