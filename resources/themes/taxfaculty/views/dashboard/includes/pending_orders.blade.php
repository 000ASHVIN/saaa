@if(count($orders))



    <table class="table table-hover table-vertical-middle">
        <thead>
        <th>Pending product</th>
        <th>Qty</th>
        <th class="text-center">Payment Status</th>
        <th class="text-center">Shipping Status</th>
        <th class="text-center">Files</th>
        <th class="text-center">Links</th>
        </thead>

        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>
                    @if($order->listing)
                        {{ $order->listing->title }} -
                    @elseif($order->product->topic)
                        {{ $order->product->topic }} -
                    @endif
                    {{ $order->product->title }}
                </td>
                <td class="text-center">
                    {{ $order->quantity }}
                </td>
                <td class="text-center">
                    @if($order->invoice)
                        @if($order->invoice->status == 'paid')
                            <a target="_blank" href="{{ route('invoices.show',$order->invoice->id) }}"
                               class="label label-success">{{ ucwords($order->invoice->status) }}</a>
                        @else($order->invoice->status == 'unpaid' || $order->invoice->status == 'cancelled')
                            <a target="_blank" href="{{ route('invoices.show',$order->invoice->id) }}"
                               class="label label-danger">{{ ucwords($order->invoice->status) }}</a>
                        @endif
                    @else
                        None
                    @endif
                </td>
                <td class="text-center">
                    @if($order->shippingInformation)
                        <a href="#" class="label label-info">{{ $order->shippingInformation->status }}</a>
                    @else
                        None
                    @endif
                </td>
                <td class="text-center">
                    @if($order->product->files && count($order->product->files) > 0)
                        <span class="label label-primary" style="background-color: #173175">
                                <a href="#" data-target="#event-popup" data-toggle="modal"
                                   style="color: white">{{ count($order->product->files) }} files</a>
                                </span>
                    @else
                        None
                    @endif
                </td>
                <td class="text-center">
                    @if($order->product->links && count($order->product->links) > 0)
                        <span class="label label-primary" style="background-color: #173175">
                                <a href="#" data-target="#event-popup" data-toggle="modal"
                                   style="color: white">{{ count($order->product->links) }} links</a>
                                </span>
                    @else
                        None
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{--@include('dashboard.includes.event-popup',['ticket' => $ticket])--}}
    {{--@include('dashboard.includes.events_modal')--}}
@endif