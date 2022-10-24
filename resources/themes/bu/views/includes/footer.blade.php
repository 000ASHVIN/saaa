
<a href="#" id="toTop" class="hidden-print"></a>


<!-- PRELOADER -->
<div id="preloader">
	<div class="inner">
		<span class="loader"></span>
	</div>
</div><!-- /PRELOADER -->

<!-- JAVASCRIPT FILES -->
<script type="text/javascript">var plugin_path = '/assets/frontend/plugins/';</script>
<script type="text/javascript" src="/assets/frontend/plugins/jquery/jquery-2.1.4.min.js"></script>

@include('vendor.sweet.alert')

{{--Preloader is in here--}}
<script type="text/javascript" src="/assets/frontend/js/scripts.js"></script>

<!-- REVOLUTION SLIDER -->
<script type="text/javascript" src="/assets/frontend/plugins/slider.revolution/js/jquery.themepunch.tools.min.js"></script>
<script type="text/javascript" src="/assets/frontend/plugins/slider.revolution/js/jquery.themepunch.revolution.min.js"></script>
<script type="text/javascript" src="/assets/frontend/js/view/demo.revolution_slider.js"></script>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC6JKWhndcoG5bQw3nrt3B4EUZd7tveY5E"></script>
<script type="text/javascript" src="/assets/frontend/plugins/gmaps.js"></script>

<script src="/assets/frontend/js/lada.min.js"></script>
<script src="/assets/frontend/js/spin.min.js"></script>
<script type="text/javascript" src="/assets/frontend/js/view/demo.shop.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/URI.js/1.15.2/URI.min.js"></script>
<script src="/assets/frontend/js/rsa_validator.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.min.js"></script>
<script src="https://js.pusher.com/4.3/pusher.min.js"></script>
<script src="//eft.ppay.io/ext/checkout/v2/checkout.js"></script>

<!-- CUSTOM JS -->
<script src="/assets/admin/vendor/lodash/lodash.min.js"></script>
<script src="{{ Theme::asset('js/app.js', null, true) }}"></script>
<script src="/assets/frontend/plugins/bootstrap.datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="/js/custom.js"></script>

<!-- confirm delete -->
<script src="/assets/admin/assets/js/bootstrap-confirm.js"></script>

@yield('scripts')

@if($errors->any())
<script>
	_toastr("Oh snap, {{ $errors->first() }}","top-right","info",false);
</script>
@endif

{{--@include('includes.maps')--}}
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-63646598-3', 'auto');
  ga('send', 'pageview');
</script>

<script>
    function spin(this1)
    {
        this1.disabled=true;
        this1.innerHTML=`<i style="padding-right: 0px;"class="fa fa-spinner fa-spin"></i>`;
        this1.form.submit();
    }
</script>

<script type="text/javascript" src="https://assets.freshdesk.com/widget/freshwidget.js"></script>
<script type="text/javascript">
    FreshWidget.init("", {"queryString": "&widgetType=popup&formTitle=Need+Help%3F&submitTitle=Submit+Question&submitThanks=Thank+you+for+submitting+the+form%2C+we+will+respond+as+soon+as+possible.+", "utf8": "✓", "widgetType": "popup", "buttonType": "text", "buttonText": "Need Help?", "buttonColor": "white", "buttonBg": "#800000", "alignment": "2", "offset": "235px", "submitThanks": "Thank you for submitting the form, we will respond as soon as possible. ", "formHeight": "500px", "url": "https://saaccountingacademy.freshdesk.com"} );
</script>
</body>
</html>