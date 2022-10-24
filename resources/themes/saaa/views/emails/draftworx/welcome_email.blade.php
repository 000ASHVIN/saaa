<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome Email</title>
</head>
<body>

<p style="font-size: 14px; font-family: Arial">Dear {{ $data['first_name']}}</p>
<p style="font-size: 14px; font-family: Arial">Thank you for enquiring about DRAFTWORX, via SA Accounting Academy and Auxilla.</p>
<p style="font-size: 14px; font-family: Arial">You receive this mailer after completing the online form to request a quote.</p>
<p style="font-size: 14px; font-family: Arial">The next steps</p>
<p style="font-size: 14px; font-family: Arial">
<ul>
    <li style="font-size: 14px; font-family: Arial">You will receive a quote with your indicated preferences.</li>
    <li style="font-size: 14px; font-family: Arial">Sign the quote and make the payment.</li>
    <li style="font-size: 14px; font-family: Arial">Email your signed quote and proof of payment to sales@draftworx.com to confirm your order.</li>
    <li style="font-size: 14px; font-family: Arial">You will receive your invoice, licence key and link to download DRAFTWORX.</li>
    <li style="font-size: 14px; font-family: Arial">Start using your new software.</li>
    <li style="font-size: 14px; font-family: Arial">Enjoy the training videos and full support. </li>
</ul>
</p>

<p style="font-size: 14px; font-family: Arial">For any further information , feel free to email me.</p>
<p style="font-size: 14px; font-family: Arial">Kind regards</p>
<p style="font-size: 14px; font-family: Arial"><strong>Ronell van Wyk </strong></p>
<p style="font-size: 14px; font-family: Arial"><strong>Software Reseller</strong></p>
<p style="font-size: 14px; font-family: Arial"><img src="https://imagizer.imageshack.com/v2/280x200q90/923/L0ClHZ.png" alt="Signature"></p>

<p style="font-size: 14px; font-family: Arial">
    <strong>t </strong> 083 286 7350 <br>
    <strong>e</strong> <a href="mailto:admin@auxilla.co.za">admin@auxilla.co.za</a> <br>
    <strong>w</strong> <a href="#">https://accountingacademy.co.za/sponsors</a> <br>
    <strong>a</strong> 100 Van Wouw street GROENKLOOF 0181 <br>
</p>

<p style="font-size: 12px; font-family: Arial">
    The information transmitted is only to be viewed or used by the person/s or entity to which it is addressed and may contain confidential and/or privileged material/information. Auxilla cannot assure that the integrity of this communication has been maintained or that it is free from errors, malicious code, interception or interference. Under no circumstances will Auxilla or the sender of this e-mail be liable to any party for any direct, indirect, special or other consequential damages from any use of this e-mail.
</p>
@include('emails.includes.disclaimer')
</body>
</html>