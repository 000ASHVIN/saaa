<div id="pricings" class="tab-pane fade in">

    <div class="alert alert-danger">
        <p><strong>NB*</strong> If this event venues has not been confirmed, <strong>DO NOT</strong> add pricings. You may only add venue pricings upon venue and date confirmation and approval.</p>
    </div>
    @if(count($event->pricings))
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <th>Title</th>
                <th>Venue</th>
                {{--<th>Description</th>--}}
                <th>Price</th>
                <th class="text-center">CPD Hours</th>
                <th class="text-center">CPD Verifiable</th>
                <th class="text-center">Attendance Certificate</th>
                <th class="text-center">Status</th>
                <th class="text-center" colspan="2">Update</th>
                <th class="text-center">Bodies</th>
                <th class="text-center">Body</th>
                <th class="text-center">CPD Features</th>
                <th class="text-center">Features</th>
            </thead>
            <tbody>
                @foreach($event->pricings as $pricing)
                    <tr>
                        <td>{{ $pricing->name }}</td>
                        <td>{{ $pricing->venue->name }}</td>
                        {{--<td>{{ $pricing->description }}</td>--}}
                        <td>R {{ number_format($pricing->price, 2) }}</td>
                        <td class="text-center">{{ $pricing->cpd_hours }}</td>
                        <td class="text-center">{{ ($pricing->cpd_verifiable ? "Yes" : "No") }}</td>
                        <td class="text-center">{{ ($pricing->attendance_certificate ? "Yes" : "No") }}</td>

                        @if($pricing->is_active == true)
                            <td class="text-center"><i class="fa fa-check"></i></td>
                        @else
                            <td class="text-center"><i class="fa fa-times"></i></td>
                        @endif
                        <td class="text-center"><a href="#" class="label label-info" data-toggle="modal" data-target="#{{$pricing->id}}_pricing_update"><i class="fa fa-pencil"></i></a></td>
                        <td class="text-center"><a href="#" class="label label-danger" data-toggle="modal" data-target="#pricing_delete_{{$pricing->id}}"><i class="fa fa-close"></i></a></td>
                        <td class="text-center">{{ $pricing->bodies->count() }}</td>
                        <td class="text-center"><a href="#" class="label label-success" data-toggle="modal" data-target="#{{$pricing->id}}_body_update"><i class="fa fa-plus"></i></a></td>
                        <td class="text-center">{{ $pricing->features()->count() }}</td>
                        <td class="text-center"><a href="#" class="label label-success" data-toggle="modal" data-target="#{{$pricing->id}}_pricing_feature"><i class="fa fa-star"></i></a></td>
                    </tr>
                    @include('admin.event.includes.bodies.update')
                    @include('admin.event.includes.pricings.update')
                    @include('admin.event.includes.pricings.delete')
                    @include('admin.event.includes.feature.update')
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">
            <p>There is no pricings for this event.</p>
        </div>
    @endif

    <hr>
    <div class="form-group">
        <button class="btn btn-info" data-toggle="modal" data-target="#pricing_create">Create your pricing</button>
        @include('admin.event.includes.pricings.create')
    </div>
</div>