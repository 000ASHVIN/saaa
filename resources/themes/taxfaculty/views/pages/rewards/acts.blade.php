@extends('app')

@section('content')

@section('title')
    ACTS Online
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('rewards') !!}
@stop

@section('styles')
    <style>
        .verticalLine {
            border-right: thick solid #e3e3e3;
        }
    </style>
@endsection

<section class="alternate">
    <div class="container">
        <div class="row">
            <div class="col-md-8 verticalLine">
                <div style="background-color: white; padding: 10px; border: 1px solid #e3e3e3">
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Acts Online </h4>
                            <p>Acts Online provides legislation, including amendments and Regulations, in an intuitive, online format.</p>
                            <p>Acts Online is one of the leading resource for available Legislation in South Africa and are used daily by thousands of professionals and industry leaders.</p>
                            <p>With Acts you are guaranteed the latest and most up to date resource for your legislative needs. In addition Acts sells PDF copies of the legislation.</p>
                            <p><strong>Discount:</strong> Up to 25% discount for SAAA Subscribers on Acts Online memberships.</p>
                            <p><strong>Steps:</strong></p>
                            <ol>
                                <li>Not yet a SAAA subscribers? Signup for a free CPD Subscription Package.</li>
                                <li>Login to your account and complete the form under rewards and Acts Online will contact you to explain how you can claim your discount.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <img src="/assets/frontend/images/sponsors/acts.jpg" width="100%" class="thumbnail" alt="SAIBA">
                <h4>Need more information ?</h4>
                {!! Form::open(['method' => 'post', 'route' => ['rewards.store', 'saiba']]) !!}
                @include('questionnaire.includes.form', ['product' => ['acts' => 'ACTS Online']])
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
    <script>
        function spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
        }
    </script>
@endsection
