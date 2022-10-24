<?php

namespace App\Console\Commands;

use App\DebugLog;
use App\Subscriptions\Subscription;
use Illuminate\Console\Command;

class UpdateOverdueSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:update-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the is_overdue property of all subscriptions with installments';
    protected $subscriptionModel;
    protected $debug = true;
    protected $debugLogger;

    /**
     * MarkOverdueSubscriptions constructor.
     * @param $subscriptionModel
     * @param $debugLogger
     */
    public function __construct(Subscription $subscriptionModel, DebugLog $debugLogger)
    {
        $this->subscriptionModel = $subscriptionModel;
        $this->debugLogger = $debugLogger;

        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $subscriptionsWithInstallments = $this->subscriptionModel->where('has_installments', true)->get();
        $subscriptionsCount = count($subscriptionsWithInstallments);
        $startMessage = 'Starting to update overdue status of ' . $subscriptionsCount . ' subscription(s).';
        $this->debugLog($startMessage);
        $this->debugLogger->log($startMessage, $subscriptionsWithInstallments->pluck('id')->toArray());
        $index = 1;
        $count = 0;
        $data = [
            'updated' => []
        ];
        foreach ($subscriptionsWithInstallments as $subscriptionWithInstallments) {
            $result = $subscriptionWithInstallments->autoUpdateIsOverdue();
            if ($result)
                $count++;
            $this->debugLog('Processed subscription ' . $index . ' of ' . $subscriptionsCount . ' (' . round(($index / $subscriptionsCount) * 100) . '%)');
            $data['updated'][] = $subscriptionWithInstallments;
            $index++;
        }
        $doneMessage = 'Successfully updated the overdue status of ' . $count . ' subscription(s).';
        $this->debugLog($doneMessage);
        $this->debugLogger->log($doneMessage, $data);
    }

    private function debugLog($text = '')
    {
        if ($this->debug) {
            $this->info($text);
        }
    }
}
