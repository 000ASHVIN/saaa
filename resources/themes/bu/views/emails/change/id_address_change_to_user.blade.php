<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">Dear {{ $user->first_name.' '.$user->last_name }},</p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">We trust that you are well.</p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">Kindly take note that your ID number has been changed from {{ $oldIdNumber }} to {{ $user->id_number }}.</p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">Please feel free to contact me should you have any further queries.</p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">Kind Regards.</p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">
    SA Accounting Academy <br>
    <strong>Email:</strong> <a href="mailto:info@accountingacademy.co.za">info@accountingacademy.co.za</a><br>
    <strong>Tell:</strong> 010 593 0466
</p>