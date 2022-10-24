<?php

namespace App\Console\Commands;

use App\Billing\Invoice;
use App\DebugLog;
use App\Subscriptions\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessInstallments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'installments:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new installments and update next installment date for subscriptions up to currnet date';

    protected $subscriptionsModel;

    protected $debug = true;
    protected $debugLogger;

    /**
     * Create a new command instance.
     * @param Subscription $subscriptionsModel
     * @param DebugLog $debugLogger
     */
    public function __construct(Subscription $subscriptionsModel, DebugLog $debugLogger)
    {
        $this->subscriptionsModel = $subscriptionsModel;
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
        $laggingSubscriptions = $this->subscriptionsModel
                                     ->where('installments_next_due_date', '<=', Carbon::now())
                                     ->with(['installments', 'plan'])
                                     ->get()->filter(function($subscription) {
                                        if($subscription->user) {
                                            return $subscription;
                                        }
                                     });

        $subscriptionsCount = count($laggingSubscriptions);
        $startMessage = 'Starting to process installments for ' . $subscriptionsCount . ' subscriptions.';
        $this->debugLog($startMessage);
        $this->debugLogger->log($startMessage, $laggingSubscriptions->pluck('id')->toArray());
        $index = 1;
        $count = 0;
        $invoiceCreatedCount = 0;
        $data = [
            'created-invoices' => [],
        ];
        foreach ($laggingSubscriptions as $laggingSubscription) {
            do {
                if(! is_null($laggingSubscription->invoice))
                {
                    $invoice = new Invoice();
                    $invoice->created_at = $laggingSubscription->installments_next_due_date;

                    $invoice->type = 'subscription';
                    $invoice->sub_total = $laggingSubscription->invoice->sub_total;
                    $invoice->total = $laggingSubscription->invoice->sub_total;
                    $invoice->balance = $laggingSubscription->invoice->sub_total;

                    $invoice->setUser($laggingSubscription->user);
                    $invoice->save();
                    $invoice->items()->attach($laggingSubscription->installments_item_id);
                    $invoice->autoUpdateAndSave();

                    $laggingSubscription->installmentInvoices()->save($invoice, ['due_date' => $laggingSubscription->installments_next_due_date]);
                }

                $newNextDueDate = $laggingSubscription->nextInstallmentsDate();
                $laggingSubscription->installments_next_due_date = $newNextDueDate;
                $laggingSubscription->save();

                $invoiceCreatedCount++;
                $this->debugLog('Created installment and invoice for ' . $newNextDueDate);

                if(! is_null($laggingSubscription->invoice))
                {
                    $data['created-invoices'][] = [
                        'subscription_id' => $laggingSubscription->id,
                        'new_invoice_id' => $invoice->id,
                        'new_next_due_date' => $newNextDueDate
                    ];
                }

                // Break if no Next Installement can be calculated
                if(is_null($newNextDueDate))
                    break;

            } while (Carbon::now()->gte($newNextDueDate));
            $count++;
            $this->debugLog('Processed subscription ' . $index . ' of ' . $subscriptionsCount . ' (' . round(($index / $subscriptionsCount) * 100) . '%)');
            $index++;
        }
        $doneMessage = 'Successfully updated the next installment date of ' . $count . ' subscriptions and created ' . $invoiceCreatedCount . ' new invoices';
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
