<style>
  .newsletter-link {
    font-weight: 500;
  }
  .newsletter-link:focus, .newsletter-link:hover {
    color: #173175;
  }
</style>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" id="newsletter_popup_button" data-toggle="modal" data-target="#newsletter_popup" style="display: none;">
    Newsletter
  </button>
  
  <!-- Modal -->
  <div class="modal fade" id="newsletter_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle"><h4 class="letter-spacing-1" style="margin: 0 !important">KEEP IN TOUCH</h4></h5>
          <button type="button" class="close" data-dismiss="modal" id="newsletter_popup_close_button" aria-label="Close" style="margin-top: -23px;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
             <!-- Newsletter Form -->
             
             <p>Subscribe to our mailing list and you will receive our best posts every week on our live <a target="_blank" style="color: #173175 !important;" href="{{ url('/') }}/events" class="newsletter-link">CPD webinars</a>, <a target="_blank" style="color: #173175 !important;" href="{{ url('/') }}/courses" class="newsletter-link">upcoming courses</a> and <a target="_blank" style="color: #173175 !important;" href="{{ url('/') }}/webinars_on_demand" class="newsletter-link">webinars-on-demand</a></p>

             {{--<form action="https://bulkro.com/admin/subscribe" method="POST" accept-charset="utf-8" id="subscribe_form">--}}
                {{--<div class="form-inline">--}}
                    {{--<div class="form-group">--}}
                        {{--<input type="text" class="form-control" name="name" id="name"/>--}}
                        {{--<input type="text" class="form-control" name="email" id="email"/>--}}
                    {{--</div>--}}
                    {{--<input type="hidden" name="list" value="u8oFBpC7BPZMmMbdjV8U9g"/>--}}
                {{--</div>--}}
                 {{--<input type="submit" class="btn btn-primary" value="subscribe" name="Send" id="submit"/>--}}
             {{--</form>--}}

             <form class="validate" action="/newsletter/subscribe" method="post"
                   data-success="Subscribed! Thank you!" data-toastr-position="bottom-right" id="news-leter-popup-form">
                 {!! csrf_field() !!}

                 <div style="display: flex">
                     <input type="text" class="form-control required" name="first_name" style="margin: 2px 2px 5px;" placeholder="First Name">
                     <input type="text" class="form-control" name="last_name" style="margin: 2px 2px 5px;" placeholder="Last Name">
                 </div>

                 <div style="display: flex">
                     <input type="email" id="email" name="email" class="form-control required" placeholder="Enter your Email" style="margin: 2px 2px 5px;">
                 </div>
                 <div class="telno">
                     <input type="text" id="cellform_popup" name="cell" class="form-control required"
                            placeholder="Enter your Mobile no" style="margin: 2px 2px 5px;">
                 </div>
                 @if(env('GOOGLE_RECAPTCHA_KEY'))	
                 <div class="row" >	
                    <div class="col-md-12">	
                        <div class="g-recaptcha"	
                            data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}">	
                        </div>	
                    </div>	
                </div>
                @endif
                <div>
                  <p style="margin-top: 10px;">By subscribing to our mailing list you accept our <a href="{{ route('terms_and_conditions') }}" target="_blank" class="newsletter-link terms" style="color: #173175;">Terms of Service</a> and <a href="{{ route('privacy_policy') }}" target="_blank" class="newsletter-link privacy" style="color: #173175;">Privacy Policy</a></p>
                </div>
                 <div class="input-group-addon" style="border: none; padding: 2px; color: white; background-color: #173175; border-color: #173175;">
                     <button class="btn btn-success btn" style="border: none; padding: 2px; color: white; background-color: #173175; border-color: #173175;" type="submit"> <i class="fa fa-envelope"></i> Subscribe to newsletter </button>
                 </div>
             </form>
             <!-- /Newsletter Form -->
            
        </div>
        {{-- <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div> --}}
      </div>
    </div>
  </div>