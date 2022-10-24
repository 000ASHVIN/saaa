<?php

namespace App\Http\Controllers;

use App\BulkMailStat;
use App\DebugLog;
use App\Webhook;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BulkMailController extends Controller
{
    public function webhook(Request $request)
    {
        $webhooks = $request->all();
        foreach ($webhooks as $webhook) {
            Webhook::create(['json' => json_encode($webhook), 'source' => 'sendgrid']);
        }
    }

    public function processSendGridWebhooks()
    {
        $webhooks = Webhook::source('sendgrid')->get();
        foreach ($webhooks as $webhook) {
            $webhookData = json_decode($webhook->json);
            if (isset($webhookData->newsletter)) {
                Webhook::destroy($webhook->id);
                continue;
            }
            if (property_exists($webhookData, 'marketing_campaign_name') && property_exists($webhookData, 'marketing_campaign_id')) {
                $existing = BulkMailStat::findByEmailTypeAndCampaignId($webhookData->email, $webhookData->event, $webhookData->marketing_campaign_id);
                if ($existing) {
                    $existing->increment('count');
                } else {
                    $type = $webhookData->event;
                    $data = [
                        'type' => $webhookData->event,
                        'email' => $webhookData->email,
                        'ip' => $webhookData->ip,
                        'campaign_name' => $webhookData->marketing_campaign_name,
                        'campaign_id' => $webhookData->marketing_campaign_id
                    ];

                    if ($type == 'click')
                        $data['url'] = $webhookData->url;

                    BulkMailStat::create($data);
                }
            }
            Webhook::destroy($webhook->id);
        }

        return "Done";
    }
}
