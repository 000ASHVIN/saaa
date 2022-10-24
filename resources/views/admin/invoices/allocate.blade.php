@extends('admin.layouts.master')

@section('title', 'inv' . $invoice->reference)
@section('description', 'Payment Allocation')

@section('css')
<link href="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
@stop

@section('content')
<div class="container-fluid container-fullw bg-white">
	<div class="row">
		<div class="col-md-12">
			<div class="row margin-top-30">
				<div class="col-lg-8 col-lg-offset-2 col-md-12">
					<div class="panel">
						{{-- <div class="panel-heading">
							<h5 class="panel-title">INV{{ $invoice->reference }} Payment Allocation</h5>
						</div> --}}
						<div class="panel-body">
							<fieldset>
								<legend>
									Invoice Items
								</legend>
								<table class="table margin-top-10 table-hover">
									<thead>
										<th>Line Item</th>
										<th>Price</th>
									</thead>
									<tbody>
										@foreach($invoice->fresh()->items as $item)
											<tr>
												<td>
													{{ $item->name }} <br>
													<small>{{ $item->description }}
													@if($invoice->status == 'paid')
													<span class="label label-success">Paid</span>
													@else
													<span class="label label-danger">{{ ucwords($invoice->status) }}</span>
													@endif
													</small>
												</td>
												<td>
													{{ money_format('%.2n', $item->price) }}
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</fieldset>
							@if(count($invoice->transactions))
							<fieldset>
								<legend>
									Invoice Payment Allocations
								</legend>
								<table class="table margin-top-10 table-hover">
									<thead>
										<th>Tags</th>
										<th>Method</th>
										<th>Reference</th>
										<th>Date</th>
										<th>Price</th>
										<th>Tools</th>
									</thead>
									<tbody>
										@foreach($invoice->transactions->where('type', 'credit') as $payment)
											<tr>
												<td>
													{{ $payment->tags }}
												</td>
												<td style="text-transform: uppercase">
													{{ $payment->method }}
												</td>
												<td>
													<a href="#"
														data-toggle="popover"
													    data-placement="top"
													  	data-title="Payment Notes"
													  	data-content=
													   "{{ ($payment->notes) ?: 'None' }}"
													  	data-trigger="hover"
													  	data-original-title
											  	>
													#{{ $payment->ref }}
												</a>
												</td>
												<td>
													{{ $payment->date->format('d M Y') }}
												</td>
												<td>
													{{ $payment->amountAsCurrency() }}
												</td>
												<td>
													@permission('can-remove-payments-from-invoice')
														{!! Form::open(['url' => '/admin/invoices/payments/'.$payment->id.'/delete']) !!}
														@include('admin.confirm_delete.confirm_delete')
														<a href="#" data-target="#confirm{!! $invoice->reference. '' .$payment->id !!}" data-toggle="modal" class="btn btn-xs btn-warning">Remove</a>
														{!! Form::close() !!}

														{{--<a href="/admin/invoices/payments/{{ $payment->id }}/delete"--}}
														   {{--class="btn btn-xs btn-danger center delete-me"--}}
														   {{--data-toggle="tooltip"--}}
														   {{--data-original-title="Delete this payment"--}}
													   {{-->--}}
														{{--X--}}
													   {{--</a>--}}
													@endrole

												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</fieldset>
							@endif
							@if($invoice->status != 'cancelled')
							<form role="form" method="POST" action="/admin/invoices/allocate">
								<fieldset>
									<legend>
										Payment Details - Total Due: {{ money_format('%.2n', $invoice->total - $invoice->transactions->where('type', 'credit')->sum('amount')) }}
									</legend>
									<input type="hidden" value="{{ $invoice->id }}" name="invoice_id">
									{!! csrf_field() !!}
									<div class="form-group">
										<label for="date_of_payment">
											Date Received <span class="symbol required"></span>
										</label>
										<input type="text" class="form-control" name="date_of_payment" id="date_of_payment" placeholder="Date payment received">
									</div>
									<div class="form-group">
										<label for="amount">
											Payment Amount <span class="symbol required"></span>
										</label>
										<input type="integer" class="form-control" name="amount" id="amount" value="{{ $invoice->total - $invoice->transactions->where('type', 'credit')->sum('amount') }}" placeholder="Amount of payment received">
									</div>
									<div class="form-group">
										<label for="description">
											Payment Reference <span class="symbol required"></span>
										</label>
										<input type="text" class="form-control" name="description" id="description" placeholder="Payment Reference">
									</div>
									<div class="form-group">
										<label for="method">
											Payment Method <span class="symbol required"></span>
										</label>
										<select name="method" id="method" class="form-control">
											<option value="">Please select...</option>
											<option value="cash">Cash</option>
											<option value="eft">EFT</option>
											<option value="instant_eft">Instant EFT</option>
											<option value="cc">Credit Card</option>
											<option value="debit">Debit Order</option>
											<option value="snap_scan">Snap Scan</option>
											<option value="pebble">Payment Pebble</option>
										</select>
									</div>
									<div class="form-group">
										<label for="notes">
											Notes
										</label>
										<textarea name="notes" id="notes" rows="5" class="form-control"></textarea>
									</div>
								</fieldset>
								<div class="form-group center">
									<button type="submit" class="btn btn-o btn-primary" onclick="spin(this)";>
										Allocate Payment
									</button>
									<a href="/admin/members/{{ $invoice->user->id }}" class="btn btn-wide  btn-o btn-danger">Cancel</a>
								</div>
							</form>
							@else
							<p>
								<i>Invoice Cancelled, payment allocations not possible.</i>
							</p>
							<a href="/admin/members/{{ $invoice->user->id }}" class="btn btn-wide  btn-o btn-danger">Cancel</a>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop

@section('scripts')
<script src="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="/assets/admin/assets/js/allocate.js"></script>
<script>
	jQuery(document).ready(function() {
		Main.init();
		Allocate.init();
	});
</script>
<script>
	$('.delete-me').on('click', function(){
		$(this).closest('form').submit();
	});

    function spin(this1)
    {
      	this1.closest("form").submit();
        this1.disabled=true;
        this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
    }
</script>
@stop