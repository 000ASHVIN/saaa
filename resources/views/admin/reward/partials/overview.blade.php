<div id="overview" class="tab-pane fade in active">
    <div class="row">
        <div class="col-md-12">
            {!! Form::model($reward, ['method' => 'Post', 'route' => ['admin.rewards.update', $reward->id]]) !!}
                @include('admin.reward.partials.form', ['button' => 'Update Rewards'])
            {!! Form::close() !!}
        </div>
    </div>
</div>