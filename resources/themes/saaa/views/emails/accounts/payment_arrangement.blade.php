<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">Dear {{ ucfirst(strtolower($invoice->user->first_name)).' '.ucfirst(strtolower($invoice->user->last_name)) }},</p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">As per our telephonic discussion today, your payment arrangement is for {{ $invoice->ptp_date }} for the amount of R{{ number_format($invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('type', 'credit')->sum('amount'), 2, ".", "") }}.  </p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">After payment made, please forward proof of payment to <a href="mailto:accounts@accountingacademy.co.za">accounts@accountingacademy.co.za</a>. </p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">Should you have any queries, please feel free to contact us.</p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">Kind Regards.</p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">
    SA Accounting Academy <br>
    <strong>Email:</strong> <a href="mailto:info@accountingacademy.co.za">info@accountingacademy.co.za</a><br>
    <strong>Tell:</strong> 010 593 0466
</p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: black; font-style: italic">
    Please note:  Recordings of seminar and webinar bookings will be loaded onto your profile after the invoice has been settled for the specific event booked.
</p>
@include('emails.includes.disclaimer')