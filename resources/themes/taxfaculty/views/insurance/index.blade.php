@extends('app')

@section('title', 'PI Insurance')
@section('intro', 'PI Insurance')
@section('breadcrumbs')
	{!! Breadcrumbs::render('insurance.index') !!}
@endsection

@section('styles')
<style type="text/css">
	.spacer-10 {
		margin: 15px 0;
	}
	.spacer-5 {
		margin: 10px 0;
	}
	label {
		font-weight: bold;
	}
</style>
@endsection

@section('content')
<section style="background-image: url('/assets/frontend/images/demo/wall2.jpg');">
	<app-insurance-register-screen inline-template>
		<div class="container app-screen">

			<div v-if="step == 1">
				@include('insurance.partials.basics')
			</div>

			<div v-if="step == 2">
				@include('insurance.partials.addresses')
			</div>

			<div v-if="step == 3">
				@include('insurance.partials.assessment')
			</div>

			<div v-if="step == 4">
				@include('insurance.partials.declaration')
			</div>

			<div v-if="step == 5">
				@include('insurance.partials.complete')
			</div>

		</div>
	</app-insurance-register-screen>
</section>
@endsection