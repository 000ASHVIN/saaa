<div  v-if="!isComanySetup && companys.length == 0">
<div class="alert alert-bordered-dotted margin-bottom-30">
    <h4>Company setup is required</h4>
    <p>Please follow the steps below in order to get your comapny setup.</p>
    <ul class="list-unstyled list-icons">
        <li><i class="fa fa-check text-success"></i> Click on Setup My Company</li>
        <li><i class="fa fa-check text-success"></i> Enter your Company Name</li>
        <li><i class="fa fa-check text-success"></i> Enter your Company Vat Number</li>
        <li><i class="fa fa-check text-success"></i> Enter the amount of employees, (for indication purposes only)</li>
        <li><i class="fa fa-check text-success"></i> Enter your company address.</li>
        <li><i class="fa fa-check text-success"></i> Complete the setup</li>
    </ul>
    <br>
    <p>Once the above setup has been completed, you will be able to add to your staff members to join your CPD subscription package. However, as per our terms and conditions for practice licenses your staff would only be able to attend webinars and <strong>NOT</strong> Seminars.</p>
</div>
<a v-on:click.prevent="isComanySetup = true" href="#" class="btn btn-success"><i class="fa fa-building"></i> Setup My Company</a>
<div class="clearfix"></div>

</div>


<div class="col-lg-12 col-md-12 col-sm-12" v-if="isComanySetup && companys.length == 0">

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
    <div class="panel panel-default">
        <div class="panel-heading panel-heading-transparent">
            <strong>Company Information</strong>
        </div>


        <div class="panel-body">
            <div class="col-md-6">
                <div class="form-group " :class="{ 'has-error' : errors.company.title.length>0}">
                    {!! Form::label('title', 'Company Name') !!}
                    {!! Form::input('text', 'title', null, ['class' => 'form-control','v-model'=>'company.detail.title']) !!}
                     <p v-if="errors.company.title.length>0" class="help-block">@{{{ errors.company.title[0] }}}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group" :class="{ 'has-error' : errors.company.company_vat.length>0}">
                    {!! Form::label('company_vat', 'Company VAT') !!}
                    {!! Form::input('text', 'company_vat', null, ['class' => 'form-control','v-model'=>'company.detail.company_vat']) !!}
                    <p v-if="errors.company.company_vat.length>0" class="help-block">@{{{ errors.company.company_vat[0] }}}</p>
                </div>
            </div>

            {{-- <div class="col-md-6">
                <div class="form-group " :class="{ 'has-error' : errors.company.plan.length>0}">
                    {!! Form::label('plan', 'License') !!}
                    {!! Form::input('text', 'plan', 'Company Licence for '.auth()->user()->subscription('cpd')->plan->name, ['class' => 'form-control','v-model'=>'company.detail.plan', 'disabled' => 'true']) !!}
                    {!! Form::hidden('plan_id', auth()->user()->subscription('cpd')->plan->id,['v-model'=>'company.detail.plan_id']) !!}
                    {!! Form::hidden('user_id', auth()->user()->id,['v-model'=>'company.detail.user_id']) !!}
                    <p v-if="errors.company.plan.length>0" class="help-block">@{{{ errors.company.plan[0] }}}</p>
                </div>
            </div>--}}

            {!! Form::hidden( 'plan', 'Company Licence for '.@auth()->user()->subscription('cpd')->plan->name, ['class' => 'form-control','v-model'=>'company.detail.plan', 'disabled' => 'true']) !!}
            {!! Form::hidden('plan_id', auth()->user()->subscription('cpd')->plan->id,['v-model'=>'company.detail.plan_id']) !!}
            {!! Form::hidden('user_id', auth()->user()->id,['v-model'=>'company.detail.user_id']) !!}

            <div class="col-md-6">
                <div class="form-group " :class="{ 'has-error' : errors.company.employees.length>0}">
                    {!! Form::label('employees', 'Number of Employees') !!}
                    {!! Form::input('number', 'employees', null, ['class' => 'form-control','v-model'=>'company.detail.employees']) !!}
                    <p v-if="errors.company.employees.length>0" class="help-block">@{{{ errors.company.employees[0] }}}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group " :class="{ 'has-error' : errors.company.address_line_one.length>0}">
                    {!! Form::label('address_line_one', 'Address Line One') !!}
                    {!! Form::input('text', 'address_line_one', null, ['class' => 'form-control','v-model'=>'company.detail.address_line_one']) !!}
                    <p v-if="errors.company.address_line_one.length>0" class="help-block">@{{{ errors.company.address_line_one[0] }}}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group " :class="{ 'has-error' : errors.company.address_line_two.length>0}">
                    {!! Form::label('address_line_two', 'Address Line Two') !!}
                    {!! Form::input('text', 'address_line_two', null, ['class' => 'form-control','v-model'=>'company.detail.address_line_two']) !!}
                    <p v-if="errors.company.address_line_two.length>0" class="help-block">@{{{ errors.company.address_line_two[0] }}}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group " :class="{ 'has-error' : errors.company.province.length>0}">
                    {!! Form::label('province', 'Province') !!}
                    {!! Form::input('text', 'province', null, ['class' => 'form-control','v-model'=>'company.detail.province']) !!}
                    <p v-if="errors.company.province.length>0" class="help-block">@{{{ errors.company.province[0] }}}</p>
                </div>
            </div>

            <div class="col-md-6">
                @include('professions.includes.countries')
            </div>

            <div class="col-md-6">
                <div class="form-group " :class="{ 'has-error' : errors.company.city.length>0}">
                    {!! Form::label('city', 'City') !!}
                    {!! Form::input('text', 'city', null, ['class' => 'form-control','v-model'=>'company.detail.city']) !!}
                    <p v-if="errors.company.area_code.length>0" class="help-block">@{{{ errors.company.city[0] }}}</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group " :class="{ 'has-error' : errors.company.area_code.length>0}">
                    {!! Form::label('area_code', 'Area Code') !!}
                    {!! Form::input('text', 'area_code', null, ['class' => 'form-control','v-model'=>'company.detail.area_code']) !!}
                    <p v-if="errors.company.area_code.length>0" class="help-block">@{{{ errors.company.area_code[0] }}}</p>
                </div>
            </div>
        </div>
    </div>

    <button  v-on:click.prevent="createCompany()"  class="btn btn-block btn-primary"><i class="fa fa-check"></i> Complete Setup</button>
    
</div>





    