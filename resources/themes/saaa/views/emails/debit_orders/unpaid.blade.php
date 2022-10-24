<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:v="urn:schemas-microsoft-com:vml">
<head>

    <!-- Define Charset -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

    <!-- Responsive Meta Tag -->
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;"/>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>

    <title>SA Accounting Academy</title>
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
                            Dear {{ ucfirst(strtolower($debit->user->first_name)) }}
                        </p>
                        <p style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">
                            We regret to inform you that your debit order was unsuccessful.
                        </p>
                        <p style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">
                            To avoid any service interuption or account suspension, we suggest that you update your debit order details immediately.
                        </p>
                        <p style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">
                            Once you have updated your debit order details on your profile, we will re-submit your debit order.
                        </p>
                        <p style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">
                            Please follow these steps to update your debit order details.
                        </p>
                        <p style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">
                        <ul style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">
                            <li style="font-size: 14px; font-family: Arial, Helvetica, sans-serif"><a href="https://accountingacademy.co.za/auth/login">Login to your account</a></li>
                            <li style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">Click on "My Billing".</li>
                            <li style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">Ensure that your payment method is set to debit order.</li>
                            <li style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">Update your details</li>
                            <li style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">Click on Save Banking Details</li>
                            <li style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">Confirm the <strong>OTP (One Time Pin)</strong> that has been sent to your cellphone.</li>
                            <li style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">One the <strong>OTP (One Time Pin)</strong> has been confirmed, your details will now be updated.</li>
                        </ul>
                        </p>
                        <p style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">
                            If your cellphone number is invalid and cannot receive the <strong>OTP (One Time Pin)</strong>, please follow the steps below to update
                            your cellphone number.
                        </p>
                        <p style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">
                        <ul style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">
                            <li style="font-size: 14px; font-family: Arial, Helvetica, sans-serif"><a href="https://accountingacademy.co.za/auth/login">Login to your account</a></li>
                            <li style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">Click on "Edit Profile".</li>
                            <li style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">Update your Mobile Number and Daytime Contact Number</li>
                            <li style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">Click on Save Changes</li>
                        </ul>
                        </p>
                        <p style="font-size: 14px; font-family: Arial, Helvetica, sans-serif">
                            If you need any assistance to update your debit order details, please feel free to contact us on 010 593 0466 or send an email to <a href="mailto:info@accountingacademy.co.za">info@accountingacademy.co.za</a>
                        </p>
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