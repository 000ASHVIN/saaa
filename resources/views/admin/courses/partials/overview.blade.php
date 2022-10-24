<div id="overview" class="tab-pane fade in active">
    <div class="row">
        <div class="col-md-12">
            {!! Form::model($course, ['method' => 'Post', 'route' => ['admin.courses.update', $course->id], 'enctype' => 'multipart/form-data']) !!}
                @include('admin.courses.partials.form', ['button' => 'Update Course'])
            {!! Form::close() !!}
        </div>
    </div>
</div>