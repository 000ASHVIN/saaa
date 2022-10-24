<?php

namespace App\Console\Commands;

use App\DebugLog;
use App\Subscriptions\Subscription;
use Illuminate\Console\Command;

class FixSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fixes invoice_id, installments_item_id and installments for subscriptions';

    protected $subscriptionModel;
    protected $debug = true;
    protected $debugLogger;

    /**
     * SetMissingInvoiceIdsForSubscriptions constructor.
     * @param Subscription $subscriptionModel
     * @param DebugLog $debugLogger
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
        $subscriptionsToFix = $this->subscriptionModel->whereBetween('plan_id', [2, 5])->where('invoice_id', null)->with(['user', 'user.invoices'])->get();
        $subscriptionsCount = count($subscriptionsToFix);
        $startMessage = 'Starting to fix ' . $subscriptionsCount . ' subscriptions.';
        $this->debugLog($startMessage);
        $this->debugLogger->log($startMessage, $subscriptionsToFix->pluck('id')->toArray());
        $index = 1;
        $fixedCount = 0;
        $data = [
            'fixed' => []
        ];
        try {
            foreach ($subscriptionsToFix as $subscriptionToFix) {
                if ($subscriptionToFix->user->hasRole('admin'))
                    continue;
                if ($subscriptionToFix->user->invoices) {
                    $invoicesCount = $subscriptionToFix->user->invoices()->where('status', '<>', 'cancelled')->count();
                    if ($invoicesCount > 0) {
                        $invoice = $subscriptionToFix->user->invoices()->with(['items'])->where('status', '<>', 'cancelled')->oldest()->first();

                        //Properties update
                        $config = $subscriptionToFix->plan->getInstallmentConfig();
                        $subscriptionToFix->setInstallmentValuesFromConfig($config);
                        $subscriptionToFix->installments_next_due_date = $subscriptionToFix->nextInstallmentsDate($invoice->created_at);
                        $subscriptionToFix->has_installments = true;
                        $subscriptionToFix->invoice_id = $invoice->id;
                        if (count($invoice->items) > 1)
                            throw new \Exception('Installment invoice [' . $invoice->reference . '] has more than 1 line items');
                        $item = $invoice->items->first();
                        if (!$item)
                            throw new \Exception('Null first item for installation invoice: ' . $invoice->reference);
                        $item->discount = $invoice->discount;
                        $item->save();
                        $subscriptionToFix->installments_item_id = $item->id;

                        //Add installment
                        $subscriptionToFix->installmentInvoices()->save($invoice, ['due_date' => $invoice->created_at]);

                        $subscriptionToFix->save();

                        $this->debugLog('Processed subscription [' . $subscriptionToFix->id . ':' . $subscriptionToFix->user_id . ']' . $index . ' of ' . $subscriptionsCount . ' (' . round(($index / $subscriptionsCount) * 100) . '%)');
                        $fixedCount++;
                        $data['fixed'][] = $subscriptionToFix->id;
                    } else {
                        $this->debugLog('No invoice [' . $subscriptionToFix->id . ':' . $subscriptionToFix->user_id . ']' . $index . ' of ' . $subscriptionsCount . ' (' . round(($index / $subscriptionsCount) * 100) . '%)');
                    }
                } else
                    $this->debugLog('Skipped subscription [' . $subscriptionToFix->id . ':' . $subscriptionToFix->user_id . ']' . $index . ' of ' . $subscriptionsCount . ' (' . round(($index / $subscriptionsCount) * 100) . '%)');
                $index++;
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getLine();
            dd([$message, $file, $line]);
        }
        $endMessage = 'Successfully fixed ' . $fixedCount . ' subscriptions.';
        $this->debugLog($endMessage);
        $this->debugLogger->log($endMessage, $data);
    }

    private function debugLog($text = '')
    {
        if ($this->debug) {
            $this->info($text);
        }
    }
}
