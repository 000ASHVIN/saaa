<br />
<p style="font-size: 11px;line-height: 11px;margin-bottom: 9px;color:#939598;">
    Disclaimer: The contents of this email and any attachments are confidential. They are intended for the named recipient(s) only. If you have received this email by mistake, please notify the sender immediately and do not disclose the contents to anyone or make copies thereof.
</p>
<p style="font-size: 11px;line-height: 11px;margin-bottom: 9px;color:#939598;">
    Cyber Risk Warning: Please be aware that there is a significant risk posed by cyber fraud, specifically affecting email accounts and bank account details. Please note that our bank account details will not change during the course of a transaction and we will not notify you of any changes to our bank details via email. Should you receive much correspondence confirm bank account details and transfer instructions with us.
</p>

@if (isset($user))
    <?php
        $email = null;
        if(is_object($user)) {
            $email = $user->email;
        } elseif(is_array($user)) {
            $email = $user['email'];
        }
    ?>
    @if ($email)
    <p style="font-size: 11px;line-height: 11px;margin-bottom: 9px;color:#939598;">
        If you don't want to receive this type of email in the future, please <a href="{{ route('unsubscribe.email', [$email]) }}" style="TEXT-DECORATION: none;">unsubscribe</a>.
    </p>
    @endif
@endif