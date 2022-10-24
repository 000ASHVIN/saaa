@extends('admin.layouts.master')

@section('title', 'Create Courses')
@section('description', 'Create A New Course')

@section('styles')
<style>
    .semester-price {
        position: relative;
    }
    .semester-price .index {
        position: absolute;
        background: #006ee6;
        height: 30px;
        width: 34px;
        border-radius: 50%;
        line-height: 2.5;
        text-align: center;
        color: white;
        top: 2px;
        left: 2px;
    }
    .semester-price input {
        padding-left: 45px;
        margin-bottom: 5px;
    }
</style>
@endsection

@section('content')
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::open(['method' => 'post', 'route' => 'admin.courses.store', 'enctype' => 'multipart/form-data']) !!}
                        @include('admin.courses.partials.form', ['button' => 'Create Course'])
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
    <script src="/admin/assets/js/index.js"></script>
    <script src="/assets/admin/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <script>
        $('.is-date').datepicker;
        $('.select2').select2();

        $('#type_of_course_id').val() === 'semester'? $('.no-of-semesters').show() : $('.no-of-semesters').hide();
        // if($('#no_of_semesters_id').val() > 0) {
        //     const semester = $('#no_of_semesters_id').val();
        //     for(i = 1 ; i <= semester; i++) {
        //         $('.semester-price-inputs .input-'+i).show()
        //     }
        // }
        $('#type_of_course_id').change(function() {
            const type = $(this).val()
            type === 'semester'? $('.no-of-semesters').show() : $('.no-of-semesters').hide()
        })

        let semesterPriceInput = "<div class='semester-price input-1'>"+
                                    "<div class='index index-1'>1</div>"+
                                    "<input type='number' name='semester_price[1]' class='form-control'>"+
                                "</div>";
        let lastSemester = 0;
        $('#no_of_semesters_id').keyup(function() {
            const semester = $(this).val()

            if(semester > 0) {

                if(semester > lastSemester) {
                    lastSemester++;
                    for(i = lastSemester; i <= semester; i++) {
                        $('.semester-price-inputs').append(semesterPriceInput.replace(/1/g, i))
                    }
                    lastSemester = semester;

                } else {
                    var start = parseInt(semester) + 1;
                    var end = lastSemester;
                    for(j = start; j <= end; j++) {
                        $('.semester-price-inputs .input-'+j).remove();
                    }
                    lastSemester = semester;
                }

            }
        })
        $('#no_of_semesters_id').focusout(function() {
            $(this).val(lastSemester)
        })
    </script>
@endsection