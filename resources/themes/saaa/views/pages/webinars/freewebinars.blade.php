@extends('app')

@section('content')

@section('title')
    SA Accounting Academy
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
                            <h4>SA Accounting Academy</h4>
                            <p><strong>If you sign up as a SAAA member we give you access to practice management webinars at no cost.</strong></p>

                            <p>Running and managing an effective accounting practice is not easy. You have to:</p>
                            <ul>
                                <li>Prepare a business plan </li>
                                <li>Decide what type of clients you will serve, </li>
                                <li>Buy new equipment,</li>
                                <li>Select the best software,</li>
                                <li>Appoint the right staff,</li>
                                <li>Take in new partners, </li>
                                <li>Brand and market your practice, </li>
                                <li>Develop new revenue streams, and </li>
                                <li>After a few years decide to sell your practice. </li>
                            </ul>
                            <p>Our Practice Management Webinars are hosted monthly to guide you on your journey and to provide access to seasoned practitioners that can show you the ropes and help you avoid mistakes. </p>

                            <p><strong>Steps:</strong></p>
                            <ol>
                                <li>Register as an SAAA CPD Subscriber.</li>
                                <li>Complete the form to the right and we will contact you to explain how you can claim your free monthly practice management webinars. </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <img src="/assets/frontend/images/sponsors/saaa.jpg" width="100%" class="thumbnail" alt="Draftworx">
                <h4>Need more information ?</h4>
                {!! Form::open(['method' => 'post', 'route' => ['rewards.store', 'webinars']]) !!}
                @include('questionnaire.includes.form', ['product' => ['Webinars' => 'Free Webinars']])
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