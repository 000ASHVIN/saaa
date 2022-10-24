<div class="attendees-list">
    <ul class="list-group" style="max-height: 460px; overflow-y: scroll;">
        <li style="display: inline-block; width: 100%"
            @if(!$selected)
            v-for="attendee in attendees | searchAndFilterAttendees search | orderBy sortField"
            @else
            v-for="attendee in selectedAttendees | searchSelectedAttendees selectedSearch | orderBy sortField"
            @endif
            class="list-group-item">
            <strong> @{{ attendee.first_name + ' ' +attendee.last_name }} </strong> (@{{ attendee.email }})
            <br>
        <span v-bind:class="{'label-danger': !attendee.attended, 'label-success': attendee.attended}"
              class="label">@{{ attendee.attended ? "Attended" : "Unattended" }}</span>
        <span v-if="attendee.invoice != null && attendee.invoice.status == 'paid'"
              class="label label-success">@{{ attendee.invoice.status.toUpperCase() }}</span>
        <span v-if="attendee.invoice != null && (attendee.invoice.status == 'unpaid' || attendee.invoice.status == 'cancelled')"
              class="label label-danger">@{{ attendee.invoice.status.toUpperCase() }}</span>
            <span v-if="attendee.invoice == null" class="label label-default">Free (No invoice)</span>
            <span v-if="attendee.marked_for_deletion" class="label label-warning">To be deleted</span>
            <br>
            <small>@{{ attendee.code }}</small>
            @if(!$selected)
                <button v-on:click="addToSelected(attendee.id)" type="button"
                        class="btn btn-info btn-xs pull-right"><i
                            class="fa fa-arrow-right"></i></button>
            @else
                <button v-on:click="removeFromSelected(attendee.id)" type="button"
                        class="btn btn-info btn-xs pull-right"><i
                            class="fa fa-arrow-left"></i></button>
            @endif
        </li>
    </ul>
</div>