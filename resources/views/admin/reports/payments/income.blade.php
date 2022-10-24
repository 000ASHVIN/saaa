@extends('admin.layouts.master')

@section('title', 'Income Report')
@section('description', 'Income Reports')

@section('content')
<income-reports inline-template>
	<div class="container-fluid container-fullw bg-white">
		<div class="row">
			<div class="col-md-6">
				<app-date :display="'From'"
                  	  :form="forms.incomeReports"
                  	  :name="'from'"
                	  :input.sync="forms.incomeReports.from"
            	>
        		</app-date>
			</div>
			<div class="col-md-6">
				<app-date :display="'To'"
                  	  :form="forms.incomeReports"
                  	  :name="'to'"
                	  :input.sync="forms.incomeReports.to"
        	    >
        		</app-date>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
				    <button type="submit" class="btn btn-primary" :disabled="forms.incomeReports.busy" v-on:click.prevent="runIncomeReport()">
                        <span v-if="forms.incomeReports.busy">
                            <i class="fa fa-btn fa-spinner fa-spin"></i> Processing Report
                        </span>
                        <span v-else>
                            <i class="fa fa-btn fa-check-circle"></i> Run Report
                        </span>
                    </button>

                    <button type="button" v-on:click.prevent="runExportReport()" class="btn btn-success" :disabled="forms.exportReport.busy || ! transactions">
                        <span v-if="forms.exportReport.busy">
                            <i class="fa fa-btn fa-spinner fa-spin"></i> Exporting Report
                        </span>
                        <span v-else>
                            <i class="fa fa-btn fa-file-excel-o"></i> Export Report
                        </span>
                    </button>
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid container-fullw bg-white" v-if="invoices.length > 0">
		<table class="table table-hover">
			<thead>
				<th>Total Invoiced</th>
				<th>Total Cancellations</th>
				<th>Total Discounts</th>
				<th>Total Payments</th>
				<th>Total Credit Notes</th>
			</thead>
			<tbody>
				<tr>
					<td>@{{ invoicesTotal | currency 'R' }}</td>
					<td>@{{ cancellationsTotal | currency 'R' }}</td>
					<td>@{{ discountsTotal | currency 'R' }}</td>
					<td><b>@{{ paymentsTotal | currency 'R' }}</b></td>
					<td>@{{ creditnotesTotal | currency 'R' }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</income-reports>
@endsection


@section('scripts')
<script src="/js/app.js"></script>
<script>
    jQuery(document).ready(function () {
        Main.init();
    });
</script>
@endsection