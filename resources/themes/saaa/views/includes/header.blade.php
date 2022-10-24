<!DOCTYPE html>
<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->	<html> <!--<![endif]-->
<head class="hidden-print">
    <link href="/assets/frontend/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <meta charset="utf-8" />

    @yield('meta_data',\View::make('includes.seo'))
    @yield('meta_tags')
    {{--  @yield('meta_tags',\View::make('includes.seo'))  --}}

    <!-- mobile settings -->
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->

    <!-- WEB FONTS : use %7C instead of | (pipe) -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400%7CRaleway:300,400,500,600,700%7CLato:300,400,400italic,600,700" rel="stylesheet" type="text/css" />

    <!-- CORE CSS -->
    <link href="/assets/frontend/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <!-- REVOLUTION SLIDER -->
    <link href="/assets/frontend/plugins/slider.revolution/css/extralayers.css" rel="stylesheet" type="text/css" />
    <link href="/assets/frontend/plugins/slider.revolution/css/settings.css" rel="stylesheet" type="text/css" />

    <!-- THEME CSS -->
    <link href="/assets/frontend/css/essentials.css" rel="stylesheet" type="text/css" />
    <link href="/assets/frontend/css/layout.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/assets/frontend/css/lada-themeless.min.css">

    <!-- PAGE LEVEL SCRIPTS -->
    <link href="/assets/frontend/css/header-1.css" rel="stylesheet" type="text/css" />

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="{{ Theme::asset('css/app.css', null, true) }}"/>

    <!-- Mobile number -->
    <link href="/assets/frontend/css/intlTelInput.css" rel="stylesheet" type="text/css" />

    @yield('styles')
    <style>
        .telno{
            margin-bottom: 5px !important;
        }
        .telno span.iti__country-name {
            color: black !important;
        }
        .planname {
            white-space: normal;
        }
    </style>
    @include('includes.globals')

    <script type="text/javascript">
        (function(){window.sib={equeue:[],client_key:"1v2tzwn1pt3m0k1tltywt"};var e={get:function(e,n){return window.sib[n]||function(){ var t = {}; t[n] = arguments; window.sib.equeue.push(t);}}};window.sendinblue=new Proxy(window.sib,e);var n=document.createElement("script");n.type="text/javascript",n.id="sendinblue-js",n.async=!0,n.src="https://sibautomation.com/sa.js?key="+window.sib.client_key;var i=document.getElementsByTagName("script")[0];i.parentNode.insertBefore(n,i),window.sendinblue.page()})();
    </script>
    {!! settings('google-tag-manager') !!}
    {!! settings('facebook-pixel') !!}

    <meta name="google-site-verification" content="{{settings('google-site-verification')}}" />
</head>

<body class="smoothscroll enable-animation">
    {!! settings('google-tag-manager-no-script') !!}
        {!! settings('google-analytics') !!}
{{--<a href="#"><img style=" z-index: 9999; position: absolute; top: 0; right: 0; width: 10%; border: 0;" src="/assets/frontend/images/beta.png" alt="Beta" data-canonical-src="#"></a>--}}