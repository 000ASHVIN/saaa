@extends('app')

@section('title', 'Edit Personal Information')

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li hreff="#">Edit</li>
                        <li class="active">General</li>
                    </ol>
                </li>
            </ol>
        </li>
    </ol>
@stop

@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')

            <div class="col-lg-9 col-md-9 col-sm-8">
                @include('dashboard.edit.nav')

                <div class="margin-top-20"></div>

                {!! Form::model($user, ['method' => 'post', 'route' => 'dashboard.edit']) !!}
                <fieldset>
                    <legend>Personal Information</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('first_name','First Name') !!}
                                {!! Form::input('text', 'first_name', null , ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('last_name','Last Name') !!}
                                {!! Form::input('text','last_name', null , ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="pull-left">
                                    {!! Form::label('email','Email Address') !!}
                                </div>
                                <div class="pull-right"><a href="#" data-toggle="modal" data-target="#change_email_address"><small><i class="fa fa-lock"></i> <strong><i>Change</i></strong></small></a></div>
                                {!! Form::input('text','email', null , ['class' => 'form-control', 'disabled' => true]) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="pull-left">
                                    {!! Form::label('id_number','ID Number') !!}
                                </div>
                                <div class="pull-right"><a href="#" data-toggle="modal" data-target="#change_id_number"><small><i class="fa fa-lock"></i> <strong><i>Change</i></strong></small></a></div>

                                {!! Form::input('text','id_number', null , ['class' => 'form-control', 'disabled' => true]) !!}
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('cell','Mobile Number') !!}
                                {!! Form::input('text','cell', null , ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('alternative_cell','Daytime Contact Number') !!}
                                {!! Form::input('text','alternative_cell', null , ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('npo_registration_number','NPO Registration Number') !!}
                                {!! Form::input('text','npo_registration_number', $user->profile->npo_registration_number , ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        

                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('about_me','About Me', ['class' => 'control-label']) !!}
                                {!! Form::textarea('about_me', $user->profile->about_me, ['class' => 'form-control', 'rows' => "3", 'cols' => '20']) !!}
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Additional Billing Information</legend>
                    <div class="form-group @if ($errors->has('billing_email_address')) has-error @endif">
                        <div class="pull-left"> {!! Form::label('billing_email_address', 'Billing Email Address') !!}</div>
                        <div class="pull-right"><small><strong><i class="fa fa-info-circle"></i> <i>This email address will be cc'd when sending your account invoices.)</i></strong></small></div>
                        {!! Form::input('text', 'billing_email_address', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('billing_email_address')) <p class="help-block">{{ $errors->first('billing_email_address') }}</p> @endif
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Professional Body Information</legend>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group @if ($errors->has('body_id')) has-error @endif">
                                {!! Form::label('body_id', 'Main Professional Body') !!}
                                <select name="body_id" id="body_id" class="form-control" v-model="bodies">
                                    <option value="null" @if(! $user->body) selected @endif>Please Select</option>
                                    @foreach($bodies as $body)
                                        @if($user->body)
                                            <option value="{{ $body->id }}" {{ ($body->id == $user->body->id ? "selected" : "") }}>{{ ucfirst($body->title) }}</option>
                                        @else
                                            <option value="{{ $body->id }}">{{ $body->title }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @if ($errors->has('body_id')) <p class="help-block">{{ $errors->first('body_id') }}</p> @endif
                            </div>
                        </div>

                        <div class="col-md-12" v-if="bodies == '5'">
                            <div class="form-group @if ($errors->has('specified_body')) has-error @endif">
                                {!! Form::label('specified_body', 'Please Specify') !!}
                                {!! Form::input('text', 'specified_body', null, ['class' => 'form-control']) !!}
                                @if ($errors->has('specified_body'))
                                    <p class="help-block">{{ $errors->first('specified_body') }}</p> @endif
                            </div>
                        </div>

                        <div class="col-md-12" v-if="bodies !== '17' && bodies !== 'null'">
                            <div class="form-group @if ($errors->has('membership_number')) has-error @endif">
                                {!! Form::label('membership_number', 'Membership Number') !!}
                                {!! Form::input('text', 'membership_number', null, ['class' => 'form-control']) !!}
                                @if ($errors->has('membership_number'))
                                <p class="help-block">{{ $errors->first('membership_number') }}</p> @endif
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group @if ($errors->has('additional_professional_bodies')) has-error @endif">
                                {!! Form::label('additional_professional_bodies[]', 'Please select any additional professional bodies you belong to.') !!}
                                {!! Form::select('additional_professional_bodies[]', array_diff($bodies->pluck('title', 'id')->toArray(), array('Other (Not one of the above)', 'None')), null, ['class' => 'form-control select2', 'multiple' => true]) !!}
                                @if ($errors->has('additional_professional_bodies')) <p class="help-block">{{ $errors->first('additional_professional_bodies') }}</p> @endif
                            </div>
                        </div>
                    </div>
                </fieldset>


                <fieldset>
                    <legend>Workplace Information</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('position','Occupation') !!}
                                {!! Form::input('text','position', $user->profile->position , ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('company','Company') !!}
                                {!! Form::input('text','company', $user->profile->company , ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('tax','Company Tax Number') !!}
                                {!! Form::input('text','tax', $user->profile->tax , ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('website','Website Url') !!}
                                {!! Form::input('text','website', $user->profile->website , ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Locale Information</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group @if ($errors->has('area')) has-error @endif">
                                {!! Form::label('area', 'Area') !!}
                                {!! Form::input('text', 'area', $user->profile->area, ['class' => 'form-control']) !!}
                                @if ($errors->has('area')) <p class="help-block">{{ $errors->first('area') }}</p> @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group @if ($errors->has('province')) has-error @endif">
                                {!! Form::label('province', 'Province') !!}
                                {!! Form::input('text', 'province', $user->profile->province, ['class' => 'form-control']) !!}
                                @if ($errors->has('province')) <p class="help-block">{{ $errors->first('province') }}</p> @endif
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Emails & Notifications</legend>
                    <label class="checkbox">
                        <input type="checkbox" name="settings[send_invoices_via_email]" value="1" {{ (key_exists('send_invoices_via_email', ($user->settings) ? $user->settings : []))? "checked" : "" }}>
                        <i></i>  Invoice via E-mail
                    </label>

                    <label class="checkbox">
                        <input type="checkbox" name="settings[event_notifications]" value="1" {{ (key_exists('event_notifications', ($user->settings) ? $user->settings : []))? "checked" : "" }}>
                        <i></i> Event Notifications
                    </label>

                    <label class="checkbox">
                        <input type="checkbox" name="settings[sms_notifications]" value="1" {{ (key_exists('sms_notifications', ($user->settings) ? $user->settings : []))? "checked" : "" }}>
                        <i></i> SMS Notifications
                    </label>

                    <label class="checkbox">
                        <input type="checkbox" name="settings[marketing_emails]" value="1" {{ (key_exists('marketing_emails', ($user->settings) ? $user->settings : []))? "checked" : "" }}>
                        <i></i> Marketing Emails
                    </label>
                    <hr>
                </fieldset>


                <div class="margiv-top10">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Save Changes</button>
                </div>
                {!! Form::close() !!}
            </div>

            @include('dashboard.edit.includes.personal.change_email_address')
            @include('dashboard.edit.includes.personal.change_id_number')

        </div>
    </section>
@stop

@section('scripts')
    <script>
        $().ready(function() {
            $('#new_id_number').rsa_id_validator({
                displayValid: [true,"true","false"],
                displayValid_id: "valid_id_number",
                displayDate_id: "date",
                displayAge_id: "age",
                displayGender_id: "gender",
                displayCitizenship_id: "citizen"
            });
        });
    </script>
@endsection()