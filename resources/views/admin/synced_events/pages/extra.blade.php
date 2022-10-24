<div id="extra" class="tab-pane fade">
    @if(count($event->extras))
    <table class="table table-bordered table-striped">
        <thead>
            <th>Name</th>
            <th>Price</th>
            <th>CPD Hours</th>
            <th>Status</th>
            {{-- <th class="text-center">Update</th>
            <th class="text-center">Discard</th> --}}
        </thead>
        <tbody>
            @foreach($event->extras as $extra)
                <tr>
                    <td>{{ $extra->name }}</td>
                    <td>{{ money_format('%2n', $extra->price) }}</td>
                    <td>{{ $extra->cpd_hours }}</td>
                    <td>{{ ($extra->is_active? "Active" : "Not Active") }}</td>
                    {{-- <td class="text-center"><a data-target="#extra_{{$extra->id}}_update" data-toggle="modal"><span class="label label-info"><i class="fa fa-pencil"></i></span></a></td>
                    <td class="text-center"><a href="{{ route('admin.event.extra.destroy', [$event->slug, $extra->id]) }}"><span class="label label-info"><i class="fa fa-close"></i></span></a></td> --}}
                </tr>
                {{-- @include('admin.event.includes.extra.edit', ['extra' => $extra]) --}}
            @endforeach
        </tbody>
    </table>
    @else
        <div class="alert alert-info">
            <p>There is no extra's for this event.</p>
        </div>
    @endif

        {{-- <hr>
        <div class="form-group">
            <button class="btn btn-info" data-toggle="modal" data-target="#extra_create">Create your Extra</button>
            @include('admin.event.includes.extra.create')
        </div> --}}
</div>