@extends('app')

@section('content')

@section('title')
   {{ $content->title }}
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

<?php
if(isset($content->question_id)) 
{
    $question = explode(',',$content->question_id);
}
else{
    $question = null;
}
?>

<section class="alternate">
    <div class="container">
        <div class="row">
            <div class="col-md-8 verticalLine">
                {!! $content->content !!}
            </div>
            <div class="col-md-4">
                @if($content->logo != "")
                <img src="{{ asset('storage/'. $content->logo) }}" width="100%" class="thumbnail" alt="{{$content->slug}}">
                @endif
            
                @if(strtolower($content->slug) == 'draftworx')
                    @if(auth()->guest())
                            <div class="alert alert-info text-center">
                                <p><strong><i class="fa fa-lock"></i> You are not logged in..</strong></p>
                                <p>In order to claim this reward, you must be logged into your account.</p>
                                <hr>
                                <div class="form-group">
                                    <a href="/auth/login" class="btn btn-primary"><i class="fa fa-lock"></i> Login</a>
                                    <a href="/auth/register" class="btn btn-default"><i class="fa fa-unlock"></i> Signup</a>
                                </div>
                            </div>
                        @else
                        @if(isset($question)) 
                        <h4>Need more information ?</h4>  
                        {!! Form::open(['method' => 'post', 'route' => 'rewards.draftworx_store']) !!}
                            @if(in_array('22',$question))                       
                            <label class="radio">
                                <input type="radio" checked value="1" name="quote">
                                <i></i> Yes, I am interested in DRAFTWORX. Please send me a quote now.
                            </label>

                            <label class="radio">
                                <input type="radio" value="0" name="quote">
                                <i></i> No, I don't need DRAFTWORX right now, but keep me informed.
                            </label>
                            @endif

                            @if(in_array('23',$question))
                            <div class="form-group @if ($errors->has('email')) has-error @endif">
                                {!! Form::label('email', 'Email Address') !!}
                                {!! Form::input('text', 'email', auth()->user()?auth()->user()->email:null, ['class' => 'form-control']) !!}
                                @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                            </div>
                            @endif

                            @if(in_array('24',$question))
                            <div class="form-group @if ($errors->has('company_trading_name')) has-error @endif">
                                {!! Form::label('company_trading_name', 'Company Trading Name') !!}
                                {!! Form::input('text', 'company_trading_name', null, ['class' => 'form-control']) !!}
                                @if ($errors->has('company_trading_name')) <p class="help-block">{{ $errors->first('company_trading_name') }}</p> @endif
                            </div>
                            @endif

                            @if(in_array('25',$question))
                            <div class="form-group @if ($errors->has('physical_business_address')) has-error @endif">
                                {!! Form::label('physical_business_address', 'Physical address of Business') !!}
                                {!! Form::input('text', 'physical_business_address', null, ['class' => 'form-control']) !!}
                                @if ($errors->has('physical_business_address')) <p class="help-block">{{ $errors->first('physical_business_address') }}</p> @endif
                            </div>
                            @endif

                            @if(in_array('26',$question))
                            <div class="form-group @if ($errors->has('vat_number')) has-error @endif">
                                {!! Form::label('vat_number', 'VAT number (if registered)') !!}
                                {!! Form::input('text', 'vat_number', null, ['class' => 'form-control']) !!}
                                @if ($errors->has('vat_number')) <p class="help-block">{{ $errors->first('vat_number') }}</p> @endif
                            </div>
                            @endif

                            @if(in_array('27',$question))
                            <div class="form-group @if ($errors->has('first_name')) has-error @endif">
                                {!! Form::label('first_name', 'Contact person - first name') !!}
                                {!! Form::input('text', 'first_name', null, ['class' => 'form-control']) !!}
                                @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
                            </div>
                            @endif

                            @if(in_array('28',$question))
                            <div class="form-group @if ($errors->has('surname')) has-error @endif">
                                {!! Form::label('surname', 'Contact person - surname') !!}
                                {!! Form::input('text', 'surname', null, ['class' => 'form-control']) !!}
                                @if ($errors->has('surname')) <p class="help-block">{{ $errors->first('surname') }}</p> @endif
                            </div>
                            @endif

                            @if(in_array('29',$question))
                            <div class="form-group @if ($errors->has('contact_number')) has-error @endif">
                                {!! Form::label('contact_number', 'Contact Number') !!}
                                {!! Form::input('text', 'contact_number', null, ['class' => 'form-control']) !!}
                                @if ($errors->has('contact_number')) <p class="help-block">{{ $errors->first('contact_number') }}</p> @endif
                            </div>
                            @endif

                            @if(in_array('30',$question))
                            <div class="form-group @if ($errors->has('id_or_passport')) has-error @endif">
                                {!! Form::label('id_or_passport', 'ID or Passport number') !!}
                                {!! Form::input('text', 'id_or_passport', null, ['class' => 'form-control']) !!}
                                @if ($errors->has('id_or_passport')) <p class="help-block">{{ $errors->first('id_or_passport') }}</p> @endif
                            </div>
                            @endif

                            @if(in_array('31',$question))
                            <div class="form-group @if ($errors->has('type_of_subscription')) has-error @endif">
                                {!! Form::label('type_of_subscription', 'Type of SAAA subscription') !!}
                                {!! Form::select('type_of_subscription', [
                                    'Registered user without CPD subscription' => 'Registered user without CPD subscription',
                                    'Monthly CPD subscription' => 'Monthly CPD subscription',
                                    'Yearly CPD subscription' => 'Yearly CPD subscription',
                                    'Not a subscriber with SAAA' => 'Not a subscriber with SAAA',
                                ],null, ['class' => 'form-control']) !!}
                                @if ($errors->has('type_of_subscription')) <p class="help-block">{{ $errors->first('type_of_subscription') }}</p> @endif
                            </div>
                            @endif

                            @if(in_array('32',$question))
                            <div class="form-group @if ($errors->has('professional_body')) has-error @endif">
                                {!! Form::label('professional_body', 'Member of which professional body?') !!}
                                {!! Form::select('professional_body', [
                                    'SAIBA' => 'SAIBA',
                                    'SAICA' => 'SAICA',
                                    'SAIPA' => 'SAIPA',
                                    'SAIT' => 'SAIT',
                                    'CIMA' => 'CIMA',
                                    'ICSA' => 'ICSA',
                                    'IIA' => 'IIA',
                                    'AAT' => 'AAT',
                                    'SAIGA' => 'SAIGA',
                                    'CIGFARO' => 'CIGFARO',
                                    'ICBA' => 'ICBA',
                                    'ACCA' => 'ACCA',
                                    'IAC' => 'IAC',
                                    'IBA' => 'IBA',
                                    'Other' => 'Other',
                                ],null, ['class' => 'form-control']) !!}
                                @if ($errors->has('professional_body')) <p class="help-block">{{ $errors->first('professional_body') }}</p> @endif
                            </div>
                            @endif

                            @if(in_array('33',$question))
                            <div class="form-group @if ($errors->has('number_of_licenses')) has-error @endif">
                                {!! Form::label('number_of_licenses', 'Number of user licences required') !!}
                                {!! Form::input('text', 'number_of_licenses', null, ['class' => 'form-control', 'placeholder' => 'Enter a number between 1 - 10']) !!}
                                @if ($errors->has('number_of_licenses')) <p class="help-block">{{ $errors->first('number_of_licenses') }}</p> @endif
                            </div>
                            @endif

                            @if(in_array('34',$question))
                            <div class="form-group @if ($errors->has('applies_to_you')) has-error @endif">
                                {!! Form::label('applies_to_you', 'Which applies to you?') !!}
                                {!! Form::select('applies_to_you', [
                                    "I'm a first-time DRAFTWORX user" => "I'm a first-time DRAFTWORX user",
                                    "I'm already using DRAFTWORX - need to add more user licences" => "I'm already using DRAFTWORX - need to add more user licences",
                                    "I'm already using DRAFTWORX - need to renew my user licence." => "I'm already using DRAFTWORX - need to renew my user licence.",
                                ],null, ['class' => 'form-control']) !!}
                                @if ($errors->has('applies_to_you')) <p class="help-block">{{ $errors->first('applies_to_you') }}</p> @endif
                            </div>
                            @endif

                            @if(in_array('35',$question))
                            <div class="form-group @if ($errors->has('type_of_business')) has-error @endif">
                                {!! Form::label('type_of_business', 'Your type of business') !!}
                                {!! Form::select('type_of_business', [
                                    'Audit/accounting firm - external use for clients' => 'Audit/accounting firm - external use for clients',
                                    'Corporate company - internal use' => 'Corporate company - internal use',
                                ],null, ['class' => 'form-control']) !!}
                                @if ($errors->has('type_of_business')) <p class="help-block">{{ $errors->first('type_of_business') }}</p> @endif
                            </div>
                            @endif

                            <button class="btn btn-primary" onclick="spin(this)"><i class="fa fa-check"></i> Submit Form</button>

                            {!! Form::close() !!}
                            @endif
                        @endif
                @elseif(strtolower($content->slug) == 'taxshop')
                    <h4>Need more information ?</h4>                                 
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <b>Contact Details</b>
                        </div>
                        <div class="panel-body">
                            <p><strong>EMAIL: </strong> <a href="mailto:enquiries@taxshop.co.za">enquiries@taxshop.co.za</a></p>
                            <p><strong>PHONE: </strong> 0861 370 220</p>
                        </div>
                    </div>
                @else 
                {!! Form::open(['method' => 'post', 'route' => ['rewards.store', $content->slug]]) !!} 
                    @if(isset($question)) 
                    <h4>Need more information ?</h4>                                 
                    @include('questionnaire.includes.form', ['question' =>$question])
                    @endif                          
                {!! Form::close() !!}
                @endif 
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
