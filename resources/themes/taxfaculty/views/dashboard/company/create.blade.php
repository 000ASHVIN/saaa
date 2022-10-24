@extends('app')

@section('title')
    Setup My Company
@stop

@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')
            <div class="col-lg-9 col-md-9 col-sm-8">

                <!-- Panel -->
                <div class="panel panel-success text-center">
                    <div class="panel-heading panel-heading-transparent">
                        <strong>Congratulations on taking the first step to get your firm compliant!</strong>
                    </div>

                    <div class="panel-body">
                        <p>
                            Please complete the below form to get your company setup. Once the setup has been completed, you would be able to invite all of your staff and track your staff's training and development
                        </p>
                    </div>
                </div>

                <!-- Panel End -->
                {!! Form::open() !!}
                <div class="panel panel-default">
                    <div class="panel-heading panel-heading-transparent">
                        <strong>Company Information</strong>
                    </div>


                    <div class="panel-body">
                        <div class="col-md-6">
                            <div class="form-group @if ($errors->has('title')) has-error @endif">
                                {!! Form::label('title', 'Company Name') !!}
                                {!! Form::input('text', 'title', null, ['class' => 'form-control']) !!}
                                @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group @if ($errors->has('company_vat')) has-error @endif">
                                {!! Form::label('company_vat', 'Company VAT') !!}
                                {!! Form::input('text', 'company_vat', null, ['class' => 'form-control']) !!}
                                @if ($errors->has('company_vat')) <p class="help-block">{{ $errors->first('company_vat') }}</p> @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group @if ($errors->has('plan')) has-error @endif">
                                {!! Form::label('plan', 'License') !!}
                                {!! Form::input('text', 'plan', 'Company Licence for '.$user->subscription('cpd')->plan->name, ['class' => 'form-control', 'disabled' => 'true']) !!}
                                {!! Form::hidden('plan_id', $user->subscription('cpd')->plan->id) !!}
                                {!! Form::hidden('user_id', $user->id) !!}
                                @if ($errors->has('plan')) <p class="help-block">{{ $errors->first('plan') }}</p> @endif
                            </div>
                        </div>

                        <div class="col-md-6" style="display:none">
                            <div class="form-group @if ($errors->has('employees')) has-error @endif">
                                {!! Form::label('employees', 'Number of Employees') !!}
                                {!! Form::input('number', 'employees', 0, ['class' => 'form-control']) !!}
                                @if ($errors->has('employees')) <p class="help-block">{{ $errors->first('employees') }}</p> @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group @if ($errors->has('address_line_one')) has-error @endif">
                                {!! Form::label('address_line_one', 'Address Line One') !!}
                                {!! Form::input('text', 'address_line_one', null, ['class' => 'form-control']) !!}
                                @if ($errors->has('address_line_one')) <p class="help-block">{{ $errors->first('address_line_one') }}</p> @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group @if ($errors->has('address_line_two')) has-error @endif">
                                {!! Form::label('address_line_two', 'Address Line Two') !!}
                                {!! Form::input('text', 'address_line_two', null, ['class' => 'form-control']) !!}
                                @if ($errors->has('address_line_two')) <p class="help-block">{{ $errors->first('address_line_two') }}</p> @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group @if ($errors->has('province')) has-error @endif">
                                {!! Form::label('province', 'Province') !!}
                                {!! Form::input('text', 'province', null, ['class' => 'form-control']) !!}
                                @if ($errors->has('province')) <p class="help-block">{{ $errors->first('province') }}</p> @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            @include('professions.includes.countries')
                        </div>

                        <div class="col-md-6">
                            <div class="form-group @if ($errors->has('city')) has-error @endif">
                                {!! Form::label('city', 'City') !!}
                                {!! Form::input('text', 'city', null, ['class' => 'form-control']) !!}
                                @if ($errors->has('city')) <p class="help-block">{{ $errors->first('city') }}</p> @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group @if ($errors->has('area_code')) has-error @endif">
                                {!! Form::label('area_code', 'Area Code') !!}
                                {!! Form::input('text', 'area_code', null, ['class' => 'form-control']) !!}
                                @if ($errors->has('area_code')) <p class="help-block">{{ $errors->first('area_code') }}</p> @endif
                            </div>
                        </div>
                    </div>
                </div>

                <button onclick="spin(this)" class="btn btn-block btn-primary"><i class="fa fa-check"></i> Complete Setup</button>
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@stop