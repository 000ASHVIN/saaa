{{--View data section--}}
<?php
$events = App\AppEvents\Event::with(['venues', 'venues.dates'])->get();
?>
        <!--END View data section-->
@extends('admin.layouts.master')

@section('title', 'Import')
@section('description', 'Members')

@section('content')
    <br>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                {!! Former::open()->route('admin.import.provider.import.action',[$provider->id,$import->id,$action])->method('POST') !!}
                <import-members-register-for-event :events="{{ $events->toJson() }}" inline-template>
                    <h3>Event</h3>
                    <label for="event_id">
                        <select v-on:change="changeEventSelection" v-model="selectedEventId" name="event_id"
                                id="event_id">
                            <option v-for="event in events" value="@{{ event.id }}">@{{ event.name }}</option>
                        </select>
                    </label>
                    <br>
                    <br>

                    <h3>Venue</h3>
                    <label for="venue_id">
                        <select v-on:change="changeVenueSelection" v-model="selectedVenueId" name="venue_id"
                                id="venue_id">
                            <option v-for="venue in selectedEvent.venues"
                                    value="@{{ venue.id }}">@{{ venue.name }}</option>
                        </select>
                    </label>
                    <br>
                    <br>


                    <h3>Date</h3>
                    <label for="date_id">
                        <select v-model="selectedDateId" name="date_id" id="date_id">
                            <option v-for="date in selectedVenue.dates"
                                    value="@{{ date.id }}">@{{ date.date }}</option>
                        </select>
                    </label>
                    <br>
                    <br>
                </import-members-register-for-event>
                {!! Former::submit('Register')->addClass('btn-primary') !!}
                {!! Former::close() !!}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop