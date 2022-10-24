<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SECTION 18A TAX RECEIPT</title>
    <link
        href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700"
        rel="stylesheet"
        type="text/css"
    />
    <style>
        body {
            font-family: 'Open Sans'
        }
    </style>
</head>
<body>
<table class="table" style="width: 100%; color:#003865;">
    <tr>
        <td width="40"></td>
        <td>
            <table class="table" style="width: 100%; font-size:14px; color:#003865;">
                <tr>    
                    <td>
                        <img src="{{ \URL::to('/') . "/assets/themes/taxfaculty/img/donation_header.jpg" }}" width="100%" alt="">
                    </td>
                </tr>
                <tr>
                    <td height="20"></td>
                </tr>
                <tr>
                    <td style="font-size: 12px;"><i>The Tax Faculty (NPC) PBO Reference number: 930067823</i></td>
                </tr>
                <tr>
                    <td height="30"></td>
                </tr>
                <tr>
                    <td align="center">
                        <strong style="font-size:14px;">SECTION 18A TAX RECEIPT</strong>
                    </td>
                </tr>
                <tr>
                    <td height="30"></td>
                </tr>
                <tr>
                    <td>
                        <p>This receipt is issued under Section 18A of the Income Tax Act No 58 of 1962 (as amended). The donation received will be used exclusively for the Nation Building objects of The Tax Faculty in carrying out its public benefit activities approved by SARS under Section 18A of the Income Tax Act No 58 of 1962 (as amended).</p>
                    </td>
                </tr>
                <tr>
                    <td height="20"></td>
                </tr>
                <tr>
                    <td>
                        <strong>Donation Receipt:</strong> #{{ str_pad($donation->id,3,0,STR_PAD_LEFT) }}<br/>
                        <strong>Date of Donation:</strong> {{ $donation->created_at->format('d F Y') }}<br/>
                        <strong>Tax Year Ending:</strong> {{ $donation->created_at->format('m') > 2? 
                        \Carbon\Carbon::parse(($donation->created_at->format('Y')+1).'-02-01')->endOfMonth()->format('d F Y') : \Carbon\Carbon::parse($donation->created_at->format('Y').'-02-01')->endOfMonth()->format('d F Y') }}<br/>
                        <strong>Nature of the Donation:</strong> Cash<br/>
                    </td>
                </tr>
                <tr>
                    <td height="15"></td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Donor Details:</strong></p>
                        <strong>First Name:</strong> {{ $donation->first_name }}<br/>
                        <strong>Last Name:</strong> {{ $donation->last_name }}<br/>
                        <strong>Company Name:</strong> {{ $donation->company_name?$donation->company_name:'-' }}<br/>
                        <strong>Email:</strong> {{ $donation->email }}<br/>
                        <strong>Cell:</strong> {{ $donation->cell }}<br/>
                        <strong>Address:</strong> {{ $donation->address }}<br/>
                        <strong>Amount:</strong> {{ round($donation->amount,2) }}<br/>
                    </td>
                </tr>
                <tr>
                    <td height="15"></td>
                </tr>
                <tr>
                    <td>
                        <p><strong>Confirmation of B-BBEE:</strong></p>
                        <p>The Tax Faculty (NPC) has a 93,52% Black Designated Group Beneficiaries as issued under section 1 of The Broad- Based Black Economic Empowerment Act 53 of 2003 (as amended).</p>
                    </td>
                </tr>
                <tr>
                    <td height="40"></td>
                </tr>
            </table>                
        </td>
        <td width="40"></td>
    </tr>
</table>
<table style="position: absolute; bottom:0px; left:0px;">
    <tr>
        <td width="40"></td>
        <td>
            <img src="{{ \URL::to('/') . "/assets/themes/taxfaculty/img/donation_footer.jpg" }}" width="100%" alt="">
        </td>
        <td width="40"></td>
    </tr>
</table>
</body>
</html>