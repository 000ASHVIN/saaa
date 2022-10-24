<div id="question" class="tab-pane fade">
<?php   
    $question = config('sponsor_questions');
    if(isset($sponsor->question_id)){
        $que = explode(",", $sponsor->question_id);
    }else{
        $que = null;
    }
?>
<div class="form-group @if ($errors->has('name')) has-error @endif">
{!! Form::label('Question', 'Question') !!}
</div>
<div class="row">
    @foreach ($question as $key => $question )
        <div class="col-md-6">
            <div class="form-group @if ($errors->has('name')) has-error @endif">
                {!! Form::checkbox( 'question[]', $key ,isset($que)?in_array($key,$que):'',['class' => 'md-check', 'id' => $key] ) !!}
                {!! Form::label($question,  ucfirst(str_replace('_',' ',$question))) !!}
            </div> 
        </div>
    @endforeach
</div> 

<button class="btn btn-primary"><i class="fa fa-check"></i> {!! $button !!}</button>
</div>

