<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">Dear {{ ucfirst(strtolower($invoice->user->first_name)).' '.ucfirst(strtolower($invoice->user->last_name)) }},</p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">As per our telephonic discussion today, your payment arrangement is for {{ $invoice->ptp_date }} for the amount of R{{ number_format($invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('type', 'credit')->sum('amount'), 2, ".", "") }}.  </p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">After payment made, please forward proof of payment to {{ config('app.email') }}. </p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">Should you have any queries, please feel free to contact us.</p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">Kind Regards.</p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">
    {{ config('app.name') }} <br>
    <strong>Email:</strong> {{ config('app.email') }}<br>
    <strong>Tell:</strong> 012 943 7002
</p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: black; font-style: italic">
    Please note:  Recordings of seminar and webinar bookings will be loaded onto your profile after the invoice has been settled for the specific event booked.
</p>