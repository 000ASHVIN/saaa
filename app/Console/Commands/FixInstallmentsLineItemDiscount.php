<?php

namespace App\Console\Commands;

use App\DebugLog;
use App\Installment;
use Illuminate\Console\Command;

class FixInstallmentsLineItemDiscount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'installments:fix-line-items-discounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Sets the line item discount of existing installment invoices to the invoice's discount";

    protected $installmentsModel;
    protected $debug = true;
    protected $debugLogger;

    /**
     * FixInstallmentsLineItemDiscount constructor.
     * @param $installmentsModel
     * @param $debugLogger
     */
    public function __construct(Installment $installmentsModel, DebugLog $debugLogger)
    {
        $this->installmentsModel = $installmentsModel;
        $this->debugLogger = $debugLogger;
        parent::__construct();
    }


    /**
     * Execute the console command.
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $installments = $this->installmentsModel->with(['invoice', 'invoice.items'])->get();
        $startMessage = "Starting to process " . count($installments) . " installment(s) with invoices and invoice items";
        $this->debugLog($startMessage);
        $this->debugLogger->log($startMessage, $installments->pluck('id')->toArray());
        $data = [
            'updated' => []
        ];
        $updatedCount = 0;
        foreach ($installments as $installment) {
            if (!$installment->invoice) {
                $this->debugLog('Skipping due to no invoice');
                continue;
            }

            $invoice = $installment->invoice;

            if (!$invoice->items || count($invoice->items) <= 0) {
                $this->debugLog('Skipping due to null or 0 items');
                continue;
            }

            if (count($invoice->items) > 1)
                throw new \Exception('Installment invoice [' . $invoice->reference . '] has more than 1 line items');

            $item = $invoice->items->first();

            if (!$item)
                throw new \Exception('Null first item for installation invoice');

            if ($invoice->discount == $item->discount) {
                $this->debugLog('Skipping due to discount already equal');
                continue;
            }

            if ($item->discount > $invoice->discount)
                throw new \Exception('Invoice item with discount larger than invoice');

            $item->discount = $invoice->discount;
            $item->save();
            $data["updated"][] = $item->toArray();
            $updatedCount++;
            $this->debugLog("Processed invoice: " . $invoice->reference . " with line item: " . $item->id);
        }
        $endMessage = "Successfully updated " . $updatedCount . " invoice items' discounts.";
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
