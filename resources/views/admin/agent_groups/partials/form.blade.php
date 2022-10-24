<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Group Name') !!}
    {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('active')) has-error @endif">
    {!! Form::label('agents', 'Group Members') !!}
    {!! Form::select('AgentsList[]', $agents->pluck('AgentIdentifier','id'),null, ['multiple'=>'multiple', 'class' => 'form-control select2']) !!}
    @if ($errors->has('AgentsList[]')) <p class="help-block">{{ $errors->first('AgentsList[]') }}</p> @endif
</div>

<div class="form-group">
    <div class="form-group">
        {!! Form::label('categories', 'Categories') !!}
        {!! Form::select('categories[]', $categories->pluck('title', 'id'), (isset($agentGroup))?(($agentGroup->categories->count())?$agentGroup->categories->pluck('id')->toArray():null):null, ['class' => 'form-control select2', 'multiple' => true, 'id'=>'categories']) !!}
    </div>
</div>

{!! Form::submit($button, ['class' => 'btn btn-success']) !!}