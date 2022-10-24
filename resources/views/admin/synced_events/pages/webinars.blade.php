<div id="webinars" class="tab-pane fade">


    @if(count($event->webinars()))
        <table class="table table-bordered table-striped">
            <thead>
                <th>Pricing</th>
                <th>Link</th>
                <th class="text-center">Status</th>
                <th>Password</th>
                {{-- <th>Update</th>
                <th>Discard</th> --}}
            </thead>
            <tbody>
            @foreach($event->webinars() as $webinar)
                <tr>
                    <td>{{ ucfirst($webinar->pricing->name) }}</td>
                    <td>
                        <div class="form-inline">
                            <input id="copy_{{$webinar->id}}_webinar" style="width: 70%" class="form-control" value="{{ $webinar->url }}">
                            <button class="btn btn-success" id="copy-button" data-clipboard-target="#copy_{{$webinar->id}}_webinar">Copy</button>
                            <a href="{{ $webinar->url }}" target="_blank" class="btn btn-info">Join</a>
                        </div>
                    </td>
                    @if($webinar->is_active == true)
                        <td style="width: 5%;" class="text-center"><i class="fa fa-check"></i></td>
                    @else
                        <td style="width: 5%;" class="text-center"><i class="fa fa-times"></i></td>
                    @endif
                    <td>{{ $webinar->passcode }}</td>
                    {{-- <td style="width: 5%" class="text-center"><a data-target=#webinar_{{$webinar->id}}_edit data-toggle="modal"><span class="label label-info"><i class="fa fa-pencil"></i></span></a></td>
                    <td style="width: 5%" class="text-center"><a href="{{ route('admin.event.webinar.destroy', $webinar->id) }}" data-confirm-content="Are you sure you want to delete selected webinar"><span class="label label-info"><i class="fa fa-close"></i></span></a></td> --}}
                </tr>
                {{-- @include('admin.event.includes.webinar.edit') --}}
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">
            <p style="font-style: italic;">No Webinars for this event listed.</p>
        </div>
    @endif
        {{-- <hr>
        <div class="form-group">
            <button class="btn btn-info" data-toggle="modal" data-target="#webinar_create">Create your webinar</button>
            @include('admin.event.includes.webinar.create')
        </div> --}}
</div>