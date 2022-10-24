<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
</head>
<body>
<table class="table" style="width: 90%">
    <tr style="height: 20px">
        <td></td>
    </tr>
    <tr>
        <td width="85"></td>
        <td style="padding-top: 20px;"><img src="{{ \URL::to('/') . "/assets/themes/taxfaculty/img/logo.png" }}" width="250px" style="margin-left: -50px" alt=""></td>
        <td width="120"></td>
        <td style="font-family: Helvetica, Arial; font-size: 30px; text-align: right;"><strong>TAX INVOICE</strong></td>
    </tr>
    <tr>
        <td width="85"></td>
        <td></td>
        <td></td>
        <td>
            <table class="table" width="100%" style="text-align: right">
                <tbody>
                <tr>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #8cc03c">REFERENCE:</td>
                    <td colspan="1"></td>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; color: #656464">{!! $invoice->reference !!}</td>
                </tr>
                <tr>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #8cc03c">BILLING DATE:</td>
                    <td colspan="1"></td>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; color: #656464"> {!! $invoice->created_at->toFormattedDateString() !!}</td>
                </tr>
                <tr>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #8cc03c">DUE DATE:</td>
                    <td colspan="1"></td>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; color: #656464"> {!! $invoice->created_at->toFormattedDateString() !!}</td>
                </tr>
                <tr>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #8cc03c">STATUS:</td>
                    <td colspan="1"></td>
                    @if($invoice->status != 'paid')
                        <td style="font-family: Helvetica, Arial; font-size: 14px; color: red"> {!! strtoupper($invoice->status) !!}</td>
                    @else
                        <td style="font-family: Helvetica, Arial; font-size: 14px; color: green"> {!! strtoupper($invoice->status) !!}</td>
                    @endif
                </tr>
                </tbody>
            </table>
        </td>
    </tr>

    <tr style="height: 20px">
        <td></td>
    </tr>
</table>
<table style="width: 90%">
    <tbody>
    <tr>
        <td style="width: 95px"></td>
        <td style="width: 400px; font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #8cc03c; padding-bottom: 10px; border-bottom: 2px solid; border-color: #8cc03c">OUR INFORMATION</td>
        <td style="width: 20px"></td>
        <td style="width: 400px ;font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #8cc03c; padding-bottom: 10px; border-bottom: 2px solid; border-color: #8cc03c">BILLING TO</td>
    </tr>
    <tr>
        <td style="width: 95px"></td>
        <td style="width: 400px; font-family: Helvetica, Arial; font-size: 14px;"><br> THE TAX FACULTY NPC</td>
        <td style="width: 20px"></td>
        <td style="width: 400px ;font-family: Helvetica, Arial; font-size: 14px;"><br> {{ ($invoice->user->profile->company) ?: $invoice->user->first_name . ' ' . $invoice->user->last_name }}</td>
    </tr>
    <tr>
        <td style="width: 95px"></td>
        <td style="width: 400px; font-family: Helvetica, Arial; font-size: 14px;">
            <table>
                <br>
                <span style="line-height: 1.7em; font-family: Helvetica, Arial; font-size: 14px; color: #656464;">
                Vat Number: 4360 2734 21 <br>
                Tel: 012 943 7002<br>
                Address: Riverwalk Office Park, <br> 41 Matroosberg road, Block A,
                Ground floor, Ashley Gardens, Pretoria <br>
            </span>
            </table>
        </td>
        <td style="width: 20px"></td>
        <td style="width: 400px; font-family: Helvetica, Arial; font-size: 14px; color: #656464; vertical-align: text-top;">
            <table>
                <br>
                @if($invoice->user->hasCompany())
                {{ ($invoice->user->hasCompany())?$invoice->user->hasCompany()->title:"" }}<br>
               @endif
                @if($invoice->user->getAddress("billing"))
                    <span style="line-height: 1.7em; font-family: Helvetica, Arial;">
                    {{ $invoice->user->getAddress("billing")->line_one }} <br>
                        {{ $invoice->user->getAddress("billing")->line_two }} <br>
                        {{ $invoice->user->getAddress("billing")->city }} {{ $invoice->user->getAddress("billing")->area_code }}<br>
                    </span>
                @endif

                @if ($invoice->user->profile->tax)
                <span style="line-height: 1.7em; font-family: Helvetica, Arial;">
                    {{ 'Vat Number: ' .$invoice->user->profile->tax }}<br>
                </span>
                @endif
                <span style="line-height: 1.7em; font-family: Helvetica, Arial;">
                    {{ $invoice->user->billing_email_address }}
                </span>
            </table>
        </td>
    </tr>
    <tr style="height: 20px">
        <td></td>
    </tr>
    </tbody>
</table>
<table style="width: 90%">
    <tbody>
    <tr>
        <td style="width: 95px"></td>
        <td style="font-family: Helvetica, Arial ;font-size: 14px; font-weight: 600; color: #8cc03c; font-size: 14px">PRODUCT</td>
        <td style="font-family: Helvetica, Arial ;text-align: center; font-size: 14px; font-weight: 600; color: #8cc03c; font-size: 14px">QTY</td>
        <td style="font-family: Helvetica, Arial ;text-align: center; font-size: 14px; font-weight: 600; color: #8cc03c; font-size: 14px">UNIT PRICE</td>
        <td style="font-family: Helvetica, Arial ;text-align: center; font-size: 14px; font-weight: 600; color: #8cc03c; font-size: 14px">TOTAL</td>
    </tr>

    <tr>
        <td style="width: 95px"></td>
        <td colspan="5" style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #8cc03c; padding-bottom: 10px; border-bottom: 2px solid; border-color: #8cc03c"></td>
    </tr>

    <tr style="height: 5px">
        <td></td>
    </tr>

    @foreach($invoice->items as $item)
        <tr>
            <td style="width: 80px"></td>
            <td width="51%" style="padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">{{ $item->name }} <br><span style="font-size: 12px; line-height: 1.5em; font-family: Helvetica, Arial">{{ $item->description }}</span> </td>
            <td width="5%" style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">{{ $item->quantity }}</td>
            <td width="20%" style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">R {{ number_format($item->price, 2) }}</td>
            <td width="15%" style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">R {{ number_format($item->price * $item->quantity, 2) }}</td>
        </tr>
    @endforeach

    @if ($invoice->donation != 0)

        <tr>
            <td style="width: 80px"></td>
            <td width="51%" style="padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea"><span style="font-size: 12px; line-height: 1.5em; font-family: Helvetica, Arial">Donation to assist an underprivileged learner.</span> </td>
            <td width="5%" style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">1</td>
            <td width="20%" style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">R {{ number_format($invoice->donation, 2) }}</td>
            <td width="15%" style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">R {{ number_format($invoice->donation, 2) }}</td>
        </tr>

    @endif

    <!-- Footer -->
    <tr>
        <td style="width: 95px"></td>
        <td style="padding: 10px; font-family: Helvetica, Arial; font-size: 14px"></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px"></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">Sub total</td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">R {{ number_format($invoice->sub_total, 2) }}</td>
    </tr>

    <tr>
        <td style="width: 95px"></td>
        <td style="padding: 10px; font-family: Helvetica, Arial; font-size: 14px"></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px"></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">Discount</td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">R {{ number_format($invoice->transactions->where('tags', 'Discount')->sum('amount'), 2) }}</td>
    </tr>

    <tr>
        <td style="width: 95px"></td>
        <td style="padding: 10px; font-family: Helvetica, Arial; font-size: 14px"></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px"></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">VAT incl @ {{ $invoice->vat_rate }}%</td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">R  {{ number_format(($invoice->total - ($invoice->transactions->where('tags', 'Discount')->sum('amount') + $invoice->donation)) - ((($invoice->total - ($invoice->transactions->where('tags', 'Discount')->sum('amount') + $invoice->donation)) / ($invoice->vat_rate + 100)) * 100), 2) }}</td>
    </tr>

    <tr>
        <td style="width: 95px"></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px"></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px"></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px; color: white" bgcolor="#8cc03c"><strong>Total Due</strong></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px; color: white" bgcolor="#8cc03c">R {{ number_format($invoice->balance, 2) }}</td>
    </tr>



    <tr>
        <td style="width: 95px"></td>
        <td style="width: 400px; font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #8cc03c; padding-bottom: 10px;">BANKING DETAILS</td>
        <td style="width: 20px"></td>
        <td style="width: 400px; font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #8cc03c; padding-bottom: 10px;"></td>
    </tr>

    <tr>
        <td style="width: 95px"></td>
        <td colspan="4" style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #8cc03c; padding-bottom: 10px; border-bottom: 2px solid; border-color: #8cc03c"></td>
    </tr>

    <tr>
        <td style="width: 95px"></td>
        <td style="width: 400px; font-family: Helvetica, Arial; font-size: 14px;">
            <table>
                <br>
                <span style="line-height: 1.7em; font-family: Helvetica, Arial; font-size: 14px; color: #656464;">
                Bank: Investec Bank  <br>
                Account Holder: THE TAX FACULTY NPC <br>
                Account Number: 10012191310 <br>
                Branch Code: 580105 <br>
                Reference: {{ $invoice->reference }} <br>
				
            </span>
            </table>
        </td>
        <td style="width: 20px"></td>
        <td style="width: 400px; font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; padding-bottom: 10px;"></td>
    </tr>
	
	<tr>
		<td style="width: 50px"></td>
        <td colspan="5" style="">
		<span style="line-height: 1.7em; font-family: Helvetica, Arial; font-size: 14px; color: #656464;font-style:italic;">It is the responsibility of the client to verify the banking details of The Tax Faculty.
Refer to the EFT payments section in our  <a href="https://taxfaculty.ac.za/terms_and_conditions">Terms & Conditions</a>.
		</span>
		</td>
     <td style="width: 20px"></td>
	</tr>
    </tbody>
</table>

{{--<table width="90%">--}}
{{--<tr style="height: 10px">--}}
{{--<td></td>--}}
{{--</tr>--}}
{{--<tr>--}}
{{--<td style="width: 0px"></td>--}}
{{--<td style="width: 400px; font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #8cc03c; padding-bottom: 10px;">SNAPSCAN</td>--}}
{{--</tr>--}}

{{--<tr>--}}
{{--<td style="width: 0px"></td>--}}
{{--<td colspan="5" style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #8cc03c; padding-bottom: 10px; border-bottom: 2px solid; border-color: #8cc03c"></td>--}}
{{--</tr>--}}

{{--<tr>--}}
{{--<td style="width: 0px"></td>--}}
{{--<td style="width: 90%;  font-family: Helvetica, Arial; font-size: 14px;">--}}
{{--<table>--}}
{{--<br>--}}
{{--<span style="line-height: 1.7em; font-family: Helvetica, Arial; font-size: 14px; color: #656464;">--}}
{{--<img src="https://imageshack.com/a/img924/934/ePqYDz.png" alt="" style="float: left; max-width: 15%;">--}}
{{--Did you know that you can settle this invoice using snapscan ? Please use the invoice reference as the reference and the invoice balance as the amount. If the incorrect reference is used, this will result in a delay in allocating your payment--}}
{{--</span>--}}
{{--</table>--}}
{{--</td>--}}
{{--</tr>--}}
{{--</table>--}}
</body>
</html>