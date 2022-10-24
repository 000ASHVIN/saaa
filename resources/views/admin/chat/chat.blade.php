@extends('admin.layouts.master')

@section('title', $room->user->first_name . ' ' . $room->user->last_name)
@section('description', 'Chat Messages')

@section('styles')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<style>
  .inbox_msg .container {
    max-width: 1170px;
    margin: auto;
  }

  .inbox_msg img {
    max-width: 100%;
  }

  .inbox_msg .inbox_people {
    background: #f8f8f8 none repeat scroll 0 0;
    float: left;
    overflow: hidden;
    width: 40%;
    border-right: 1px solid #c4c4c4;
  }

  .inbox_msg .inbox_msg {
    border: 1px solid #c4c4c4;
    clear: both;
    overflow: hidden;
  }

  .inbox_msg .top_spac {
    margin: 20px 0 0;
  }


  .inbox_msg .recent_heading {
    float: left;
    width: 40%;
  }

  .inbox_msg .srch_bar {
    display: inline-block;
    text-align: right;
    width: 60%;
  }

  .inbox_msg .headind_srch {
    padding: 10px 29px 10px 20px;
    overflow: hidden;
    border-bottom: 1px solid #c4c4c4;
  }

  .inbox_msg .recent_heading h4 {
    color: #05728f;
    font-size: 21px;
    margin: auto;
  }

  .inbox_msg .srch_bar input {
    border: 1px solid #cdcdcd;
    border-width: 0 0 1px 0;
    width: 80%;
    padding: 2px 0 4px 6px;
    background: none;
  }

  .inbox_msg .srch_bar .input-group-addon button {
    background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
    border: medium none;
    padding: 0;
    color: #707070;
    font-size: 18px;
  }

  .inbox_msg .srch_bar .input-group-addon {
    margin: 0 0 0 -27px;
  }

  .inbox_msg .chat_ib h5 {
    font-size: 15px;
    color: #464646;
    margin: 0 0 8px 0;
  }

  .inbox_msg .chat_ib h5 span {
    font-size: 13px;
    float: right;
  }

  .inbox_msg .chat_ib p {
    font-size: 14px;
    color: #989898;
    margin: auto
  }

  .inbox_msg .chat_img {
    float: left;
    width: 11%;
  }

  .inbox_msg .chat_ib {
    float: left;
    padding: 0 0 0 15px;
    width: 88%;
  }

  .inbox_msg .chat_people {
    overflow: hidden;
    clear: both;
  }

  .inbox_msg .chat_list {
    border-bottom: 1px solid #c4c4c4;
    margin: 0;
    padding: 18px 16px 10px;
  }

  .inbox_msg .inbox_chat {
    height: 550px;
    overflow-y: scroll;
  }

  .inbox_msg .active_chat {
    background: #ebebeb;
  }

  .inbox_msg .incoming_msg_img {
    display: inline-block;
    width: 6%;
  }

  .inbox_msg .received_msg {
    display: inline-block;
    padding: 0 0 0 10px;
    vertical-align: top;
    width: 92%;
  }

  .inbox_msg .received_withd_msg p {
    /* background: #ebebeb none repeat scroll 0 0; */
    border-radius: 3px;
    color: #646464;
    font-size: 14px;
    margin: 0;
    padding: 5px 10px 0px 12px;
    width: 100%;
  }

  .inbox_msg .time_date {
    color: #747474;
    display: block;
    font-size: 12px;
    margin: 8px 0 0 12px;
  }

  .inbox_msg .received_withd_msg {
    width: 57%;
    border-radius: 20px;
    background: #ebebeb none repeat scroll 0 0;
    padding: 4px 7px 7px 7px;
  }

  .inbox_msg .mesgs {
    float: left;
    padding: 30px 15px 0 25px;
    width: 100%;
  }

  .inbox_msg .sent_msg p {
    /* background: #05728f none repeat scroll 0 0; */
    border-radius: 3px;
    font-size: 14px;
    margin: 0;
    color: #fff;
    padding: 5px 10px 0px 12px;
    width: 100%;
  }

  .inbox_msg .outgoing_msg {
    overflow: hidden;
    margin: 26px 0 26px;
  }

  .time_sent{
    color: white !important;
  }

  .inbox_msg .sent_msg {
    float: right;
    width: 46%;
    border-radius: 20px;
    background: #05728f none repeat scroll 0 0;
    padding: 4px 7px 7px 7px;
  }

  .inbox_msg .input_msg_write input {
    background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
    border: medium none;
    color: #4c4c4c;
    font-size: 15px;
    min-height: 48px;
    width: 100%;
  }

  .inbox_msg .type_msg {
    border-top: 1px solid #c4c4c4;
    position: relative;
  }

  .inbox_msg .msg_send_btn {
    background: #05728f none repeat scroll 0 0;
    border: medium none;
    border-radius: 50%;
    color: #fff;
    cursor: pointer;
    font-size: 17px;
    height: 33px;
    position: absolute;
    right: 0;
    top: 11px;
    width: 33px;
  }

  .inbox_msg .messaging {
    padding: 0 0 50px 0;
  }

  .inbox_msg .msg_history {
    height: 400px;
    overflow-y: auto;
  }

  .inbox_msg .inbox_msg {
    /* display: flex;
    justify-content: center; */
  }
</style>
@endsection

@section('content')
<section>
  <div class="container-fluid container-fullw padding-bottom-10 bg-white">
    <div class="row">
      <div class="inbox_msg col-md-12">
        <div class="mesgs">
          <div class="msg_history">

            @foreach ($messages as $message)
            @if($message->is_admin == '0')
            <div class="incoming_msg">
              <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
              <div class="received_msg">
                <div class="received_withd_msg">
                  <p>{{$message->message}}</p>
                  <span class="time_date">{{$message->date}}</span>
                </div>
              </div>
            </div>
            @else
            <div class="outgoing_msg">
              <div class="sent_msg">
                <p>{{$message->message}}</p>
                <span class="time_date time_sent">{{$message->date}}</span>
              </div>
            </div>

            @endif
            @endforeach

          </div>
          <div class="type_msg">
            <div class="input_msg_write">
              <input type="text" class="write_msg" placeholder="Type a message" />
              <button class="msg_send_btn" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('scripts')
<script src="/admin/assets/js/index.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-timeago/1.4.0/jquery.timeago.min.js"></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
  jQuery(document).ready(function() {
    Main.init();
  });

  var pusher = new Pusher('{{ env("PUSHER_KEY", "") }}', {
    cluster: 'ap2'
  });
  // var channel = pusher.subscribe('my-channel');
  // channel.bind('my-event', function(data) {
  //     alert(JSON.stringify(data));
  // });
  var channel = pusher.subscribe('chat');
  channel.bind('room_{{$room->id}}', function(data) {
    var message = data.message

    if (message.is_admin == '0') {
      $(".msg_history").append('<div class="incoming_msg"> <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div> <div class="received_msg"> <div class="received_withd_msg"> <p>' + message.message + '</p> <span class="time_date"> '+message.date+'</span></div> </div> </div>');
    } else {
      $(".msg_history").append('<div class="outgoing_msg"><div class="sent_msg">  <p>' + message.message + '</p><span class="time_date time_sent"> '+ message.date +'</span> </div> </div>');
    }
    scrollDown();
  });

  $(".msg_send_btn").click(function() {
    if ($(".write_msg").val() != '') {
      sendMessage();
    }
  });

  $('.write_msg').keyup(function(e) {
    if (e.keyCode == 13 && $(".write_msg").val() != '') {
      sendMessage();
    }
  });

  function scrollDown() {
    $container = $('.msg_history');
    $container[0].scrollTop = $container[0].scrollHeight;
    $container.animate({
      scrollTop: $container[0].scrollHeight
    }, "slow");
  }
  scrollDown();

  function sendMessage() {
    var message = $(".write_msg").val();
    // var author = $.cookie("realtime-chat-nickname");

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      'url': '{{ route("admin.chat.message", $room->id)}}',
      'type': 'POST',
      'data': {
        message: message,
        is_admin: 1
      },
      'success': function(data) {
        $(".write_msg").val("")
      },
      'error': function() {
        console.error('Something wrong!');
      }
    });

    scrollDown();
  }
</script>
@stop