<div class="form-group @if ($errors->has('title')) has-error @endif">
    {!! Form::label('title', 'Category Title') !!}
    {!! Form::input('text', 'title', null, ['class' => 'form-control']) !!}
    @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('description')) has-error @endif">
    {!! Form::label('description', 'Description') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '10', 'col' => '5']) !!}
    @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('parent_id')) has-error @endif">
    {!! Form::label('parent_id', 'Please select Parent category') !!}
    {!! Form::select('parent_id', array('0'=>'Select Parent Category')+$categories, null, ['class' => 'form-control select2']) !!}
    @if ($errors->has('parent_id')) <p class="help-block">{{ $errors->first('parent_id') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('image')) has-error @endif">
    {!! Form::label('image', 'Image') !!}
    {!! Form::file('image', null, ['class' => 'form-control']) !!}
    <input type="hidden" name="remove_image" id="input_remove_image" value="">
    @if (isset($category) && $category->image)
        <p style="margin-top:5px; text-align:right;" id="category_image_preview">
            <a href="{{ asset('storage/'.$category->image) }}" target="_blank"><i class="fa fa-link"></i> Category Image</a>
            <a href="javascript:void(0);" id="remove_category_image" style="color:#AA0000"><i class="fa fa-times"></i></a>
        </p>
    @endif
    @if ($errors->has('image')) <p class="help-block">{{ $errors->first('image') }}</p> @endif
</div> 

{!! Form::submit($submit, ['class' => 'btn btn-success']) !!}