<?php

namespace App\Console\Commands;

use App\BulkMailStat;
use App\DebugLog;
use App\Webhook;
use Illuminate\Console\Command;

class ProcessBulkMails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulkmails:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processes all webhooks and converts them into BulkMailStats';

    protected $webhookModel;
    protected $bulkMailStatModel;
    protected $debugLog;

    /**
     * Create a new command instance.
     * @param Webhook $webhookModel
     * @param BulkMailStat $bulkMailStatModel
     * @param DebugLog $debugLog
     */
    public function __construct(Webhook $webhookModel, BulkMailStat $bulkMailStatModel, DebugLog $debugLog)
    {
        $this->webhookModel = $webhookModel;
        $this->bulkMailStatModel = $bulkMailStatModel;
        $this->debugLog = $debugLog;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $startingMessage = "Starting web hook processing.";

        $this->info($startingMessage);
        try {
            $this->debugLog->log($startingMessage);
            $webhooks = $this->webhookModel->source('sendgrid')->get();
            foreach ($webhooks as $webhook) {
                $webhookData = json_decode($webhook->json);
                if (isset($webhookData->newsletter)) {
                    $this->webhookModel->destroy($webhook->id);
                    continue;
                }
                if (property_exists($webhookData, 'marketing_campaign_name') && property_exists($webhookData, 'marketing_campaign_id')) {
                    $existing = $this->bulkMailStatModel->findByEmailTypeAndCampaignId($webhookData->email, $webhookData->event, $webhookData->marketing_campaign_id);
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

                        $this->bulkMailStatModel->create($data);
                    }
                }
                $this->webhookModel->destroy($webhook->id);
            }
            $successMessage = count($webhooks) . " webhook(s) successfully processed.";
            $this->debugLog->log($successMessage);
            $this->info($successMessage);
        } catch (\Exception $e) {
            $this->error("An error occurred, please see the DebugLog");
        }
    }
}
