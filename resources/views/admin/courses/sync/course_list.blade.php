@extends('admin.layouts.master')

@section('title', 'All Courses')
@section('description', 'Sync courses')

@section('styles')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <sync-course :courses="{{ $courses }}" :moodle_courses="{{ json_encode($moodle_courses) }}" :moodle_course_ids="{{ json_encode($moodle_course_ids) }}" inline-template>
                <div class="row">
                    <div class="col-md-5">
                        <div class="alert" :class="messages.class" v-show="courseMessage">
                            @{{ messages.text }}
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="alert" :class="messages.class" v-show="moodleMessage">
                            @{{ messages.text }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="course_list">Course</label>
                            <select class="form-control" name="course" id="course_list" v-model="course" @change="checkCourse">
                                <option value="0">Please select the course</option>
                                <option v-for="course in courses" v-bind:value="course.id">
                                    @{{ course.title }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="moodle_course_list">Moodle Course</label>
                            <select class="form-control" name="moodle_course" id="moodle_course_list" v-model="moodle_course" @change="checkMoodleCourse">
                                <option value="0">Please select the course</option>
                                <option v-for="course in moodle_courses" v-bind:value="course.id">
                                    @{{ course.fullname }}
                                </option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group center">
                            <label for=""> </label><br>
                            <button class="btn btn-o btn-primary" :disabled="!allowAccess || busy" @click="syncCourse">
                                <i class="fa fa-spinner fa-spin" style="margin-right: 3px" v-if="busy"></i>
                                LINK/SYNC
                            </button>
                        </div>
                    </div>
                </div>

                
                
                {{-- <table>
                    <thead>
                        <th>Courses</th>
                    </thead>
                        <tr v-for="(index, course) in synced_courses" :key="course.id">
                            <td>@{{ index + 1 }}</td>
                            <td>@{{ course.title }}</td>
                        </tr>
                    </tbody>
                </table>  --}}
            </sync-course>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h3>Synced Course List</h3>
                    <table class="table table-striped" id="course-datatable">
                        <thead>
                            <th>Course</th>
                            <th></th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
@stop 


@section('scripts')
    <script src="/assets/themes/saaa/js/app.js"></script>
    @include('admin.members.includes.spin')
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    {{-- <script src="/admin/assets/js/index.js"></script> --}}
    <script>
        $('#course-datatable').DataTable({
            serverSide: true,
            ajax: '/admin/course/synced_list',
            rowId: 'id',
            columns: [
                {data: 'title'},                        
                {
                    data: "id",
                    "searchable": false,
                    "sortable": false,
                    "render": function (id, type, full, meta) {
                        return '<a href="/admin/courses/show/' + id + '" class="btn btn-sm btn-secondary btn-circle" style="background-color: #21679b; border-color: #21679b; color: white"><i class="fa fa-pencil"></i></a>';
                    }
                },
            ],
            'processing': true,
            'language': {
                'loadingRecords': '&nbsp;',
                'processing': '<div class="spinner"></div>'
            }
        });
    </script>
@stop
