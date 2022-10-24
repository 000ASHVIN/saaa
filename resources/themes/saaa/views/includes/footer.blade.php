
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
@if(auth()->user() && auth()->user()->showSelfRenewPopup())
  @include('dashboard.includes.upcoming_renewal_popup');
  <script>
    $(document).ready(function(){
      setTimeout(() => {
        if($.cookie("upcoming_renewal_popup") != '{{ Carbon\Carbon::now()->format("Y-m-d") }}') {
          $('#upcoming_renewal_popup').modal('show');
          $.cookie("upcoming_renewal_popup", '{{ Carbon\Carbon::now()->format("Y-m-d") }}', { path: '/', secure: false });
        }
      }, 2000);
    });
  </script>
@endif

@yield('scripts')

@if($errors->any())
<script>
	_toastr("Oh snap, {{ $errors->first() }}","top-right","info",false);
</script>
@endif

{{-- newletter popup, set cookie --}}
@if (!auth()->user())
<script>

  $('#newsletter_popup_close_button').click(function() {
    document.cookie = "popup_close=close;";
  });

  function getCookie(name) {
      var nameEQ = name + "=";
      var ca = document.cookie.split(';');
      for(var i=0;i < ca.length;i++) {
          var c = ca[i];
          while (c.charAt(0)==' ') c = c.substring(1,c.length);
          if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
      }
      return null;
  }
  
  if(getCookie('popup_close') != 'close') {
    setTimeout(function(){
        $('#newsletter_popup_button').click()
    }, 10000);
  }
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

<script src="/assets/frontend/plugins/editor.summernote/summernote.js"></script>
<script src="/assets/frontend/js/intlTelInput.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script>
    // Initialise summernote for support ticket popup
    $(document).ready(function(){
      $('#news-leter-popup-form').submit(function(e) {
          $('#newsletter_popup_close_button').click();
      })

      var max=50;
      $('.price-clean p').each(function(){
          if($(this).height()>max){
              max=$(this).height();
          }
      })

      $('.price-clean p').each(function(){
          $(this).css('min-height',max+'px'); 
      })

      var input2 = document.querySelector("#cellform");
      if(input2!=null) {
          window.intlTelInput(input2, {
              autoHideDialCode: false,
              autoPlaceholder: "off",
              formatOnDisplay: true,
              hiddenInput: "full_number",
              initialCountry: "za",
              nationalMode: false,
              separateDialCode: true,
              utilsScript: "/assets/frontend/js/utils.js",
          });
      }
        setTimeout(() => {

            $('#support_ticket_summernote').summernote({
                height: 150,
                fontNames: ['Arial'],
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ]
            });

            $('#support_ticket_summernote').on('summernote.change',function(we, contents, $editable){
                $('#support_ticket_summernote').val(contents);
                $('#support_ticket_summernote').trigger('change');
            });

        }, 2000);

    })

    // Initialise mobile country code dropdown for support ticket popup
    var telInputInitialized = false;
    $(document).ready(function() {
        $('#need_help_button').on('click', function(){
            $('#support_ticket').modal('show');

            setTimeout(function(){
                if(!telInputInitialized) {
                    telInputInitialized = true;
                    var input = document.querySelector("#support_ticket_mobile");
                    if(input!=null) {
                        window.intlTelInput(input, {
                            autoHideDialCode: false,
                            autoPlaceholder: "off",
                            formatOnDisplay: true,
                            hiddenInput: "full_number",
                            initialCountry: "za",
                            nationalMode: false,
                            separateDialCode: true,
                            utilsScript: "/assets/frontend/js/utils.js",
                        });
                    }
                }
            }, 500);

        });
    });
</script>

{{-- <script type="text/javascript" src="https://assets.freshdesk.com/widget/freshwidget.js"></script>
<script type="text/javascript">
    FreshWidget.init("", {"queryString": "&widgetType=popup&formTitle=Need+Help%3F&submitTitle=Submit+Question&submitThanks=Thank+you+for+submitting+the+form%2C+we+will+respond+as+soon+as+possible.+", "utf8": "✓", "widgetType": "popup", "buttonType": "text", "buttonText": "Need Help?", "buttonColor": "white", "buttonBg": "#173175", "alignment": "2", "offset": "235px", "submitThanks": "Thank you for submitting the form, we will respond as soon as possible. ", "formHeight": "500px", "url": "https://saaccountingacademy.freshdesk.com"} );
</script>--}}
<script>
  function submitform(){
    var values = $('#add_user').val();
    if(values>100){
      swal({
        title: "Warning!",
        text: "You can add max 100 users only",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      
      return false;
    }
    swal({
      title: "Are you sure?",
      text: "Are you sure want to add "+values+" no of user to practice plan",
      icon: "warning",
      buttons: true,
      dangerMode: true,
      showCancelButton: true,
    },
    function(isConfirm){ 
                 
      if (isConfirm) {
        $('#add_user_form').submit();
          
      }
      swal.close();
  });
   
  }
  function updateBox(box){
    //  var val  =box.val();
    if(box.value == 'others'){
        $('input[name=other_province]').removeClass('hidden');
    }else{
        $('input[name=other_province]').addClass('hidden');
    }
  }
</script>
@if(auth()->check())
@if(auth()->user()->showPopup())
<script>
   $(document).ready(function(){
     if(window.location.pathname != "/dashboard/billing"){  
        setTimeout(() => {
            $('#Debit_order_button').modal('show');
        }, 3000); 
      }
    }); 
</script>
@endif

@if(auth()->user()->showCreditPopup())

<script>
  $(document).ready(function(){
    if(window.location.pathname != "/dashboard/billing"){  
       setTimeout(() => {
        swal({
          title: "Warning!",
          text: "Please update your credit card details",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
       }, 3000); 
     }
   }); 
</script>
@endif
@endif

@if (auth()->user())
<script>
    // Chat Box script

    $.ajax({
        type: 'POST',
        url: '/dashboard/chat/room/end',
        data: {
            "_token": "{{ csrf_token() }}"
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });

    hideChat(0);

    var i = 1;
    $('#prime').click(function() {
        toggleFab();
        scrollDown();
        if (i == 1) {
            createNewChatRoom();
            i++;
        }
    });


    //Toggle chat and links
    function toggleFab() {
        $('#prime').toggleClass('is-float');
        $('.chat').toggleClass('is-visible');
        $('.fab').toggleClass('is-visible');
        $("#icon_toggle").toggleClass('fa-paper-plane fa-close ');

        hideChat(1);
    }

    function hideChat(hide) {
        switch (hide) {
            case 0:
                $('.chat_fullscreen_loader').css('display', 'none');
                $('#chat_fullscreen').css('display', 'none');
                break;
            case 1:
                $('.chat_fullscreen_loader').css('display', 'block');
                $('#chat_fullscreen').css('display', 'block');
                break;
        }

    }




    var pusher = new Pusher('{{ env("PUSHER_KEY", "") }}', {
        cluster: 'ap2'
    });

    function createNewChatRoom() {
        $.get("{{ route('dashboard.chat.room.create') }}", function(res) {
            $('#chat_room_id').val(res.chat_room_id);
            bindPusher(res.chat_room_id)
        });
    }

    function appendMessage(message) {
        if (message.is_admin == '1') {
            $("#chat_head").html(message.author);
            $(".agent, .online").show();
            $("#chat_fullscreen").append('<span class="chat_msg_item chat_msg_item_admin">' + message.message + '</span>');
        } else {
            $("#chat_fullscreen").append('<span class="chat_msg_item chat_msg_item_user">' + message.message + ' <div class="status">' + message.date + '</div> </span>');
        }
    }


    // $.get("{{ route('dashboard.chat.messages') }}", function(messages) {
    //     $.each(messages, function(i, message) {
    //         appendMessage(message);
    //     });
    // });

    $("#chatSend").keyup(function(e) {
        if (e.keyCode == 13 && $("#chatSend").val().trim() != '') {
            sendMessage();
        }
    });

    $(".sendMessage").click(function() {
        if ($("#chatSend").val() != '') {
            sendMessage();
        }
    });

    function bindPusher(roomId, oldRoomId = '') {
        var channel = pusher.subscribe('chat');
        if (oldRoomId != '') {
            channel.unbind('room_' + oldRoomId);
        }
        channel.bind('room_' + roomId, function(data) {
            appendMessage(data.message);
            scrollDown();
        });
    }

    function sendMessage() {
        var message = $("#chatSend").val();
        // var author = $.cookie("realtime-chat-nickname");
        $.ajax({
            type: 'POST',
            url: '{{ route("dashboard.chat.message") }}',
            data: {
                "chat_room_id": $('#chat_room_id').val(),
                "message": message,
                "_token": "{{ csrf_token() }}",
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                if (res.new_room == 'yes') {
                    const oldRoomId = $('#chat_room_id').val();
                    const chatRoomId = res.room.id;
                    $('#chat_room_id').val(chatRoomId);
                    appendMessage(res.message);
                    bindPusher(chatRoomId, oldRoomId);
                }
                $("#chatSend").val("")
            }
        });
        scrollDown();
    }

    function scrollDown() {
        $container = $('#chat_fullscreen');
        $container[0].scrollTop = $container[0].scrollHeight;
        $container.animate({
            scrollTop: $container[0].scrollHeight
        }, "slow");
    }

    $(".end_chat").click(function() {
        $.ajax({
            type: 'POST',
            url: '/dashboard/chat/room/end/' + $('#chat_room_id').val(),
            data: {
                "chat_room_id": $('#chat_room_id').val(),
                "_token": "{{ csrf_token() }}",
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                if (res.success == 'success') {
                    $('.chat_msg_item').remove();
                    $(".agent, .online").hide();
                    $("#chat_head").html("{{ config('app.name') }}");
                    toggleFab();
                }
            }
        });
    });
</script>
@endif
</body>
</html>