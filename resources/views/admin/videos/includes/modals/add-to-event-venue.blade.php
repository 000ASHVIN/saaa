{!! Former::open()->route('admin.recordings.create',$video->id)->id('add-to-event-venue-form')->method('POST') !!}
<select-event-and-venue :events="{{ $events->toJson() }}" inline-template>
    <h3>Event</h3>
    <label for="event_id">
        <select v-on:change="changeEventSelection" v-model="selectedEventId" name="event_id" id="event_id">
            <option v-for="event in events" value="@{{ event.id }}">@{{ event.name }}</option>
        </select>
    </label>
    <br><br>
</select-event-and-venue>
{!! Former::close() !!}