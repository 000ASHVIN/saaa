@extends('app')

@section('content')

@section('title')
    CPD Subscription 2017
@stop

@section('intro')

@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('CPD') !!}
@stop

<section style="background-image: url('/assets/frontend/images/demo/wall2.jpg');">

    <div class="container">
        <div class="row">
            <div class="col-md-12 thumbnail" style="background-color: white">
                <a target="_blank" href="https://saiba.org.za/"><img src="http://imageshack.com/a/img921/6804/jehGkB.png" alt=""></a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="stepwizard">
            <div class="stepwizard-row setup-panel">

                <div class="stepwizard-step">
                    <a href="#step-1" style="pointer-events: none; cursor: default;" type="button"
                       class="btn btn-primary">1</a>
                    <p>Personal Information</p>
                </div>

                <div class="stepwizard-step">
                    <a href="#step-2" style="pointer-events: none; cursor: default;" type="button"
                       onclick="return e.preventDefault();" class="btn btn-default" disabled="disabled">2</a>
                    <p>Select your package</p>
                </div>

                @if(Request::url() != url('subscriptions/2017/saiba_member'))
                    <div class="stepwizard-step">
                        <a href="#step-3" style="pointer-events: none; cursor: default;" type="button"
                           class="btn btn-default" disabled="disabled">3</a>
                        <p>Personal Idemnity</p>
                    </div>
                @endif

            </div>
        </div>

        <br>

        <form role="form" action="" method="post">
            {!! Form::open(['method' => 'post', 'route' => 'cpd_new_store']) !!}
            <div class="row setup-content" id="step-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Personal Information
                    </div>
                    <div class="panel-body">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('first_name', 'First Name', ['class' => 'control-label']) !!}
                                {!! Form::input('text', 'first_name', null, ['class' => 'form-control', 'required' => 'required', 'maxlength' => '100']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('id_number', 'ID Number', ['class' => 'control-label']) !!}
                                {!! Form::input('text', 'id_number', null, ['class' => 'form-control', 'required' => 'required', 'maxlength' => '20']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('company', 'Company', ['class' => 'control-label']) !!}
                                {!! Form::input('text', 'company', null, ['class' => 'form-control', 'required' => 'required', 'maxlength' => '100']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('email', 'Email Address', ['class' => 'control-label']) !!}
                                {!! Form::input('text', 'email', null, ['class' => 'form-control', 'required' => 'required', 'maxlength' => '100']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('last_name', 'Last Name', ['class' => 'control-label']) !!}
                                {!! Form::input('text', 'last_name', null, ['class' => 'form-control', 'required' => 'required', 'maxlength' => '100']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('job_title', 'Job Title', ['class' => 'control-label']) !!}
                                {!! Form::input('text', 'job_title', null, ['class' => 'form-control', 'required' => 'required', 'maxlength' => '100']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('payment_option', 'Payment Method', ['class' => 'control-label']) !!}
                                {!! Form::select('payment_option', [
                                    'credit_card' => 'Credit Card',
                                    'debit_order' => 'Debit Order'
                                ],null, ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('cell', 'Cellphone Number', ['class' => 'control-label']) !!}
                                {!! Form::input('text', 'cell', null, ['class' => 'form-control', 'required' => 'required', 'maxlength' => '100']) !!}
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('proffesional_body', 'Please select your primary professional body', ['class' => 'control-label']) !!}
                                <select name="proffesional_body" class="form-control">
                                    <option {{ (Request::url() === url('subscriptions/2017/saiba_member')? "disabled" : "")}} value="">Please select..</option>
                                    <option {{ (Request::url() === url('subscriptions/2017/saiba_member')? "disabled" : "")}} value="aat">aat</option>
                                    <option {{ (Request::url() === url('subscriptions/2017/saiba_member')? "disabled" : "")}} value="cima">CIMA</option>
                                    <option {{ (Request::url() === url('subscriptions/2017/saiba_member')? "disabled" : "")}} value="iac">IAC</option>
                                    <option {{ (Request::url() === url('subscriptions/2017/saiba_member')? "disabled" : "")}} value="iia">IIA</option>
                                    <option {{ (Request::url() === url('subscriptions/2017/saiba_member')? "disabled" : "")}} value="saica">SAICA</option>
                                    <option {{ (Request::url() === url('subscriptions/2017/saiba_member')? "disabled" : "")}} value="saica">SAICA</option>
                                    <option {{ (Request::url() === url('subscriptions/2017/saiba_member')? "disabled" : "")}} value="acca">ACCA</option>
                                    <option {{ (Request::url() === url('subscriptions/2017/saiba_member')? "disabled" : "")}} value="cis">CIS</option>
                                    <option {{ (Request::url() === url('subscriptions/2017/saiba_member')? "disabled" : "")}} value="icba">ICBA</option>
                                    <option {{ (Request::url() === url('subscriptions/2017/saiba_member')? "selected" : "")}} value="saiba">SAIBA</option>
                                    <option {{ (Request::url() === url('subscriptions/2017/saiba_member')? "disabled" : "")}} value="saipa">SAIPA</option>
                                    <option {{ (Request::url() === url('subscriptions/2017/saiba_member')? "disabled" : "")}} value="other">OTHER</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer" style="text-align: right;">
                        <button class="btn btn-primary nextBtn" type="button">Proceed to next step <i
                                    class="fa fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>

            <div class="row setup-content" id="step-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Select your package
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            {!! Form::label('plans', 'Please select your package') !!}
                            <select v-model="selected" name="plan" class="form-control">
                                <option value="" selected>Please Select</option>
                                <option value="option-one-bookkeeper-junior-accountant">Option One: Bookkeeper/Junior
                                    Accountant
                                </option>
                                <option value="option-two-accounting-officer">Option Two: Accounting Officer</option>
                                <option value="option-three-independent-reviewer">Option Three: Independent Reviewer
                                </option>
                                <option value="option-comprehensive-accountancy-practice">Option Four: Comprehensive
                                    Accountancy Practice
                                </option>
                            </select>
                        </div>
                        @include('pre-register.form')

                        @if(Request::url() === url('subscriptions/2017/saiba_member'))
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="terms"> I have read and understood the
                                    <a target="_blank" href="{{ route('terms_and_conditions') }}">terms and conditions</a>
                                </label>
                            </div>
                        @endif
                    </div>
                    <div class="panel-footer" style="text-align: right;">
                        @if(Request::url() != url('subscriptions/2017/saiba_member'))
                        <button class="btn btn-primary nextBtn" type="button">Proceed to next step
                            <i class="fa fa-arrow-right"></i>
                        </button>
                        @else
                            <button class="btn btn-primary nextBtn" type="submit"> Complete </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row setup-content" id="step-3">
                <div class="panel panel-default">
                    <div class="panel-heading"> PI Insurance</div>
                    <div class="panel-body">

                        <p>
                            Aon South Africa (Pty) Ltd (“Aon”) is an authorized Financial Services Provider (“FSP”) in
                            terms of Financial Advisory and Intermediary Services Act 37 of 2002 (“FAIS”) have
                            developed a Professional Indemnity Insurance Plan for Accountants
                        </p>
                        <p>
                            Professional Indemnity Insurance helps cover you, your business and your employees from
                            professional liability lawsuits, and offers insurance for the associated defence costs.
                        </p>
                        <p>
                            The individual member policies is underwritten by The Hollard Insurance Company and
                            administered by AON South Africa.
                        </p>

                        <p>
                            The AON offer typically provides R5,000,000 Professional Indemnity Cover from R49 pm. Only available if you are a member in good standing
                            of a professional body and completed a professional vitality assessment. The underwritter may Adjust your premium based on your risk
                            profile. Approval of policy subject to underwritter risk assessment.
                        </p>
                        <hr>
                        <p>Please indicate if you would like to have Professional Indemnity cover</p>
                        <label class="radio-inline"><input name="pi_cover" type="radio" v-model="wantsPi" value="1">Yes</label>
                        <label class="radio-inline"><input name="pi_cover" type="radio" value="0"
                                                           v-model="wantsPi">No</label>

                        <div v-if="wantsPi == '1'">
                            <br>
                            <div class="alert alert-info">
                                Please note that you will be redirected to the
                                PI Insurance page after you clicked on submit
                                to answer a few short questions.
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <label><input type="checkbox" name="terms"> I have read and understood the <a
                                        target="_blank" href="{{ route('terms_and_conditions') }}">terms and
                                    conditions</a></label>
                        </div>
                    </div>

                    <div class="panel-footer" style="text-align: right">
                        <button class="btn btn-primary" type="submit">Complete <i class="fa fa-check"></i></button>
                    </div>
                </div>
            </div>
        </form>


    </div>
</section>

@endsection


@section('scripts')
    <script>
        $(document).ready(function () {
            var navListItems = $('div.setup-panel div a'),
                    allWells = $('.setup-content'),
                    allNextBtn = $('.nextBtn');

            allWells.hide();

            navListItems.click(function (e) {
                e.preventDefault();
                var $target = $($(this).attr('href')),
                        $item = $(this);

                if (!$item.hasClass('disabled')) {
                    navListItems.removeClass('btn-primary').addClass('btn-default');
                    $item.addClass('btn-primary');
                    allWells.hide();
                    $target.show();
                    $target.find('input:eq(0)').focus();
                }
            });

            allNextBtn.click(function () {
                var curStep = $(this).closest(".setup-content"),
                        curStepBtn = curStep.attr("id"),
                        nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                        curInputs = curStep.find("input[type='text'],input[type='url']"),
                        isValid = true;

                $(".form-group").removeClass("has-error");
                for (var i = 0; i < curInputs.length; i++) {
                    if (!curInputs[i].validity.valid) {
                        isValid = false;
                        $(curInputs[i]).closest(".form-group").addClass("has-error");
                    }
                }

                if (isValid)
                    nextStepWizard.removeAttr('disabled').trigger('click');
            });

            $('div.setup-panel div a.btn-primary').trigger('click');
        });
    </script>
    <script type="text/javascript">
        function KeepCount() {
            var elements = document.getElementsByName("events[]");
            var total = 0;
            for (var i in elements) {
                var element = elements[i];
                if (element.checked) total++;
                if (total > 8) {
                    element.checked = false;
                    return false;
                }
                if (total >= 8) {
                    $('#message').html('<div class="alert alert-success"><strong>Success!</strong> You have selected the maximum events.</div>');
                } else {
                    $('#message').html('<div class="alert alert-warning"><strong>warning!</strong> You need to select more events.</div>');
                }
            }
        }
    </script>

    <script>
        new Vue({
            el: '#pre-register',

            data: {
                wantsPi: 0
            }
        })
    </script>
@endsection