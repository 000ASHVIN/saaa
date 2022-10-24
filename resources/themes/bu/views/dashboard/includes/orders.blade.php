@if(count($orders))
    <table class="table table-hover table-vertical-middle">
        <thead>
        <th>Product</th>
        <th class="text-left">Qty</th>
        <th class="text-left">Payment</th>
        <th class="text-center">Shipping</th>
        <th class="text-center">Files</th>
        <th class="text-center">Links</th>
        <th class="text-center">Assessments</th>
        </thead>

        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>
                    {{ $order->product->detailedTitle }}
                </td>
                <td class="text-left">
                    {{ $order->quantity }}
                </td>
                <td class="text-left">
                    @if($order->invoiceOrder && $order->invoiceOrder->status == 'paid')
                        <a href="#" class="label custom-label" data-toggle="tooltip" title="Paid" data-placement="right">
                            <i class="fa fa-check"></i>
                        </a>
                    @else
                        @if($order->invoiceOrder && $order->invoiceOrder->status == 'unpaid')
                            <a href="#"
                               class="label custom-label" data-toggle="tooltip" title="Unpaid" data-placement="right"> <i class="fa fa-times"></i>
                            </a>
                        @else
                            <a href="{{ route('invoices.show',$order->invoice->id) }}"
                               class="label custom-label" data-toggle="tooltip" title="Cancelled" data-placement="right"> <i class="fa fa-ban"></i>
                            </a>
                        @endif
                    @endif
                </td>
                <td class="text-center">
                    @if($order->shippingInformation)
                        <button type="button" style="background-color: #800000 !important; border-color: #800000 !important;" class="btn btn-info btn-xs" data-container="body" data-toggle="popover" data-placement="top" data-content="{{ $order->shippingInformation->status->description }}">
                            {{ $order->shippingInformation->status->title }}
                        </button>
                        <a href="#" class=""></a>
                    @else
                        None
                    @endif
                </td>
                <td class="text-center">
                    @if($order->product->files && count($order->product->files) > 0)
                        <span class="label label-primary" style="background-color: #800000">
                            @if($order->is_pending)
                                {{ count($order->product->files) }} files
                            @else
                                <a href="#" data-target="#files-popup-product-{{ $order->product->id }}"
                                   data-toggle="modal"
                                   style="color: white">{{ count($order->product->files) }} files</a>
                            @endif
                                </span>
                    @else
                        None
                    @endif
                </td>
                <td class="text-center">
                    @if($order->product->links && count($order->product->links) > 0)
                        <span class="label label-primary" style="background-color: #800000">
                            @if($order->is_pending)
                                {{ count($order->product->links) }} Resources
                            @else
                                <a href="#" data-target="#links-popup-product-{{ $order->product->id }}"
                                   data-toggle="modal"
                                   style="color: white">{{ count($order->product->links) }} Resources</a>
                            @endif
                                </span>
                    @else
                        None
                    @endif
                </td>
                <td>
                    @if($order->product->assessments)
                        <span class="label label-primary" style="background-color: #800000">
                            @if($order->is_pending)
                                {{ count($order->product->assessments) }} Assessments
                            @else
                                <a href="#" data-target="#assessment-popup-product-{{ $order->product->id }}"
                                   data-toggle="modal"
                                   style="color: white">{{ count($order->product->assessments) }} Assessments</a>
                            @endif
                                </span>
                    @else
                        None
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @foreach($orders as $order)
        @if(!$order->is_pending)
            @if($order->product->files && count($order->product->files) > 0)
                @include('dashboard.includes.files-popup',['files' => $order->product->files, 'product' => $order->product])
            @endif
            @if($order->product->links && count($order->product->links) > 0)
                @include('dashboard.includes.links-popup',['links' => $order->product->links, 'product' => $order->product])
            @endif
            @if($order->product->assessments)
                @include('dashboard.includes.assessment_popup',['assessments' => $order->product->assessments, 'product' => $order->product])
            @endif
        @endif
    @endforeach
@endif