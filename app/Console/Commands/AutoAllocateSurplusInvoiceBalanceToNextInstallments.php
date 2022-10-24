<?php

namespace App\Console\Commands;

use App\DebugLog;
use App\Subscriptions\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoAllocateSurplusInvoiceBalanceToNextInstallments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'installments:auto-allocate-surplus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Allocate the surplus payments for subscription invoices to other installments on the same subscription';
    protected $subscriptionsModel;
    protected $debug = true;
    protected $debugLogger;

    /**
     * AutoAllocateSurplusInvoiceBalanceToNextInstallments constructor.
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
        $subscriptionsWithInstallments = $this->subscriptionsModel->where('has_installments', true)->with(['installments', 'installments.invoice', 'installments.invoice.payments'])->get();
        $subscriptionsCount = count($subscriptionsWithInstallments);
        $startMessage = 'Starting to allocate surplus installments payments for ' . $subscriptionsCount . ' subscription(s).';
        $this->debugLog($startMessage);
        $this->debugLogger->log($startMessage, $subscriptionsWithInstallments->pluck('id')->toArray());
        $totalPaymentsTransferred = 0;
        $outerIndex = 1;
        $data = [
            'auto-allocated' => []
        ];
        foreach ($subscriptionsWithInstallments as $subscriptionWithInstallments) {
            $installments = $subscriptionWithInstallments->installments;
            $installmentsCount = count($installments);
            if ($installmentsCount <= 1) {
                $this->debugLog('Skipped subscription ' . $outerIndex . ' of ' . $subscriptionsCount . ' (' . round(($outerIndex / $subscriptionsCount) * 100) . '%).');
                continue;
            }
            $installmentPaymentsTransferred = 0;
            for ($i = 0; $i < $installmentsCount - 1; $i++) {
                $installment = $installments[$i];
                $invoice = $installment->invoice;
                if ($invoice->balance >= 0) {
                    $this->debugLog('Skipped installment with invoice ' . $invoice->reference . ' due to larger than 0 balance');
                    continue;
                }

                $nextInstallment = $installments[$i + 1];
                $payments = $invoice->payments()->get();
                $paymentsCount = count($payments);
                if ($paymentsCount <= 1) {
                    $this->debugLog('Skipped installment with invoice ' . $invoice->reference . ' due to 1 or less payments.');
                    continue;
                }

                $index = 0;
                $paymentsTotal = 0;
                $transferring = false;
                $paymentsTransferred = 0;
                foreach ($payments as $payment) {
                    $paymentsTotal += $payment->amount;
                    if ($transferring) {
                        $payment->invoice()->associate($nextInstallment->invoice);
                        $payment->save();
                        $invoice->balance += $payment->amount;
                        $invoice->save();
                        //$invoice->autoUpdateAndSave();
                        $nextInstallment->invoice->balance -= $payment->amount;
                        if ($nextInstallment->invoice->balance <= 0) {
                            $nextInstallment->invoice->paid = true;
                            $nextInstallment->invoice->status = 'paid';
                            $nextInstallment->invoice->date_settled = Carbon::now();
                            $nextInstallment->invoice->releasePendingOrders();
                            $nextInstallment->invoice->save();
                            $nextInstallment->invoice->updateSubscriptionOverdueStatus();
                        }
                        $nextInstallment->invoice->save();
                        //$nextInstallment->invoice->autoUpdateAndSave();
                        $paymentsTransferred++;
                        $this->debugLog('Transferred payment from invoice ' . $invoice->reference . ' to ' . $nextInstallment->invoice->reference . '.');
                        $data['auto-allocated'][] = [
                            'from_id' => $invoice->id,
                            'to_id' => $nextInstallment->invoice->id,
                            'installment_id' => $nextInstallment->id
                        ];
                    } else if ($paymentsTotal >= $invoice->total)
                        $transferring = true;
                    $index++;
                }
                $installmentPaymentsTransferred += $paymentsTransferred;
            }
            $totalPaymentsTransferred += $installmentPaymentsTransferred;
            $this->debugLog('Processed subscription ' . $outerIndex . ' of ' . $subscriptionsCount . ' with ' . $installmentPaymentsTransferred . ' payments transferred (' . round(($outerIndex / $subscriptionsCount) * 100) . '%).');
            $outerIndex++;
        }
        $doneMessage = 'Successfully transferred ' . $totalPaymentsTransferred . ' surplus payment(s).';
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
