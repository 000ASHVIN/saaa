<h4 class="text-center">You've purchased something from us, but we don't have a contact number for you.</h4>
<h5 style="margin-bottom: 0;">Please fill in your contact details below so we can get hold of you if we need to.</h5>
    {!! Former::open()->method('POST')->route('dashboard.update-contact-details')->addClass('no-margin-bottom') !!}
    {!! Former::text('cell','Contact number') !!}
    {!! Former::submit('Submit')->addClass('btn-primary pull-right') !!}
<br>
{!! Former::close() !!}