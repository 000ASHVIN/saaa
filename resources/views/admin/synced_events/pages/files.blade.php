<div id="files" class="tab-pane fade">
    @if(count($event->links))
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <th>Name</th>
                <th>Link</th>
                <th>Instructions</th>
                <th>Password</th>
                {{-- <th class="text-center">Update</th> --}}
            </thead>
            <tbody>
            @foreach($event->links as $link)
                {{-- @include('admin.event.includes.links.update') --}}
                <tr>
                    <td>{{ $link->name }}</td>
                    <td>
                        <div class="form-inline">
                            <input id="link{{$link->id}}" style="width: 60%;" class="form-control" value="{{ $link->url }}">
                            <button class="btn btn-success" id="copy-button" data-clipboard-target="#link{{$link->id}}">Copy</button>
                            <a href="{{ $link->url }}" target="_blank" class="btn btn-info">View</a>
                        </div>
                    </td>
                    <td>{{ ($link->instructions ? : "No Instructions supplied") }}</td>
                    <td>{{ ($link->secret ? : "No Password") }}</td>
                    {{-- <td class="text-center"><a data-target="#link_{{$link->id}}_update" data-toggle="modal"><span class="label label-info"><i class="fa fa-pencil"></i></span></a></td> --}}
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">
            <p>There is no link's for this event.</p>
        </div>
    @endif

    {{-- <hr>
    <div class="form-group">
        <button class="btn btn-info" data-toggle="modal" data-target="#link_create">Create your link</button>
        @include('admin.event.includes.links.create')
    </div> --}}
</div>