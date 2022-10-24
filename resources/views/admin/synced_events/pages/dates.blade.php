<div id="dates" class="tab-pane fade">
    <table class="table table-bordered table-hover table-striped">
        <thead>
            <th>Venue</th>
            <th>Venue Date</th>
            <th>Address Line One</th>
            <th>City</th>
            <th class="text-center">Status</th>
            {{-- <th class="text-center">Update</th> --}}
        </thead>
        <tbody>
        @foreach($event->AllVenues as $venue)
            @if(count($venue->dates))
               @foreach($venue->dates as $date)
                   <tr>
                       <td>{{ $venue->name }}</td>
                       <td>{{ $date->date }}</td>
                       <td>{{ $venue->address_line_one }}</td>
                       <td>{{ $venue->city }}</td>
                       @if($date->is_active == 1)
                          <td class="text-center"><i class="fa fa-check"></i></td>
                       @else
                           <td class="text-center"><i class="fa fa-times"></i></td>
                       @endif
                       {{-- <td class="text-center"><a href="#" class="label label-info" data-toggle="modal" data-target="#{{$date->id}}_date_update"><i class="fa fa-pencil"></i></a></td> --}}
                   </tr>
                   {{-- @include('admin.event.includes.dates.update') --}}
               @endforeach
            @else
                <tr>
                    <td colspan="6">No available dates for {{ strtolower($venue->name) }}</td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div>