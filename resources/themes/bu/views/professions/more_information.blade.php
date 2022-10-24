@extends('app')

@section('content')

@section('title')
    Practice License for {!! $profession->title !!}
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('profession', $profession->title) !!}
@stop

<section style="background-image: url('/assets/frontend/images/demo/wall2.jpg'); padding-bottom: 10px;">
    <div class="container">
        <div class="row">

            <div class="panel panel-success text-center">
                <div class="panel-heading panel-heading-transparent">
                    <strong>Congratulations!</strong>
                </div>

                <div class="panel-body">
                    <p>
                        You are about to get your whole firm compliant. Once registered, you and all your staff will
                        gain access to all Essential Plus content effective 1 November 2017.
                    </p>
                    <p>
                        Also use this facility to monitor your staff's training and development.
                    </p>
                </div>
            </div>
            {!! Form::open() !!}
            <div class="panel panel-default">
                <div class="panel-heading panel-heading-transparent">
                    <strong>Information for purchase order</strong>
                </div>

                <div class="panel-body">
                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('first_name')) has-error @endif">
                            {!! Form::label('first_name', 'First Name') !!}
                            {!! Form::input('text', 'first_name', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('first_name')) <p
                                    class="help-block">{{ $errors->first('first_name') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('last_name')) has-error @endif">
                            {!! Form::label('last_name', 'Last Name') !!}
                            {!! Form::input('text', 'last_name', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('last_name')) <p
                                    class="help-block">{{ $errors->first('last_name') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('id_number')) has-error @endif">
                            {!! Form::label('id_number', 'ID Number') !!}
                            {!! Form::input('text', 'id_number', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('id_number')) <p
                                    class="help-block">{{ $errors->first('id_number') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('email')) has-error @endif">
                            {!! Form::label('email', 'Email Address') !!}
                            {!! Form::input('text', 'email', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('cell')) has-error @endif">
                            {!! Form::label('cell', 'Cellphone Number') !!}
                            {!! Form::input('text', 'cell', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('cell')) <p class="help-block">{{ $errors->first('cell') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('alternative_cell')) has-error @endif">
                            {!! Form::label('alternative_cell', 'Alternative Contact Number') !!}
                            {!! Form::input('text', 'alternative_cell', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('alternative_cell')) <p
                                    class="help-block">{{ $errors->first('alternative_cell') }}</p> @endif
                        </div>
                    </div>

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading panel-heading-transparent">
                    <strong>Company Information</strong>
                </div>

                <div class="panel-body">

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('company')) has-error @endif">
                            {!! Form::label('company', 'Company Name') !!}
                            {!! Form::input('text', 'company', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('company')) <p
                                    class="help-block">{{ $errors->first('company') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('company_vat')) has-error @endif">
                            {!! Form::label('company_vat', 'Company VAT') !!}
                            {!! Form::input('text', 'company_vat', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('company_vat')) <p
                                    class="help-block">{{ $errors->first('company_vat') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('plan')) has-error @endif">
                            {!! Form::label('plan', 'License') !!}
                            {!! Form::input('text', 'plan', 'Company Licese for'.$profession->title, ['class' => 'form-control', 'disabled' => 'true']) !!}
                            {!! Form::hidden('selected_plan', $profession->title) !!}
                            @if ($errors->has('plan')) <p class="help-block">{{ $errors->first('plan') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('employees')) has-error @endif">
                            {!! Form::label('employees', 'Number of Employees') !!}
                            {!! Form::input('number', 'employees', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('employees')) <p
                                    class="help-block">{{ $errors->first('employees') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('address_line_one')) has-error @endif">
                            {!! Form::label('address_line_one', 'Address Line One') !!}
                            {!! Form::input('text', 'address_line_one', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('address_line_one')) <p
                                    class="help-block">{{ $errors->first('address_line_one') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('address_line_two')) has-error @endif">
                            {!! Form::label('address_line_two', 'Address Line Two') !!}
                            {!! Form::input('text', 'address_line_two', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('address_line_two')) <p
                                    class="help-block">{{ $errors->first('address_line_two') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('province')) has-error @endif">
                            {!! Form::label('province', 'Province') !!}
                            {!! Form::input('text', 'province', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('province')) <p
                                    class="help-block">{{ $errors->first('province') }}</p> @endif
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
                            @if ($errors->has('area_code')) <p
                                    class="help-block">{{ $errors->first('area_code') }}</p> @endif
                        </div>
                    </div>


                </div>
            </div>

            <div class="panel panel-default text-right">
                <div class="panel-footer">
                    <a href="{{ route('profession.show', $profession->slug) }}" class="btn btn-primary"><i
                                class="fa fa-arrow-left"></i> Back</a>
                    <button class="btn btn-primary"><i class="fa fa-check"></i> Submit Order</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <br>
</section>
@endsection