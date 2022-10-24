<div class="panel panel-default" v-if="course.type_of_course == 'semester' && option == 'yearly'">

    <div class="panel-heading">

        Please select your course semester payment option
    </div>

    <div class="panel-body">
        {{--  <div v-for="(i,semesters) in course.semester_price">  --}}

            <label class="radio">
                <input type="radio" v-on:change="coursetotalCalculate" v-model="course_type" value="partially">
                <i></i>Pay for 1st semester Only (R<strong>@{{course.semester_price}}</strong>)
            </label>
            <label class="radio">
                <input type="radio" v-on:change="coursetotalCalculate" v-model="course_type" value="full">
                <i></i>Full Payment (R<strong>@{{(course.semester_price * course.no_of_semesters)}}</strong>)
            </label>
        {{--  </div>  --}}

    </div>
</div>