<style>
  @import url(https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic&subset=latin,cyrillic);
 /* @import url(https://zavoloklom.github.io/material-design-iconic-font/css/docs.md-iconic-font.min.css); */
 
 .fabs ul li{
   list-style: none;
 }
 .fabs {
   bottom: 0;
   position: fixed;
   margin: 1em;
   right: 0;
   z-index: 998;
   
 }
 
 .fabs .fab {
   display: block;
   width: 56px;
   height: 56px;
   border-radius: 50%;
   text-align: center;
   color: #f0f0f0;
   margin: 25px auto 0;
   box-shadow: 0 0 4px rgba(0, 0, 0, .14), 0 4px 8px rgba(0, 0, 0, .28);
   cursor: pointer;
   -webkit-transition: all .1s ease-out;
   transition: all .1s ease-out;
   position: relative;
   z-index: 998;
   overflow: hidden;
   background: #8cc03c;
 }
 
 .fabs .fab > i {
   font-size: 2em;
   line-height: 55px;
   -webkit-transition: all .2s ease-out;
   -webkit-transition: all .2s ease-in-out;
   transition: all .2s ease-in-out;
 }
 
 .fabs .fab:not(:last-child) {
   width: 0;
   height: 0;
   margin: 20px auto 0;
   opacity: 0;
   visibility: hidden;
   line-height: 40px;
 }
 
 .fabs .fab:not(:last-child) > i {
   font-size: 1.4em;
   line-height: 40px;
 }
 
 .fabs .fab:not(:last-child).is-visible {
   width: 40px;
   height: 40px;
   margin: 15px auto 10;
   opacity: 1;
   visibility: visible;
 }
 
 .fabs .fab:nth-last-child(1) {
   -webkit-transition-delay: 25ms;
   transition-delay: 25ms;
 }
 
 .fabs .fab:not(:last-child):nth-last-child(2) {
   -webkit-transition-delay: 20ms;
   transition-delay: 20ms;
 }
 
 .fabs .fab:not(:last-child):nth-last-child(3) {
   -webkit-transition-delay: 40ms;
   transition-delay: 40ms;
 }
 
 .fabs .fab:not(:last-child):nth-last-child(4) {
   -webkit-transition-delay: 60ms;
   transition-delay: 60ms;
 }
 
 .fabs .fab:not(:last-child):nth-last-child(5) {
   -webkit-transition-delay: 80ms;
   transition-delay: 80ms;
 }
 
 .fab(:last-child):active,
 .fab(:last-child):focus,
 .fab(:last-child):hover {
   box-shadow: 0 0 6px rgba(0, 0, 0, .16), 0 6px 12px rgba(0, 0, 0, .32);
 }
 /*Chatbox*/
 
 .fabs .chat {
   position: fixed;
   right: 85px;
   bottom: 20px;
   width: 400px;
   font-size: 12px;
   line-height: 22px;
   font-family: 'Roboto';
   font-weight: 500;
   -webkit-font-smoothing: antialiased;
   font-smoothing: antialiased;
   opacity: 0;
   box-shadow: 1px 1px 100px 2px rgba(0, 0, 0, 0.22);
   border-radius: 10px;
   -webkit-transition: all .2s ease-out;
   -webkit-transition: all .2s ease-in-out;
   transition: all .2s ease-in-out;
 }
 
 .fabs .chat_fullscreen {
     position: fixed;
     right: 0px;
     bottom: 0px;
     top: 0px;
   }
 .fabs .chat_header {
       /* margin: 10px; */
     font-size: 13px;
     font-family: 'Roboto';
     font-weight: 500;
     color: #f3f3f3;
     height: 55px;
     background: #8cc03c;
     border-top-left-radius: 10px;
     border-top-right-radius: 10px;
     padding-top: 8px;
 }
 .fabs .chat_header2 {
       /* margin: 10px; */
     border-top-left-radius: 0px;
     border-top-right-radius: 0px;
 }
 .fabs .chat_header .span {
   float:right;
 }
 
 .fabs .chat_fullscreen_loader {
   display: none;
     float: right;
     cursor: pointer;
     /* margin: 10px; */
     font-size: 20px;
     opacity: 0.5;
     /* padding: 20px; */
     margin: -10px 10px;
 }
 
 .fabs .chat.is-visible {
   opacity: 1;
   -webkit-animation: zoomIn .2s cubic-bezier(.42, 0, .58, 1);
   animation: zoomIn .2s cubic-bezier(.42, 0, .58, 1);
 }
 .fabs .is-hide{
   opacity: 0
 }
 
 .fabs .chat_option {
   float: left;
   font-size: 15px;
   list-style: none;
   position: relative;
   height: 100%;
   width: 100%;
   text-align: relative;
   margin-left: 20px;
       letter-spacing: 0.5px;
       font-weight: 400
 }
 
 
 .fabs .chat_option img {
     border-radius: 50%;
     width: 55px;
     float: left;
     margin: -30px 20px 10px 20px;
     border: 4px solid rgba(0, 0, 0, 0.21);
 }
 
 .fabs .change_img img{
     width: 35px;
     margin: 0px 20px 0px 20px;
 }
 .fabs .chat_option .agent {
   font-size: 12px;
     font-weight: 300;
 }
 .fabs .chat_option .online{
       opacity: 0.4;
     font-size: 11px;
     font-weight: 300;
 }
 .fabs .chat_color {
   display: block;
   width: 20px;
   height: 20px;
   border-radius: 50%;
   margin: 10px;
   float: left;
 }
 
 
 .fabs .chat_body {
   background: #fff;
   width: 100%;
 
   display: inline-block;
   text-align: center;
     overflow-y: auto;
 
 }
 .fabs #chat_body{
     height: 450px;
 }
 .fabs .chat_login p,.fabs .chat_body li, .fabs p, .fabs a {
     -webkit-animation: zoomIn .5s cubic-bezier(.42, 0, .58, 1);
   animation: zoomIn .5s cubic-bezier(.42, 0, .58, 1);
 }
 .fabs .chat_body p {
   padding: 20px;
   color: #888
 }
 .fabs .chat_body a {
   width: 10%;
   text-align: center;
   border: none;
   box-shadow: none;
   line-height: 40px;
   font-size: 15px;
 }
 
 
 
 .fabs .chat_field {
   position: relative;
   margin: 5px 0 5px 0;
   width: 50%;
   font-family: 'Roboto';
   font-size: 12px;
   line-height: 30px;
   font-weight: 500;
   color: #4b4b4b;
   -webkit-font-smoothing: antialiased;
   font-smoothing: antialiased;
   border: none;
   outline: none;
   display: inline-block;
 }
 
 .fabs .chat_field.chat_message {
   height: 30px;
   resize: none;
       font-size: 13px;
     font-weight: 400;
 }
 .fabs .chat_category{
   text-align: left;
 }
 
 .fabs .chat_category{
   margin: 20px;
   background: rgba(0, 0, 0, 0.03);
   padding: 10px;
 }
 
 .fabs .chat_category ul li{
     width: 80%;
     height: 30px;
     background: #fff;
     padding: 10px;
     box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
     margin-bottom: 10px;
     border-radius: 3px;
     border: 1px solid #e0e0e0;
     font-size: 13px;
     cursor: pointer;
     line-height: 30px;
     color: #888;
     text-align: center;
 }
 
 .fabs .chat_category li:hover{
     background: #83c76d;
     color: #fff;
 }
 .fabs .chat_category li.active{
     background: #83c76d;
     color: #fff;
 }
 
 .fabs .tags{
  margin: 20px;
     bottom: 0px;
     display: block;
     width: 120%
 }
 .fabs .tags li{
     padding: 5px 10px;
     border-radius: 40px;
     border: 1px solid rgb(3, 117, 208);
     margin: 5px;
     display: inline-block;
     color: rgb(3, 117, 208);
     cursor: pointer;
 
 }
 .fabs .fab_field {
   width: 100%;
   display: inline-block;
   text-align: center;
   background: #fff;
   border-top: 1px solid #eee;
   border-bottom-right-radius: 10px;
     border-bottom-left-radius: 10px;
 
 }
 .fabs .fab_field2 {
     bottom: 0px;
     position: absolute;
     border-bottom-right-radius: 0px;
     border-bottom-left-radius: 0px;
         z-index: 999;
   }
 
 .fabs .fab_field a {
   display: inline-block;
   text-align: center;
 }
 
 .fabs #fab_camera {
   float: left;
   background: rgba(0, 0, 0, 0);
 }
 
 .fabs #fab_send {
   float: right;
   background: rgba(0, 0, 0, 0);
 }
 
 .fabs .fab_field .fab {
   width: 35px;
   height: 35px;
   box-shadow: none;
   margin: 5px;
 }
 
 .fabs .fab_field .fab>i {
   font-size: 1.6em;
   line-height: 35px;
   color: #bbb;
 }
 .fabs .fab_field .fab>i:hover {
   color: #42a5f5;
 }
 .fabs .chat_conversion {
 
 }
 
 .fabs .chat_converse {
   position: relative;
     background: #fff;
     margin: 0px 0 0px 0;
     height: 300px;
     min-height: 0;
     font-size: 12px;
     font-family: 'Open Sans',Arial,Helvetica,sans-serif;
     line-height: 18px;
     overflow-y: auto;
     width: 100%;
     float: right;
     overflow-x: hidden;
     /* padding-bottom: 100px; */
 }
 .fabs .chat_converse2{
       height: 100%;
     max-height: 800px
 }
 .fabs .chat_list {
   opacity: 0;
   visibility: hidden;
   height: 0;
 }
 
 .fabs .chat_list .chat_list_item {
   opacity: 0;
   visibility: hidden;
 }
 
 .fabs .chat .chat_converse .chat_msg_item {
   position: relative;
   margin: 8px 0 20px 0;
   padding: 8px 10px;
   max-width: 60%;
   display: block;
   word-wrap: break-word;
   border-radius: 20px;
   -webkit-animation: zoomIn .5s cubic-bezier(.42, 0, .58, 1);
   animation: zoomIn .5s cubic-bezier(.42, 0, .58, 1);
   clear: both;
   z-index: 999;
 }
 .fabs .status {
     /* margin: 45px -75px 0 0; */
     /* margin: 45px -45px 0 0;
     float: right; */
    font-size: 11px;
    opacity: 0.3;
    position: absolute;
    bottom: -22px;
    right: 0;
    color: #666;
    width: 76px;
 }
 .fabs .status2 {
     margin: -10px 20px 0 0;
     float: right;
     display: block;
     font-size: 11px;
     opacity: 0.3;
 }
 .fabs .chat .chat_converse .chat_msg_item .chat_avatar {
   position: absolute;
   top: 0;
 }
 
 .fabs .chat .chat_converse .chat_msg_item.chat_msg_item_admin .chat_avatar {
   left: -52px;
   background: rgba(0, 0, 0, 0.03);
 }
 
 .fabs .chat .chat_converse .chat_msg_item.chat_msg_item_user .chat_avatar {
   right: -52px;
   background: rgba(0, 0, 0, 0.6);
 }
 
 .fabs .chat .chat_converse .chat_msg_item .chat_avatar, .chat_avatar img{
   width: 40px;
   height: 40px;
   text-align: center;
   border-radius: 50%;
 }
 
 .fabs .chat .chat_converse .chat_msg_item.chat_msg_item_admin {
   /* margin-left: 60px; */
   margin-left: 20px;
   float: left;
   background: rgba(0, 0, 0, 0.03);
   color: #666;
 }
 
 .fabs .chat .chat_converse .chat_msg_item.chat_msg_item_user {
   margin-right: 20px;
   float: right;
   background: #8cc03c;
   color: #eceff1;
   /* min-width: 75px; */
   /* text-align: center; */
   position: relative;
 }
 
 .fabs .chat .chat_converse .chat_msg_item.chat_msg_item_admin:before {
   content: '';
   position: absolute;
   top: 15px;
   left: -12px;
   z-index: 998;
   border: 6px solid transparent;
   border-right-color: rgba(255, 255, 255, .4);
 }
 
 .fabs .chat_form .get-notified label{
     color: #077ad6;
     font-weight: 600;
     font-size: 11px;
 }
 
 .fabs input {
   position: relative;
   width: 90%;
   font-family: 'Roboto';
   font-size: 12px;
   line-height: 20px;
   font-weight: 500;
   color: #4b4b4b;
   -webkit-font-smoothing: antialiased;
   font-smoothing: antialiased;
   outline: none;
   background: #fff;
   display: inline-block;
   resize: none;
   padding: 5px;
   border-radius: 3px;
 }
 .fabs .chat_form .get-notified input {
   margin: 2px 0 0 0;
   border: 1px solid #83c76d;
 }
 .fabs .chat_form .get-notified i {
     background: #83c76d;
     width: 30px;
     height: 32px;
     font-size: 18px;
     color: #fff;
     line-height: 30px;
     font-weight: 600;
     text-align: center;
     margin: 2px 0 0 -30px;
     position: absolute;
     border-radius: 3px;
 }
 .fabs .chat_form .message_form {
   margin: 10px 0 0 0;
 }
 .fabs .chat_form .message_form input{
   margin: 5px 0 5px 0;
   border: 1px solid #e0e0e0;
 }
 .fabs .chat_form .message_form textarea{
   margin: 5px 0 5px 0;
   border: 1px solid #e0e0e0;
   position: relative;
   width: 90%;
   font-family: 'Roboto';
   font-size: 12px;
   line-height: 20px;
   font-weight: 500;
   color: #4b4b4b;
   -webkit-font-smoothing: antialiased;
   font-smoothing: antialiased;
   outline: none;
   background: #fff;
   display: inline-block;
   resize: none;
   padding: 5px;
   border-radius: 3px;
 }
 .fabs .chat_form .message_form button{
     margin: 5px 0 5px 0;
     border: 1px solid #e0e0e0;
     position: relative;
     width: 95%;
     font-family: 'Roboto';
     font-size: 12px;
     line-height: 20px;
     font-weight: 500;
     color: #fff;
     -webkit-font-smoothing: antialiased;
     font-smoothing: antialiased;
     outline: none;
     background: #fff;
     display: inline-block;
     resize: none;
     padding: 5px;
     border-radius: 3px;
     background: #83c76d;
     cursor: pointer;
 }
 .fabs strong.chat_time {
   padding: 0 1px 1px 0;
   font-weight: 500;
   font-size: 8px;
   display: block;
 }
 
 /*Chatbox scrollbar*/
 
 .fabs ::-webkit-scrollbar {
   width: 6px;
 }
 
 .fabs ::-webkit-scrollbar-track {
   border-radius: 0;
 }
 
 .fabs ::-webkit-scrollbar-thumb {
   margin: 2px;
   border-radius: 10px;
   background: rgba(0, 0, 0, 0.2);
 }
 /*Element state*/
 
 .fabs .is-active {
   -webkit-transform: rotate(180deg);
   transform: rotate(180deg);
   -webkit-transition: all 1s ease-in-out;
   transition: all 1s ease-in-out;
 }
 
 .fabs .is-float {
   box-shadow: 0 0 6px rgba(0, 0, 0, .16), 0 6px 12px rgba(0, 0, 0, .32);
 }
 
 .fabs .is-loading {
   display: block;
   -webkit-animation: load 1s cubic-bezier(0, .99, 1, 0.6) infinite;
   animation: load 1s cubic-bezier(0, .99, 1, 0.6) infinite;
 }
 /*Animation*/
 
 @-webkit-keyframes zoomIn {
   0% {
     -webkit-transform: scale(0);
     transform: scale(0);
     opacity: 0.0;
   }
   100% {
     -webkit-transform: scale(1);
     transform: scale(1);
     opacity: 1;
   }
 }
 
 @keyframes zoomIn {
   0% {
     -webkit-transform: scale(0);
     transform: scale(0);
     opacity: 0.0;
   }
   100% {
     -webkit-transform: scale(1);
     transform: scale(1);
     opacity: 1;
   }
 }
 
 @-webkit-keyframes load {
   0% {
     -webkit-transform: scale(0);
     transform: scale(0);
     opacity: 0.0;
   }
   50% {
     -webkit-transform: scale(1.5);
     transform: scale(1.5);
     opacity: 1;
   }
   100% {
     -webkit-transform: scale(1);
     transform: scale(1);
     opacity: 0;
   }
 }
 
 @keyframes load {
   0% {
     -webkit-transform: scale(0);
     transform: scale(0);
     opacity: 0.0;
   }
   50% {
     -webkit-transform: scale(1.5);
     transform: scale(1.5);
     opacity: 1;
   }
   100% {
     -webkit-transform: scale(1);
     transform: scale(1);
     opacity: 0;
   }
 }
 /* SMARTPHONES PORTRAIT */
 
 @media only screen and (min-width: 300px) {
   .fabs .chat {
     width: 250px;
   }
 }
 /* SMARTPHONES LANDSCAPE */
 
 @media only screen and (min-width: 480px) {
   .fabs .chat {
     width: 300px;
   }
   .fabs .chat_field {
     width: 65%;
   }
 }
 /* TABLETS PORTRAIT */
 
 @media only screen and (min-width: 768px) {
   .fabs .chat {
     width: 300px;
   }
   .fabs .chat_field {
     width: 65%;
   }
 }
 /* TABLET LANDSCAPE / DESKTOP */
 
 @media only screen and (min-width: 1024px) {
   .fabs .chat {
     width: 300px;
   }
   .fabs .chat_field {
     width: 65%;
   }
 }
 /*Color Options*/
 
 
 
 .fabs .blue .fab {
   background: #42a5f5;
   color: #fff;
 }
 
 
 
 .fabs .blue .chat {
   background: #42a5f5;
   color: #999;
 }
 
 
 /* Ripple */
 
 .fabs .ink {
   display: block;
   position: absolute;
   background: rgba(38, 50, 56, 0.4);
   border-radius: 100%;
   -moz-transform: scale(0);
   -ms-transform: scale(0);
   webkit-transform: scale(0);
   -webkit-transform: scale(0);
           transform: scale(0);
 }
 /*animation effect*/
 
 .fabs .ink.animate {
   -webkit-animation: ripple 0.5s ease-in-out;
           animation: ripple 0.5s ease-in-out;
 }
 
 @-webkit-keyframes ripple {
   /*scale the element to 250% to safely cover the entire link and fade it out*/
   
   100% {
     opacity: 0;
     -moz-transform: scale(5);
     -ms-transform: scale(5);
     webkit-transform: scale(5);
     -webkit-transform: scale(5);
             transform: scale(5);
   }
 }
 
 @keyframes ripple {
   /*scale the element to 250% to safely cover the entire link and fade it out*/
   
   100% {
     opacity: 0;
     -moz-transform: scale(5);
     -ms-transform: scale(5);
     webkit-transform: scale(5);
     -webkit-transform: scale(5);
             transform: scale(5);
   }
 }
 ::-webkit-input-placeholder { /* Chrome */
   /* color: #bbb; */
 }
 :-ms-input-placeholder { /* IE 10+ */
   /* color: #bbb; */
 }
 ::-moz-placeholder { /* Firefox 19+ */
   /* color: #bbb; */
 }
 :-moz-placeholder { /* Firefox 4 - 18 */
   /* color: #bbb; */
 }
 .fabs .chat_munu_bar {
  float: right;
    cursor: pointer;
    font-size: 20px;
    opacity: 0.5;
    margin-top: -10px;
    margin-right: 35px;
 }

 .chat_munu_bar {
   position: relative;
 }
 .chat_munu_bar .sub-menu {
   position: absolute;
   top: -5px;
    right: 18px;
    width: 101px;
    display: none;
 }
 .chat_munu_bar .sub-menu li {
  padding: 4px;
    background: red;
    padding-bottom: 5px;
    border-radius: 5px;
 }
 .chat_munu_bar .sub-menu li a {
   color: white;
   font-size: 12px;
 }
 .chat_munu_bar:hover .sub-menu {
  display: block;
 }
 </style>
 <div class="fabs">
     <div class="chat">
       <div class="chat_header">
        <div class="chat_option">
          <span id="chat_head">The Tax Faculty</span> <br> 
          <span class="agent">Agent</span> <span class="online">(Online)</span>
         <span id="chat_fullscreen_loader" class="chat_munu_bar">
           <i class="fa fa-bars"></i>
           <ul class="sub-menu">
             <li class="end_chat"><a href="#">End Chat</a></li>
           </ul>
         </span>
  
        </div>
   
       </div>
       
         <div id="chat_fullscreen" class="chat_conversion chat_converse">
             {{-- <span class="chat_msg_item chat_msg_item_admin">
                 Hey there! Any question?
             </span>
 
             <span class="chat_msg_item chat_msg_item_user">
                 Hello! How are you
                 <div class="status">13:11 Dec 06</div>
             </span> --}}
             
         </div>
       <div class="fab_field">
         <a id="fab_send" class="fab sendMessage"><i class="fa fa-paper-plane" aria-hidden="true"></i></a>
         <textarea id="chatSend" name="chat_message" placeholder="Send a message" class="chat_field chat_message"></textarea>
         <input type="hidden" name="chat_room_id" id="chat_room_id" value="">
       </div>
     </div>
       <a id="prime" class="fab"><i id="icon_toggle" class="fa fa-paper-plane" aria-hidden="true"></i></a>
 </div>
 {{-- <script src='http://code.jquery.com/jquery-1.11.3.min.js'></script> --}}
   