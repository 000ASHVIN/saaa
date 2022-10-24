<?php

namespace App\Console\Commands;

use App\Billing\Payment;
use App\DebugLog;
use App\Installment;
use Illuminate\Console\Command;

class BreakLargerPaymentsDownIntoSmallerOnes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:chunk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $paymentsModel;
    protected $installmentsModel;
    protected $debug = true;
    protected $debugLogger;

    /**
     * BreakLargerPaymentsDownIntoSmallerOnes constructor.
     * @param Payment $paymentsModel
     * @param Installment $installmentsModel
     * @param DebugLog $debugLogger
     */
    public function __construct(Payment $paymentsModel, Installment $installmentsModel, DebugLog $debugLogger)
    {
        $this->paymentsModel = $paymentsModel;
        $this->installmentsModel = $installmentsModel;
        $this->debugLogger = $debugLogger;
        parent::__construct();
    }

    public function handle()
    {
        $installmentsWithInvoicesAndPayments = $this->installmentsModel->with(['invoice', 'invoice.payments'])->get();
        $installmentsCount = count($installmentsWithInvoicesAndPayments);
        $startMessage = 'Starting to chunk ' . $installmentsCount . ' installments with invoice payments.';
        $this->debugLog($startMessage);
        $this->debugLogger->log($startMessage, $installmentsWithInvoicesAndPayments->pluck('id')->toArray());

        $installmentsIndex = 1;
        $totalPaymentsCreated = 0;
        $data = [
            'chunked' => [],
            'created' => []
        ];
        foreach ($installmentsWithInvoicesAndPayments as $installment) {
            $invoice = $installment->invoice;

            if (!$invoice) {
                $installmentsIndex++;
                continue;
            }

            $payments = $invoice->payments;
            if (!$payments) {
                $installmentsIndex++;
                continue;
            }

            $paymentsIndex = 1;
            $paymentsCreated = 0;
            foreach ($payments as $payment) {

                if ($payment->amount <= $invoice->total) {
                    $paymentsIndex++;
                    continue;
                }

                $surplusForPayment = $payment->amount - $invoice->total;
                $newPaymentsToCreate = floor($surplusForPayment / $invoice->total);

                if ($newPaymentsToCreate <= 0) {
                    $paymentsIndex++;
                    continue;
                }

                $data['chunked'][] = $payment->id;
                for ($i = 0; $i < $newPaymentsToCreate; $i++) {
                    $newPayment = $this->paymentsModel->create([
                        'invoice_id' => $invoice->id,
                        'amount' => $invoice->total,
                        'date_of_payment' => $payment->date_of_payment,
                        'description' => $payment->description
                    ]);
                    $payment->amount -= $invoice->total;
                    $payment->save();
                    $paymentsCreated++;
                    $this->debugLog('Created a new payment ' . ($i + 1) . ' / ' . $newPaymentsToCreate . ' for invoice ' . $invoice->reference);
                    $data['created'][] = [
                        'id' => $newPayment->id,
                        'created_from' => $payment->id
                    ];
                }

                $paymentsIndex++;
            }

            $installmentsIndex++;
            $totalPaymentsCreated += $paymentsCreated;
        }

        $doneMessage = 'Successfully chunked ' . $installmentsCount . ' installments and created ' . $totalPaymentsCreated . ' new payments.';
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
