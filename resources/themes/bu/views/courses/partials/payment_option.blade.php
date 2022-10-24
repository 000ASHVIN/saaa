<div class="panel panel-default">

    <div class="panel-heading">
        Please select your course enrollment payment option
    </div>

    <div class="panel-body">
        <label class="radio">
            <input type="radio" v-model="option" value="monthly">
            <i></i> Monthly Option
        </label>

        <label class="radio" >
            <input type="radio" v-model="option" value="yearly">
            <i></i> Once-off
        </label>
    </div>
</div>

<div class="alert alert-info" v-if="option">
    <span v-if="option == 'monthly'"><i class="fa fa-info"></i> Please note that an enrollment fee of R<strong>@{{ course.monthly_enrollment_fee }}</strong> will be charged.</span>
    <span v-if="option == 'yearly'"><i class="fa fa-info"></i> Please note that an enrollment fee of R<strong>@{{ course.yearly_enrollment_fee }}</strong> will be charged</span>
</div>