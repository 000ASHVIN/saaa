<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:v="urn:schemas-microsoft-com:vml">
<head>

    <!-- Define Charset -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

    <!-- Responsive Meta Tag -->
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;"/>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>

    <title>{{ config('app.name') }}</title>

    <style type="text/css">

        body {
            width: 100%;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            mso-margin-top-alt: 0px;
            mso-margin-bottom-alt: 0px;
            mso-padding-alt: 0px 0px 0px 0px;
        }

        p, h1, h2, h3, h4 {
            margin-top: 0;
            margin-bottom: 0;
            padding-top: 0;
            padding-bottom: 0;
        }

        span.preheader {
            display: none;
            font-size: 1px;
        }

        html {
            width: 100%;
        }

        table {
            font-size: 14px;
            border: 0;
            transition: all .5s;
        }

        table td {
            transition: all .5s;
        }

        .action-btn {
            width: 30px;
            position: absolute;
            left: 10px;
            top: 35%;
            z-index: 2000;
        }

        a {
            transition: all .5s;
        }

        #promail {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        #promail li {
            position: relative;
            cursor: n-resize;
        }

        .test {
            width: 100%;
            position: relative;
        }

        .test .icon {
            position: absolute;
            top: 2px;
            right: 2px;
        }

        #promail .test .icon img {
            width: 35px !important;
            height: 27px !important;
        }

        /* ----------- responsivity ----------- */
        @media only screen and (max-width: 798px) {
            body[yahoo] .hide-800 {
                display: none !important;
            }

            body[yahoo] .container800 {
                width: 100% !important;
            }

            body[yahoo] .container800_img {
                width: 50% !important;
            }

            body[yahoo] .section800_img img {
                width: 100% !important;
                height: auto !important;
            }

            body[yahoo] .half-container800 {
                width: 49% !important;
            }
        }

        @media only screen and (max-width: 640px) {

            /*------ top header ------ */
            body[yahoo] .main-header {
                font-size: 25px !important;
            }

            body[yahoo] .main-section-header {
                font-size: 30px !important;
            }

            body[yahoo] .show {
                display: block !important;
            }

            body[yahoo] .hide {
                display: none !important;
            }

            body[yahoo] .align-center {
                text-align: center !important;
            }

            /*-------- container --------*/
            body[yahoo] .container590 {
                width: 440px !important;
            }

            body[yahoo] .container580 {
                width: 400px !important;
            }

            body[yahoo] .container800 {
                width: 440px !important;
            }

            body[yahoo] .container800_img {
                width: 100% !important;
            }

            body[yahoo] .section800_img img {
                width: 100% !important;
            }

            /*-------- secions ----------*/
            body[yahoo] .section-item {
                width: 440px !important;
            }

            body[yahoo] .section-img img {
                width: 440px !important;
                height: auto !important;
            }

            body[yahoo] .video-img img {
                width: 210px !important;
                height: auto !important;
            }

            body[yahoo] .gallery-img img {
                width: 380px !important;
                height: auto !important;
            }

            body[yahoo] .container580-img img {
                width: 400px !important;
                height: auto !important;
            }

        }

        @media only screen and (max-width: 479px) {
            /*------ top header ------ */
            body[yahoo] .main-header {
                font-size: 24px !important;
                line-height: 34px !important;
            }

            body[yahoo] .main-header div {
                line-height: 34px !important;
            }

            body[yahoo] .main-section-header {
                font-size: 23px !important;
            }

            body[yahoo] .align-center {
                text-align: center !important;
            }

            /*-------- container --------*/
            body[yahoo] .container590 {
                width: 280px !important;
            }

            body[yahoo] .container580 {
                width: 260px !important;
            }

            body[yahoo] .container800 {
                width: 100% !important;
            }

            body[yahoo] .container800_img {
                width: 100% !important;
            }

            body[yahoo] .section800_img img {
                width: 100% !important;
                height: auto !important;
            }

            body[yahoo] .half-container800 {
                width: 100% !important;
            }

            /*-------- secions ----------*/
            body[yahoo] .section-item {
                width: 280px !important;
            }

            body[yahoo] .section-img img {
                width: 280px !important;
                height: auto !important;
            }

            body[yahoo] .video-img img {
                width: 130px !important;
                height: auto !important;
            }

            body[yahoo] .gallery-img img {
                width: 280px !important;
                height: auto !important;
            }

            body[yahoo] .container580-img img {
                width: 240px !important;
                height: auto !important;
            }

        }
    </style>
</head>

<body yahoo="fix" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table class="container590" border="0" height="100" width="600" align="center" cellpadding="0" cellspacing="0"
       bgcolor="ffffff" style="border-radius: 5px; border: 1px solid #f2f2f2;">
    <tbody>
    <tr>
        <td colspan="2" height="5" style="font-size: 5px; line-height: 5px;">&nbsp;</td>
    </tr>
    <tr>
        <td width="18">&nbsp;</td>
        <td border="0" align="center">
            <table class="container590" border="0" height="35" width="514" align="center" cellpadding="0"
                   cellspacing="0">
                <tbody>
                <tr>
                    <td align="center"><a href="#"><img src="{{ route('home') }}/assets/themes/taxfaculty/img/logo.png"
                                                        alt=""/></a></td>
                </tr>
                </tbody>
            </table>
        </td>
        <td width="18">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" height="5" style="font-size: 5px; line-height: 5px;">&nbsp;</td>
    </tr>
    </tbody>
</table>
<table class="container590" border="0" height="25" width="600" align="center" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <td></td>
    </tr>
    </tbody>
</table>
<table class="container590" border="0" width="600" align="center" cellpadding="0" cellspacing="0" bgcolor="fcfcfc"
       style="border-radius: 5px; border: 1px solid #f2f2f2;">
    <tbody>
    <tr>
        <td colspan="2" height="5" style="font-size: 5px; line-height: 5px;">&nbsp;</td>
    </tr>
    <tr>
        <td border="0" align="center">
            <table class="container590" border="0" width="514" align="center" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td height="40"></td>
                </tr>
                <tr align="left"
                    style="color: #666666; font-size: 14px; font-family: 'Open Sans', Calibri, sans-serif; font-weight: 400; line-height: 20px;">
                    <td>
                        <p>
                            Greetings, <br><br>
                            We have received an application from {{ $user->first_name }} {{ $user->last_name }}
                            with ID Number {{ $user->id_number }}
                            <br>
                            <br>
                            <strong>Membership Number: {{ $user->membership_number }}</strong>
                            <br>
                            <br>
                            In order for this member to qualify for the discount on the selected seminar/webinar, we will need to verify that they are in good standing with you.
                            <br>
                            <br>
                            If yes, please proceed by clicking the "Confirm Membership" below. <br>
                            If this memebership has been cancelled or is not in good standing with {{ $user->body->title }}, please "Decline" and they will have to pay full price.
                        </p>
                    </td>
                </tr>
                <tr align="center">
                    <td height="40"></td>
                </tr>
                <tr align="center">

                    <td>
                        <table border="0">
                            <tr>
                                <!--<td width="20"></td>-->
                                <td>
                                    <table border="0" align="center" width="200" cellpadding="0" cellspacing="0"
                                           class="main_color" bgcolor="93c054"
                                           style="border-radius: 5px;">

                                        <tr>
                                            <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td align="center"
                                                style="color: #fff; font-size: 13px; font-family: 'Open Sans', Calibri, sans-serif; font-weight: 400; line-height: 22px;">
                                                <!-- ======= main section button ======= -->

                                                <div style="line-height: 22px;">
                                                    <a href="{{ route('membership_confirm', $user->id) }}"
                                                       style="color: #fff; text-decoration: none;">Confirm Membership</a>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                                        </tr>

                                    </table>
                                </td>
                                <td>
                                    <table border="0" align="center" width="200" cellpadding="0" cellspacing="0"
                                           class="main_color" bgcolor="f95252"
                                           style="border-radius: 5px;">

                                        <tr>
                                            <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td align="center"
                                                style="color: #fff; font-size: 13px; font-family: 'Open Sans', Calibri, sans-serif; font-weight: 400; line-height: 22px;">
                                                <!-- ======= main section button ======= -->

                                                <div style="line-height: 22px;">
                                                    <a href="{{ route('membership_decline', $user->id) }}"
                                                       style="color: #fff; text-decoration: none;">Decline Membership</a>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr align="center">
                    <td height="40"></td>
                </tr>
                <tr align="center"
                    style="color: #666666; font-size: 14px; font-family: 'Open Sans', Calibri, sans-serif; font-weight: 400; line-height: 20px;">
                    <td>
                        <p>
                            Kind regards, <br>
                            The {{ config('app.name') }} CPD Team
                            <br>
                            <br>
                        </p>
                    </td>
                </tr>

                </tbody>
            </table>
        </td>
        <td width="18">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" height="5" style="font-size: 5px; line-height: 5px;">&nbsp;</td>
    </tr>
    </tbody>
</table>
<table class="container590" border="0" height="75" width="600" align="center" cellpadding="0" cellspacing="0"
       bgcolor="ffffff">
    <tbody>
    <tr>
        <td align="center"
            style="color: #c6c6c6; display: block; text-decoration: none; outline: none; border: none; font-size: 12px; font-family: 'Open Sans', Calibri, sans-serif; font-weight: 400;">
            <br/>
            <p>&copy; 2017 {{ config('app.name') }}. All Rights Reserved.</p>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>