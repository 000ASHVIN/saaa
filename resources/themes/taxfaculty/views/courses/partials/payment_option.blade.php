@if(in_array($course->id,$array))
<input v-model="option" type="hidden" value="monthly" />
@else
<div class="panel panel-default">

    <div class="panel-heading">
        Please select your course enrollment payment option
    </div>

    <div class="panel-body">
        <label class="radio">
            <input type="radio" v-on:change="coursetotalCalculate" v-model="option" value="monthly">
            <i></i> Monthly Option
        </label>

        <label class="radio" >
            <input type="radio" v-on:change="coursetotalCalculate" v-model="option" value="yearly">
            <i></i> Once-off
        </label>
    </div>
</div>
@endif

<div class="panel panel-default">
    <div class="panel-heading">
        ID Number
    </div>
    <div class="panel-body">
        <div class="form-group">
            <input type="text" name="id_number" class="form-control" v-model="id_number">
            <span class="help-block text-danger" v-if="address.errors.id_number"><small>@{{ address.errors.id_number[0] }}</small></span>
        </div>
    </div>

    <div class="panel-heading">
        Address
    </div>
    <div class="panel-body course_address">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="street_name">Street number and street name</label>
                    <input type="text" name="street_name" id="street_name" class="form-control" v-model="address.street_name">
                    <span class="help-block text-danger" v-if="address.errors.street_name && !address.street_name"><small>@{{ address.errors.street_name[0] }}</small></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="building">Complex/Building</label>
                    <input type="text" name="building" id="building" class="form-control" v-model="address.building">
                    <span class="help-block text-danger" v-if="address.errors.building && !address.building"><small>@{{ address.errors.building[0] }}</small></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="suburb">Suburb</label>
                    <input type="text" name="suburb" id="suburb" class="form-control" v-model="address.suburb">
                    <span class="help-block text-danger" v-if="address.errors.suburb && !address.suburb"><small>@{{ address.errors.suburb[0] }}</small></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="city">City/Town</label>
                    <input type="text" name="city" id="city" class="form-control" v-model="address.city">
                    <span class="help-block text-danger" v-if="address.errors.city && !address.city"><small>@{{ address.errors.city[0] }}</small></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="province">Province</label>
                    <input type="text" name="province" id="province" class="form-control" v-model="address.province">
                    <span class="help-block text-danger" v-if="address.errors.province && !address.province"><small>@{{ address.errors.province[0] }}</small></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" name="country" id="country" class="form-control" v-model="address.country">
                    <span class="help-block text-danger" v-if="address.errors.country && !address.country"><small>@{{ address.errors.country[0] }}</small></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group" style="margin-bottom: 0">
                    <label for="postal_code">Postal code</label>
                    <input type="text" name="postal_code" id="postal_code" class="form-control" v-model="address.postal_code">
                    <span class="help-block text-danger" v-if="address.errors.postal_code && !address.postal_code"><small>@{{ address.errors.postal_code[0] }}</small></span>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.donation', ['vif' => "donations && option=='yearly'", 'vmodel' => "forms.enroll.donations"])
@include('includes.course-fees', ['vif' => "donations && option=='yearly'", 'vmodel' => "forms.enroll.donations"])

<div class="alert alert-info" v-if="option">
    <span v-if="option == 'monthly'">
        @if(!in_array($course->id,$array))
          <i class="fa fa-info"></i> Please note that an enrollment fee of <span >R<strong>@{{ course.monthly_enrollment_fee }}</strong></span> will be charged.</br>
      @endif
          <div v-if="course.type_of_course == 'semester'" >
          @if(!in_array($course->id,$array))
          <i class="fa fa-info"></i> Please note that a Purchase order of <span >R<strong>@{{ parseInt(course.order_price) > 0 ? course.order_price : 0 }}</strong></span> will be generated upon payment of your enrollment fee. Payable before course commencing.     
          @else
  
          <i class="fa fa-info"></i> Please note that a once off compliance course fee of <span >R<strong>@{{ course.monthly_enrollment_fee }}</strong></span> will be charged.</br>
  
  
          <i class="fa fa-info"></i>Please note after completion of the compliance course, the payment options available on the Full and Accelerator courses can be registered for as per results achieved.  
          @endif  
          </div>
      </span>
   

    {{--  <span v-if="option == 'yearly'"><i class="fa fa-info"></i> Please note that an enrollment fee of 
        <span >R<strong>@{{ course.annual_discounted_price }}</strong></span> will be charged.
    </span>  --}}

    <span v-if="option == 'yearly' && course_type != ''"><i class="fa fa-info"></i> Please note that an enrollment fee of 
        <span >R<strong>@{{ course_total }}</strong></span> will be charged.
    </span>

    <span v-if="forms.enroll.donations && option=='yearly' && donations">
        <br/><i class="fa fa-info"></i> Please note that a donation fee of 
        <span >R<strong>@{{ donations }}</strong></span> will be charged.
    </span>

</div>