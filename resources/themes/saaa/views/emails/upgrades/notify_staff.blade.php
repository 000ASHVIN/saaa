<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:v="urn:schemas-microsoft-com:vml">
<head>

    <!-- Define Charset -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

    <!-- Responsive Meta Tag -->
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;"/>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>

    <title>New Upgrade took place</title>
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
                    <td align="center"><a href="#"><img src="{{ route('home') }}/assets/frontend/images/logo_light.png"
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
                        <p style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">
                            <Strong>The Note:</Strong>
                        </p>
                        <p style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">
                            {!! ucfirst(str_replace('_', ' ', $note['type'])) !!}<br>
                            {!! $note['description'] !!}<br>
                            Agent: {!! $note['logged_by'] !!}<br>
                        </p>
                        <p style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">
                            <strong>Member details:</strong>
                        </p>
                        <p style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">
                            Name: {!! $user['first_name'].' '.$user['last_name'] !!} <br>
                            Cellphone: {!! $user['cell'] !!} <br>
                            Email Address: {!! $user['email'] !!} <br>
                            ID Number: {!! $user['id_number'] !!} <br>
                            Payment Method: {!! ucfirst(str_replace('_', ' ', $user['payment_method'])) !!} <br>
                        </p>
                        <p style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">
                            <strong>Subscription details:</strong>
                        </p>
                        <p style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">
                            New Package: {!! $newPlan->name !!} {!! $newPlan->interval !!}ly <br>
                            New Price: R {!! number_format($newPlan->price, 2) !!}<br>
                        </p>

                        @if($oldPlan->name)
                            <p style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">
                                Old Package: {!! $oldPlan->name !!} {!! $oldPlan->interval !!}ly <br>
                                Old Price: R {!! number_format($oldPlan->price, 2) !!}<br>
                            </p>
                        @endif
                        @include('emails.includes.disclaimer')
                    </td>
                </tr>
                <tr align="center">
                    <td height="40"></td>
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
            <p>&copy; 2018 SA Accounting Academy. All Rights Reserved.</p>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>