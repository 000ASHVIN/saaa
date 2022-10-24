<div id="venues" class="tab-pane fade">
    @if(count($event->AllVenues))
        <table class="table table-hover table-bordered table-striped">
            <thead>
                <th>Title</th>
                <th>Address line one</th>
                <th>Address line two</th>
                <th>Status</th>
                <th>Dates</th>
                <th class="text-center">Update</th>
                <th class="text-center">Dates</th>
                <th class="text-center">Discard</th>
            </thead>
            <tbody>
                @foreach($event->AllVenues as $venue)
                    <tr>
                        <td>{{ $venue->name }}</td>
                        <td>{{ $venue->address_line_one }}</td>
                        <td>{{ $venue->address_line_two }}</td>
                        <td>{{$venue->is_active ? "Active" : "Not Active"}}</td>
                        <td class="text-left">
                            @if(count($venue->dates))
                                @foreach($venue->dates as $date)
                                    {{ $date->date }} <br>
                                @endforeach
                            @else
                                No dates available
                            @endif
                        </td>
                        <td class="text-center"><a href="#" data-target="#{{$venue->id}}_update" data-toggle="modal"><span class="label label-info"><i class="fa fa-pencil"></i></span></a></td>
                        <td class="text-center"><a href="#" data-target="#{{$venue->id}}_date_create" data-toggle="modal"><span class="label label-info"><i class="fa fa-calendar-o"></i></span></a></td>
                        <td class="text-center"><a data-confirm-content="Are you sure you want to delete selected venue" class="label label-info" href="{{ route('admin.event.venue.destroy', $venue->id) }}">X</a></td>
                    </tr>
                    @include('admin.event.includes.venues.update')
                    @include('admin.event.includes.dates.create')
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">
            <p style="font-style: italic;">No venues for this event listed.</p>
        </div>
    @endif
        <hr>
        <div class="form-group">
            <button class="btn btn-info" data-toggle="modal" data-target="#venues_create">Create your venue</button>
        </div>
</div>