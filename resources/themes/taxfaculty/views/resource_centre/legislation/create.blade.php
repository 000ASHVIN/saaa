@extends('app')

@section('content')

<section>
    <div class="container">

        <div class="col-md-9">
            {!! Form::open(['method' => 'post']) !!}
            <div class="form-group @if ($errors->has('subject')) has-error @endif">
                {!! Form::label('subject', 'Subject') !!}
                {!! Form::input('text', 'subject', null, ['class' => 'form-control']) !!}
                @if ($errors->has('subject')) <p class="help-block">{{ $errors->first('subject') }}</p> @endif
            </div>

            <div class="form-group @if ($errors->has('tag')) has-error @endif">
                {!! Form::label('tag', 'Ticket Category') !!}
                {!! Form::select('tag', [
                    'accounting' => 'Accounting',
                    'tax' => 'Tax',
                    'ethics' => 'Ethics',
                    'auditing' => 'Auditing',
                    'practice_management' => 'Practise Management',
                    'reporting' => 'Reporting',
                    'legislation' => 'Legislation',
                    'information_technology' => 'Information Technology',
                ],null, ['class' => 'form-control']) !!}
                @if ($errors->has('tag')) <p class="help-block">{{ $errors->first('tag') }}</p> @endif
            </div>

            <div class="form-group @if ($errors->has('description')) has-error @endif">
                {!! Form::label('description', 'Ticket Description') !!}
                {!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'ticket']) !!}
                @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
            </div>

            {!! Form::submit('Start Conversation', ['class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}
        </div>

        <div class="col-md-3">
            <div class="alert alert-warning">
                <p>Please note that you can only respond to the ticket via email, Please keep in mind that your responses are public and will be viewed by the comunity for future answers.</p>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="/assets/frontend/plugins/editor.summernote/summernote.js"></script>
    <script>
        $('#ticket').summernote({
            height: 350,
            fontNames: ['Arial'],
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
            ]
        });
    </script>
@stop