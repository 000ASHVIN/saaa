<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('date','CPD Date') !!}
            {!! Form::input('text', 'dateFormatted', null, ['class' => 'form-control cpd_date']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('hours','CPD Hours') !!}
            {!! Form::input('text','hours', null, ['class' => 'form-control', 'placeholder' => 'Numeric value. Eg. 1 for 1 hour']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('source', 'Topic') !!}
            {!! Form::input('text', 'source', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('service_provider', 'Service provider') !!}
            {!! Form::input('text', 'service_provider', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('verifiable')) has-error @endif">
            {!! Form::label('verifiable', 'Verifiable') !!}
            {!! Form::select('verifiable', [
                true => 'Yes',
                false => 'No'
            ],null, ['class' => 'form-control']) !!}
            @if ($errors->has('verifiable')) <p class="help-block">{{ $errors->first('verifiable') }}</p> @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group @if ($errors->has('category')) has-error @endif">
            {!! Form::label('category', 'Category') !!}
            {!! Form::select('category', [
                'tax' => 'Tax',
                'ethics' => 'Ethics',
                'auditing' => 'Auditing',
                'accounting' => 'Accounting',
            ],null, ['class' => 'form-control']) !!}
            @if ($errors->has('category')) <p class="help-block">{{ $errors->first('category') }}</p> @endif
        </div>
    </div>

    <div class="col-md-12">
        {!! Form::label('attachment', 'Attachment') !!}
        <div class="fancy-file-upload">
            <i class="fa fa-upload"></i>
            <input type="file" class="form-control" name="attachment" onchange="jQuery(this).next('input').val(this.value);">
            <input type="text" class="form-control" placeholder="no file selected" readonly="">
            <span class="button">Choose File</span>
        </div>
    </div>


    <div class="col-md-12">
        <br>
        <br>
        <div class="form-group">
            <div class="text-center">
                <button class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                <button class="btn btn-primary" onclick="spin(this)"><i class="fa fa-check"></i> {{ $submit }}</button>
            </div>
        </div>
    </div>
</div>