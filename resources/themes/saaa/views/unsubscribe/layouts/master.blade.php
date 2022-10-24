<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('description')</title>
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/admin/vendor/fontawesome/css/font-awesome.min.css">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            background: #f8f8f8;
            font-family: "Open Sans", Arial, Helvetica, sans-serif;
        }
        .wrapper {
            width: calc(100vw - 50px);
            /* height: calc(100vh - 50px); */
            display: flex;
            justify-content: center;
            /* align-items: center; */
            margin: auto;
        }
        .box {
            max-width: 500px;
            box-shadow: 0 0 1px 0 rgb(0 0 0 / 10%);
            padding: 30px;
            background: #fff;
            border-radius: 5px;
            margin: 10px;
        }
        .logo {
            text-align: center;
        }
        .logo img {
            width: 100%;
        }
        .description {
            color: #666;
            /* text-align: center; */
        }
        .description p {
           font-size: 14px;
        }
        .radio, .checkbox {
            display: inline-block;
            margin: 0 15px 3px 0;
            padding-left: 27px;
            font-size: 15px;
            line-height: 27px;
            color: #666;
            cursor: pointer;
            position: relative;
            font-weight: 500;
        }
        .radio input, .checkbox input {
            position: absolute;
            left: -9999px;
        }
        .radio i, .checkbox i {
            position: absolute;
            top: 5px;
            left: 0;
            display: block;
            width: 19px;
            height: 19px;
            outline: none;
            border-width: 2px;
            border-style: solid;
            border-color: rgba(0,0,0,0.3);
            background: rgba(255,255,255,0.3);
        }
        .checkbox input + i:after {
            content: '\f00c';
            top: 0;
            left: 0px;
            width: 15px;
            height: 15px;
            font: normal 12px/16px FontAwesome;
            text-align: center;
            color: rgba(0,0,0,8);
            position: absolute;
            opacity: 0;
        }
        .checkbox input:checked + i:after {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="box">
            <div class="logo">
                <img src="{{ asset('assets/frontend/images/logo_light.png') }}" alt="">
            </div>
            <div class="description">
               @yield('content')
            </div>
        </div>
    </div>
</body>
<script src="/assets/admin/vendor/jquery/jquery.min.js"></script>
<script src="/assets/admin/vendor/jquery-cookie/jquery.cookie.js"></script>
@stack('scripts')
</html>