<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase Order</title>
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
        <td style="font-family: Helvetica, Arial; font-size: 20px; text-align: right;"><strong>Purchase Order</strong></td>
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
                    <td style="font-family: Helvetica, Arial; font-size: 14px; color: #656464">{!! $order->reference !!}</td>
                </tr>
                <tr>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #8cc03c">BILLING DATE:</td>
                    <td colspan="1"></td>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; color: #656464"> {!! $order->created_at->toFormattedDateString() !!}</td>
                </tr>
                <tr>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #8cc03c">DUE DATE:</td>
                    <td colspan="1"></td>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; color: #656464"> {!! $order->created_at->toFormattedDateString() !!}</td>
                </tr>
                <tr>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #8cc03c">STATUS:</td>
                    <td colspan="1"></td>
                    @if($order->status != 'unpaid')
                        <td style="font-family: Helvetica, Arial; font-size: 14px; color: green"> {!! strtoupper($order->status) !!}</td>
                    @else
                        <td style="font-family: Helvetica, Arial; font-size: 14px; color: red"> {!! strtoupper($order->status) !!}</td>
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
        <td style="width: 400px ;font-family: Helvetica, Arial; font-size: 14px;"><br> {{ ($order->user->profile->company) ?: $order->user->first_name . ' ' . $order->user->last_name }}</td>
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
                @if($order->user->getAddress("billing"))
                    <span style="line-height: 1.7em; font-family: Helvetica, Arial;">
                    {{ $order->user->getAddress("billing")->line_one }} <br>
                        {{ $order->user->getAddress("billing")->line_two }} <br>
                        {{ $order->user->getAddress("billing")->city }} {{ $order->user->getAddress("billing")->area_code }}<br>
                    </span>
                @endif

                @if ($order->user->profile->tax)
                <span style="line-height: 1.7em; font-family: Helvetica, Arial;">
                    {{ 'Vat Number: ' .$order->user->profile->tax }}<br>
                </span>
                @endif
                <span style="line-height: 1.7em; font-family: Helvetica, Arial;">
                    {{ $order->user->billing_email_address }}
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

    @foreach($order->items as $item)
        <tr>
            <td style="width: 80px"></td>
            <td width="51%" style="padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">{{ $item->name }} <br><span style="font-size: 12px; line-height: 1.5em; font-family: Helvetica, Arial">{{ $item->description }}</span> </td>
            <td width="5%" style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">{{ $item->quantity }}</td>
            <td width="20%" style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">R {{ number_format($item->price, 2) }}</td>
            <td width="15%" style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">R {{ number_format($item->price * $item->quantity, 2) }}</td>
        </tr>
    @endforeach

    @if ($order->donation != 0)

        <tr>
            <td style="width: 80px"></td>
            <td width="51%" style="padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea"><span style="font-size: 12px; line-height: 1.5em; font-family: Helvetica, Arial">Donation to assist an underprivileged learner.</span> </td>
            <td width="5%" style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">1</td>
            <td width="20%" style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">R {{ number_format($order->donation, 2) }}</td>
            <td width="15%" style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">R {{ number_format($order->donation, 2) }}</td>
        </tr>

    @endif

    <!-- Footer -->
    <tr>
        <td style="width: 95px"></td>
        <td style="padding: 10px; font-family: Helvetica, Arial; font-size: 14px"></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px"></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">Sub total</td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">R {{ number_format($order->sub_total, 2) }}</td>
    </tr>

    <tr>
        <td style="width: 95px"></td>
        <td style="padding: 10px; font-family: Helvetica, Arial; font-size: 14px"></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px"></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">Discount</td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">R {{ number_format($order->discount, 2) }}</td>
    </tr>

    <tr>
        <td style="width: 95px"></td>
        <td style="padding: 10px; font-family: Helvetica, Arial; font-size: 14px"></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px"></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">VAT incl @ {{ $order->vat_rate }}%</td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">R {{ number_format(($order->total - $order->donation) - ((($order->total - $order->donation) / ($order->vat_rate + 100)) * 100), 2) }}</td>
    </tr>

    <tr>
        <td style="width: 95px"></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px"></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px"></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px; color: white" bgcolor="#8cc03c"><strong>Total Due</strong></td>
        <td style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px; color: white" bgcolor="#8cc03c">R {{ number_format($order->balance, 2) }}</td>
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
                Reference: {{ $order->reference }} <br>
            </span>
            </table>
        </td>
        <td style="width: 20px"></td>
        <td style="width: 400px; font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; padding-bottom: 10px;"></td>
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
{{--Did you know that you can settle this purchase order using snapscan ? Please use the purchase order reference as the reference and the purchase order balance as the amount. If the incorrect reference is used, this will result in a delay in allocating your payment--}}
{{--</span>--}}
{{--</table>--}}
{{--</td>--}}
{{--</tr>--}}
{{--</table>--}}
</body>
</html>