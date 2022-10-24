<fieldset>
    <legend>Account Settings</legend>
    <div class="checkbox clip-check check-info checkbox-inline">
        <input type="checkbox" id="send_invoices_via_email" value="1" name="settings[send_invoices_via_email]" {{ (key_exists('send_invoices_via_email', ($member->settings) ? $member->settings : []))? "checked" : "" }}>
        <label for="send_invoices_via_email"> Invoice via E-mail </label>
    </div>

    <div class="checkbox clip-check check-info checkbox-inline">
        <input type="checkbox" id="event_notifications" value="1" name="settings[event_notifications]" {{ (key_exists('event_notifications', ($member->settings) ? $member->settings : []))? "checked" : "" }}>
        <label for="event_notifications"> Event Notifications </label>
    </div>

    <div class="checkbox clip-check check-info checkbox-inline">
        <input type="checkbox" id="sms_notifications" value="1" name="settings[sms_notifications]" {{ (key_exists('sms_notifications', ($member->settings) ? $member->settings : []))? "checked" : "" }}>
        <label for="sms_notifications"> SMS Notifications </label>
    </div>

    <div class="checkbox clip-check check-info checkbox-inline">
        <input type="checkbox" id="marketing_emails" value="1" name="settings[marketing_emails]" {{ (key_exists('marketing_emails', ($member->settings) ? $member->settings : []))? "checked" : "" }}>
        <label for="marketing_emails"> Marketing Emails </label>
    </div>
</fieldset>