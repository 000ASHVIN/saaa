<div class="form-group @if ($errors->has('title')) has-error @endif">
    {!! Form::label('title', 'Title') !!}
    {!! Form::input('text', 'title', (isset($title) ? $title : ""), ['class' => 'form-control']) !!}
    @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('category')) has-error @endif">
    {!! Form::label('category', 'Search by category') !!}
    <select name="category" id="category" class="form-control">
        <option value="null">Please Select..</option>
        <option value="null">All an “All”</option>
        @foreach($categories as $cat)
            <option {{ isset($category)? ($category === $cat) ? 'selected' : "" : "" }} value="{{ $cat->id }}">{{ $cat->title }}</option>
        @endforeach
    </select>
    @if ($errors->has('category')) <p class="help-block">{{ $errors->first('category') }}</p> @endif
</div>

<button class="btn btn-primary btn-block" onclick="spin(this)"><i class="fa fa-search"></i> Search</button>