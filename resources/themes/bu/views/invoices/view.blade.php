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
        <td width="65"></td>
        <td style="padding-top: 20px;"><img src="{{ \URL::to('/') . "/assets/frontend/images/bu_logo.png" }}" width="250px" style="margin-left: -50px"  alt=""></td>
        <td width="120"></td>
        <td style="font-family: Helvetica, Arial; font-size: 25px; text-align: right;"><strong>TAX INVOICE</strong></td>
    </tr>
    <tr>
        <td width="85"></td>
        <td></td>
        <td></td>
        <td>
            <table class="table" width="100%" style="text-align: right">
                <tbody>
                <tr>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #800000">REFERENCE:</td>
                    <td colspan="1"></td>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; color: #656464">{!! $invoice->reference !!}</td>
                </tr>
                <tr>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #800000">BILLING DATE:</td>
                    <td colspan="1"></td>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; color: #656464"> {!! $invoice->created_at->toFormattedDateString() !!}</td>
                </tr>
                <tr>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #800000">DUE DATE:</td>
                    <td colspan="1"></td>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; color: #656464"> {!! $invoice->created_at->toFormattedDateString() !!}</td>
                </tr>
                <tr>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #800000">STATUS:</td>
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
        <td style="width: 400px; font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #800000; padding-bottom: 10px; border-bottom: 2px solid; border-color: #800000">OUR INFORMATION</td>
        <td style="width: 20px"></td>
        <td style="width: 400px ;font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #800000; padding-bottom: 10px; border-bottom: 2px solid; border-color: #800000">BILLING TO</td>
    </tr>
    <tr>
        <td style="width: 95px"></td>
        <td style="width: 400px; font-family: Helvetica, Arial; font-size: 14px;"><br> SA Accounting Academy (Pty) Ltd</td>
        <td style="width: 20px"></td>
        <td style="width: 400px ;font-family: Helvetica, Arial; font-size: 14px;"><br> {{ ($invoice->user->profile->company) ?: $invoice->user->first_name . ' ' . $invoice->user->last_name }}</td>
    </tr>
    <tr>
        <td style="width: 95px"></td>
        <td style="width: 400px; font-family: Helvetica, Arial; font-size: 14px;">
            <table>
                <br>
                <span style="line-height: 1.7em; font-family: Helvetica, Arial; font-size: 14px; color: #656464;">
                Vat Number: 4850255789 <br>
                Tel: 010 593 0466 <br>
                Ground Floor Block 3,<br>
                Fourways Office Park, <br>
                Corner Fourways Boulevard & Roos Street, <br>
                Fourways, <br>
                2191
            </span>
            </table>
        </td>
        <td style="width: 20px"></td>
        <td style="width: 400px; font-family: Helvetica, Arial; font-size: 14px; color: #656464; vertical-align: text-top;">
            <table>
                <br>
                @if($invoice->user->getAddress("billing"))
                    <span style="line-height: 1.7em; font-family: Helvetica, Arial;">
                    {{ $invoice->user->getAddress("billing")->line_one }} <br>
                        {{ $invoice->user->getAddress("billing")->line_two }} <br>
                        {{ $invoice->user->getAddress("billing")->city }} {{ $invoice->user->getAddress("billing")->area_code }}<br>
                    </span>
                @endif

                <span style="line-height: 1.7em; font-family: Helvetica, Arial;">
                    {{ ($invoice->user->profile->tax) ? 'Vat Number:' . $invoice->user->profile->tax : $invoice->user->email }}
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
        <td style="font-family: Helvetica, Arial ;font-size: 14px; font-weight: 600; color: #800000; font-size: 14px">PRODUCT</td>
        <td style="font-family: Helvetica, Arial ;text-align: center; font-size: 14px; font-weight: 600; color: #800000; font-size: 14px">QTY</td>
        <td style="font-family: Helvetica, Arial ;text-align: center; font-size: 14px; font-weight: 600; color: #800000; font-size: 14px">UNIT PRICE</td>
        <td style="font-family: Helvetica, Arial ;text-align: center; font-size: 14px; font-weight: 600; color: #800000; font-size: 14px">TOTAL</td>
    </tr>

    <tr>
        <td style="width: 95px"></td>
        <td colspan="5" style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #800000; padding-bottom: 10px; border-bottom: 2px solid; border-color: #800000"></td>
    </tr>

    <tr style="height: 5px">
        <td></td>
    </tr>

    @foreach($invoice->items as $item)
        <tr>
            <td style="width: 80px"></td>
            <td width="51%" style="padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">{{ str_limit($item->name, 40) }} <br><span style="font-size: 12px; line-height: 1.5em; font-family: Helvetica, Arial">{{ str_limit($item->description, 40) }}</span> </td>
            <td width="5%" style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">{{ $item->quantity }}</td>
            <td width="20%" style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">R {{ number_format($item->price, 2) }}</td>
            <td width="15%" style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">R {{ number_format($item->price * $item->quantity, 2) }}</td>
        </tr>
    @endforeach

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
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">R {{ number_format($invoice->total - (($invoice->total / ($invoice->vat_rate + 100)) * 100), 2) }}</td>
    </tr>

    <tr>
        <td style="width: 95px"></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px"></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px"></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px; color: white" bgcolor="#800000"><strong>Total Due</strong></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px; color: white" bgcolor="#800000">R {{ number_format($invoice->balance, 2) }}</td>
    </tr>



    
    </tbody>
</table>

<table width="90%">
    <tr style="height: 10px">
        <td></td>
    </tr>
    <tr>
        <td style="width: 0px"></td>
        <td style="width: 40%; font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #800000; padding-bottom: 10px;">BANKING DETAILS</td>
		       <td style="width: 0px"></td>
        <td style="width: 40%; font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #800000; padding-bottom: 10px;">SNAPSCAN</td>
    </tr>

    <tr>
        <td style="width: 0px"></td>
        <td colspan="5" style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #800000; padding-bottom: 10px; border-bottom: 2px solid; border-color: #800000"></td>
    </tr>

    <tr>
        <td style="width: 0px"></td>
       
		 <td style="width: 40%; font-family: Helvetica, Arial; font-size: 14px;">
            <table>
                <br>
                <span style="line-height: 1.7em; font-family: Helvetica, Arial; font-size: 14px; color: #656464;">
                Bank: ABSA Bank  <br>
                Account Holder: SA Accounting Academy (Pty) Ltd <br>
                Account Number: 4077695135 <br>
                Branch Code: 632005 <br>
                Reference: {{ $invoice->reference }} <br>
            </span>
            </table>
        </td>
		<td style="width: 0px"></td>
		 <td style="width: 50%;  font-family: Helvetica, Arial; font-size: 14px;">
            <table>
                <br>
                <span style="line-height: 1.5em; font-family: Helvetica, Arial; font-size: 12px; color: #656464;">
                <img src="https://imageshack.com/a/img924/934/ePqYDz.png" alt="" style="float: left; max-width: 50%;">
               Did you know that you can settle this invoice using snapscan ? Please use the invoice reference as the reference and the invoice balance as the amount. If the incorrect reference is used, this will result in a delay in allocating your payment
            </span>
            </table>
        </td>
    </tr>
	
</table>
</body>
</html>