<div class="form-group">
    {!! Form::label('question', 'The Question') !!}
    {!! Form::input('text', 'question', null, ['class' => 'form-control']) !!}

</div>

<div class="form-group">
    {!! Form::label('answer', 'The Answer') !!}
    {!! Form::textarea('answer', null, ['class' => 'form-control ckeditor']) !!}
</div>


<div class="form-group">
    <div class="form-group @if ($errors->has('start_date')) has-error @endif">
        {!! Form::label('date', 'Date') !!}
        {!! Form::input('text', 'date', null, ['class' => 'is-date form-control']) !!}
        @if ($errors->has('date')) <p class="help-block">{{ $errors->first('date') }}</p> @endif
    </div>
</div>

{{--
<div class="form-group">
    <div class="form-group">
        {!! Form::label('faq_tag_id', 'Please Select Tag') !!}
        {!! Form::select('faq_tag_id[]', $tags->pluck('title', 'id'), (isset($question))?(($question->faq_tags->count())?$question->faq_tags->pluck('id')->toArray():null):null, ['class' => 'form-control select2', 'style' => 'text-transform:capitalize', 'multiple' => true]) !!}
    </div> 
</div>
--}}

<div class="form-group">
    <?php
        $tags = '';
        if(isset($question)) {
            if($question->faq_tags->count()) {
                $tags = implode(',', $question->faq_tags->pluck('title')->toArray());
            }
        }
    ?>
    <div class="form-group">
        {!! Form::label('faq_tags', 'Please Select Tag') !!}
        {!! Form::input('text', 'faq_tags', $tags, ['class' => 'form-control', 'id'=>'faq_tags']) !!}
    </div> 
</div>

<div class="form-group">
    <div class="form-group">
        {!! Form::label('faq_type', 'FAQ Type') !!}
        {!! Form::select('faq_type', $faqTypes->pluck('name', 'type'), (isset($question))?(($question->categories->count())?$question->categories[0]->faq_type:null):null, ['class' => 'form-control select2', 'style' => 'text-transform:capitalize', 'placeholder'=>'FAQ Type']) !!}
    </div>
</div>

<div class="form-group">
    <div class="form-group">
        {!! Form::label('categories_list', 'FAQ Categories') !!}
        {!! Form::select('categories_list[]', $categories->pluck('title', 'id'), (isset($question))?(($question->categories->count())?$question->categories->pluck('id')->toArray():null):null, ['class' => 'form-control select2', 'style' => 'text-transform:capitalize', 'multiple' => true, 'id'=>'categories_list']) !!}
    </div>
</div>

