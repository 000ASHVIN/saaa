<?php

namespace App;


class Settings
{
    protected $user;
    protected $allowed = [
        'marketing_emails',
        'sms_notifications',
        'event_notifications',
        'send_invoices_via_email',
    ];

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function merge(array $attributes = null)
    {
        if (! $attributes){
            $this->user->settings = [];
        }else{
            $settings = array_merge(
                $this->user->settings,
                array_only($attributes, $this->allowed)
            );
        }
        return $this->user->update(compact('settings'));
    }
}