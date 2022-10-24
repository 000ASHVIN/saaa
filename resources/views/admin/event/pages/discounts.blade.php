<div id="discount" class="tab-pane fade">
    @if(count($event->promoCodes))
        <table class="table table-bordered table-striped">
            <thead>
                <th>Title</th>
                <th>Description</th>
                <th>Code</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Limited Uses</th>
                <th>Remaining Uses</th>
                <th class="text-center">Update</th>
                <th class="text-center">Remove</th>
            </thead>
            <tbody>
            @foreach($event->promoCodes as $code)
                <tr>
                    <td>{{ ucfirst($code->title) }}</td>
                    <td>{{ ucfirst($code->description) }}</td>
                    <td>{{ $code->code }}</td>
                    <td>{{ ucfirst($code->discount_type) }}</td>
                    <td>{{ $code->discount_amount }}</td>
                    <td>{{ ($code->has_limited_uses ? "Yes" : "No") }}</td>
                    <td>{{ ($code->remaining_uses > 0 ?  $code->remaining_uses : "Unlimited") }}</td>
                    <td class="text-center"><a href="#" class="label label-info" data-toggle="modal" data-target="#{{$code->id}}_discount_create"><i class="fa fa-pencil"></i></a></td>
                    <td class="text-center"><a data-confirm-content="Are you sure you want to delete the selected code" class="label label-info" href="{{ route('admin.event.discount.destroy', $code
                    ->code) }}">X</a></td>
                </tr>

                @include('admin.event.includes.discount.edit')
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">
            <p>There is no discount codes for this event.</p>
        </div>
    @endif

        <hr>
        <div class="form-group">
            <button class="btn btn-info" data-toggle="modal" data-target="#discount_create">Create discount code</button>
            @include('admin.event.includes.discount.create')
        </div>
</div>