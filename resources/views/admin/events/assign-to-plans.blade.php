@extends('admin.layouts.master')

@section('title', 'Events')
@section('description', 'Assign event to plans')

@section('content')
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <div class="panel panel-default">
                <div class="panel-body">
                    {!! Former::open()->route('admin.events.assign-to-plans')->method('POST') !!}
                    <div class="col-md-6 col-md-offset-3">
                        <event-assign-to-plans :events="{{ $events->toJson() }}" :plans="{{ $plans->toJson() }}" inline-template>
                            <h3>Select Event</h3>
                            <div class="form-group">
                                <select v-on:change="changeEventSelection" v-model="selectedEventId" name="event_id" id="event_id" class="form-control">
                                    <option v-for="event in events" value="@{{ event.id }}">@{{ event.name }}</option>
                                </select>
                            </div>

                            <h3>Select Venue</h3>
                            <div class="form-group">
                                <select v-on:change="changeVenueSelection" v-model="selectedVenueId" name="venue_id"
                                        id="venue_id" class="form-control">
                                    <option v-for="venue in selectedEvent.venues"
                                            value="@{{ venue.id }}">@{{ venue.name }}</option>
                                </select>
                            </div>

                            <h3>Select Date</h3>
                            <div class="form-group">
                                <select v-model="selectedDateId" name="date_id" id="date_id" class="form-control">
                                    <option v-for="date in selectedVenue.dates"
                                            value="@{{ date.id }}">@{{ date.date }}</option>
                                </select>
                            </div>

                            <h3>Select Your Plans</h3>
                            <div class="form-group">
                                <select name="plans_ids[]" id="plans_ids[]" class="select2 form-control" multiple="true">
                                    <option v-for="plan in plans" value="@{{ plan.id }}" id="@{{ plan.id }}">@{{ plan.interval }}ly - @{{ plan.name }}</option>
                                </select>
                            </div>
                        </event-assign-to-plans>

                        <a onclick="spin(this)" class="btn btn-primary"><i class="fa fa-check"></i> Assign Events</a>
                    </div>
                    {!! Former::close() !!}
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

    <script type="text/javascript">
        $('.select2').select2();

        function spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
        }
    </script>
@stop