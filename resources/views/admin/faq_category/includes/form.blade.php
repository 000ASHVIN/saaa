<div class="form-group @if ($errors->has('category')) has-error @endif">
    {!! Form::label('category', 'Category') !!}
    {!! Form::select('category', $categories,null, ['class' => 'form-control', 'placeholder'=>'Category']) !!}
    @if ($errors->has('category')) <p class="help-block">{{ $errors->first('category') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('faq_category_type')) has-error @endif">
    {!! Form::label('faq_category_type', 'FAQ category Type') !!}
    {!! Form::select('faq_category_type', $faqCategories,null, ['class' => 'form-control', 'placeholder'=>'FAQ category type']) !!}
    @if ($errors->has('faq_category_type')) <p class="help-block">{{ $errors->first('faq_category_type') }}</p> @endif
</div>

<hr>
{!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
<a href="{{ route('faq.categories') }}" class="btn btn-warning">Back</a>
