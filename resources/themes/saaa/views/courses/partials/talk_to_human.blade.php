<div class="modal fade course_brochure_popup" id="talk_to_human_popup" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content brochure-popup-background">
            <div class="text-centre">
                {{-- <img src="/assets/themes/taxfaculty/img/ttf_logo.png" > --}}
            </div>
            <div class="modal-header brochure-popup-border">
                <h3 class="modal-title brochure-popup-title" id="exampleModalLabel">TALK TO A HUMAN</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -40px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                {!! Form::open(['method' => 'post', 'route' => 'courses.talk_to_human']) !!} 
                <input type="hidden" value="" name="course_id">
                <h4 class="modal-title brochure-popup-required-title">*Required fileds</h4><br/>
                <div class="row">
                    <div class="col-md-12  @if ($errors->has('name')) has-error @endif">
                        {!! Form::input('text', 'name', null, ['class' => 'form-control
                        brochure-popup-text','placeholder' => 'Name*', 'required' => 'required', 'maxlength' => 100])
                        !!}
                        @if ($errors->has('name'))
                        <p class="help-block">{{ $errors->first('name') }}</p> @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12  @if ($errors->has('email')) has-error @endif">
                        {!! Form::input('text', 'email', null, ['class' => 'form-control
                        brochure-popup-text','placeholder' => 'Email*', 'required' => 'required', 'maxlength' => 255])
                        !!}
                        @if ($errors->has('email'))
                        <p class="help-block">{{ $errors->first('email') }}</p> @endif
                    </div> 
                </div>
                <div class="row">
                    <div class="col-md-12  @if ($errors->has('mobile')) has-error @endif">
                        {!! Form::input('text', 'mobile', null, ['class' => 'form-control brochure-popup-text
                        cell','placeholder' => 'Contact number*', 'required' => 'required', 'maxlength' =>
                        15,'id'=>'cell']) !!}
                        @if ($errors->has('mobile'))
                        <p class="help-block">{{ $errors->first('mobile') }}</p> @endif
                    </div> 
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if(env('GOOGLE_RECAPTCHA_KEY'))
                        <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}">
                        </div>
                        @if ($errors->has('g-recaptcha-response'))
                        <p class="help-block">{{ $errors->first('g-recaptcha-response') }}</p> @endif
                        @endif
                    </div> 
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p>By consenting to receive communications, you agree to the use of your data as described in our privacy policy. You may opt out of receiving communications at any time</p>
                    </div> 
                </div>
            </div>
      
            <div class="modal-footer brochure-popup-footer">
                <button type="submit" class="btn btn-primary">Continue</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>