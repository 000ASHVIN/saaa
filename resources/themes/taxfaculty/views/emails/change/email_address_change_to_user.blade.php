<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">Dear {{ $user->first_name.' '.$user->last_name }},</p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">We trust that you are well.</p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">Kindly take note that your email address has been changed from {{ $oldEmail }} to {{ $user->email }}.</p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">Please ensure that you use your new credentials the next time you log in to your profile.</p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">Please feel free to contact me should you have any further queries.</p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">Kind Regards.</p>
<p style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: black">
    {{ config('app.name') }} <br>
    <strong>Email:</strong> {{ config('app.email') }}<br>
    <strong>Tell:</strong> 012 943 7002
</p>