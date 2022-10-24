<div class="form-group @if ($errors->has('title')) has-error @endif">
    {!! Form::label('title', 'Article Title') !!}
    {!! Form::input('text', 'title', null, ['class' => 'form-control']) !!}
    @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('draft')) has-error @endif">
    <small class="pull-right" style="font-style: italic">Drafts will not be displayed on the website.</small>
    {!! Form::label('draft', 'Is this a draft ?') !!}
    {!! Form::select('draft', [
        true => 'Yes',
        false => 'No'
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('draft')) <p class="help-block">{{ $errors->first('draft') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('short_description')) has-error @endif">
    <small style="float: right; font-style: italic;">This will be displayed under the heading for articles</small>
    {!! Form::label('short_description', 'Short Description') !!}
    {!! Form::textarea('short_description', null, ['class' => 'form-control', 'rows' => '5', 'col' => '10', 'maxlength' => '250']) !!}
    @if ($errors->has('short_description')) <p class="help-block">{{ $errors->first('short_description') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('description')) has-error @endif">
    {!! Form::label('description', 'Description') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control ckeditor']) !!}
    @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('author_list[]')) has-error @endif">
    {!! Form::label('author_list[]', 'Please select your author') !!}
    {!! Form::select('author_list[]', $authors, null, ['class' => 'form-control select2']) !!}
    @if ($errors->has('author_list[]')) <p class="help-block">{{ $errors->first('author_list[]') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('category_list[]')) has-error @endif">
    {!! Form::label('category_list[]', 'Please select your category') !!}
    {!! Form::select('category_list[]', $categories, null, ['class' => 'form-control select2']) !!}
    @if ($errors->has('category_list[]')) <p class="help-block">{{ $errors->first('category_list[]') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('image')) has-error @endif">
    {!! Form::label('image', 'Article Image') !!}
    {!! Form::file('image', null, ['class' => 'form-control']) !!}
    @if ($errors->has('image')) <p class="help-block">{{ $errors->first('image') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('publish_date')) has-error @endif">
    {!! Form::label('publish_date', 'Publish Date') !!}
    {!! Form::input('text', 'publish_date', null, ['class' => 'is-date form-control']) !!}
    @if ($errors->has('publish_date')) <p class="help-block">{{ $errors->first('publish_date') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('publish_time')) has-error @endif">
    {!! Form::label('publish_time', 'Publish Time') !!}
    {!! Form::input('text', 'publish_time', null, ['class' => 'form-control timepicker']) !!}
    @if ($errors->has('publish_time')) <p class="help-block">{{ $errors->first('publish_time') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('keyword')) has-error @endif">
    {!! Form::label('keyword', 'Keyword') !!}
    {!! Form::input('text', 'keyword', null, ['class' => 'form-control']) !!}
    @if ($errors->has('keyword')) <p class="help-block">{{ $errors->first('keyword') }}</p> @endif
</div>

  
<div class="form-group @if ($errors->has('meta_description')) has-error @endif">
    {!! Form::label('meta_description', 'Meta Description') !!}
    {!! Form::textarea('meta_description', null, ['class' => 'form-control']) !!}
    @if ($errors->has('meta_description')) <p class="help-block">{{ $errors->first('meta_description') }}</p> @endif
</div>


<button type="submit" class="btn btn-primary">{{ $submit }}</button>