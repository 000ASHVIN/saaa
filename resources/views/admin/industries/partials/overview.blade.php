<div id="overview" class="tab-pane fade in active">
    <div class="row">
        <div class="col-md-12">
            {!! Form::model($industry, ['method' => 'Post', 'route' => ['admin.industries.update', $industry->id]]) !!}
                @include('admin.industries.partials.form', ['button' => 'Update Industry'])
            {!! Form::close() !!}
        </div>
    </div>
</div>