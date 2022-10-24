@extends('app')

@section('content')

@section('title')
    SAIBA - Southern African Institute for Business Accountants
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
                            <h4>SAIBA - Southern African Institute for Business Accountants</h4>
                            <p>SAIBA is your gateway to the accounting profession. Join. Earn. Share.</p>

                            <p>SAIBA designations include:</p>
                            <table class="table">
                                <thead>
                                    <th>BA (SA)</th>
                                    <th>BAP (SA)</th>
                                    <th>CBA (SA)</th>
                                    <th>CFO (SA)</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Step into your accounting career</td>
                                        <td>Become an accountant in practice</td>
                                        <td>Your gateway to becoming a superior financial manager</td>
                                        <td>Become an international financial executive</td>
                                    </tr>
                                </tbody>
                            </table>
                            <p><strong>Discount:</strong> Existing members of professional bodies:</p>
                            <p>SAAA subscribers that are members of professional bodies get exemptions to earn a SAIBA designation and a 50% discount on a designation fee.</p>
                            <p>Not yet a member of a professional body: SAAA subscribers get a SAIBA Associate membership for one year for free. Get to experience the SAIBA benefits at no cost.</p>
                            <p><strong>Steps:</strong></p>
                            <ol>
                                <li>Not yet a SAAA subscribers? Signup for a free CPD Subscription Package.</li>
                                <li>Login to your account and complete the form under rewards and SAIBA will contact you to explain how you can claim your discount.</li>
                                <li>SAIBA is a SAQA recognised controlling body for accountants with a statutory licence to issue designations for junior accountants, financial managers, CFOs and accountants in practice.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <img src="/assets/frontend/images/sponsors/saiba.jpg" width="100%" class="thumbnail" alt="SAIBA">
                <h4>Need more information ?</h4>
                {!! Form::open(['method' => 'post', 'route' => ['rewards.store', 'saiba']]) !!}
                @include('questionnaire.includes.form', ['product' => ['SAIBA' => 'SAIBA']])
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
