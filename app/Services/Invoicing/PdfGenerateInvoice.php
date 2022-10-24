<?php namespace App\Services\Invoicing;

use App\Billing\Invoice;
use invoicr;

class PdfGenerateInvoice
{
    protected $render = "I";
    protected $type = "Tax Invoice";

    public function generate(Invoice $toGenerate)
    {
        $invoice = new invoicr("A4", "R", "en");
        $invoice->setNumberFormat('.', ',');
//        $invoice->setAccountability(\URL::to('/') . "/assets/frontend/images/accountability_small.jpg", 50);
        $invoice->setLogo(\URL::to('/') . "/assets/frontend/images/logo_light.png", 100);
        $invoice->setSnappy(\URL::to('/') . "/assets/frontend/images/small_snapscan.jpg", 50);

        $invoice->setColor("#173175");
        $invoice->setType("Tax Invoice");
        $invoice->setReference($toGenerate->reference);
        $invoice->setDate($toGenerate->created_at->toFormattedDateString());
        $invoice->setDue($toGenerate->created_at->toFormattedDateString());
        $invoice->setFrom(array(config('app.name'), "Vat Number: 4850255789", "Tel: 010 593 0466", "The Broadacres Business Centre,", "Cnr 3rd Ave & Cedar Road, Broadacres,", "2191"));

        $address = $toGenerate->user->getAddress("billing");

        $to = ($toGenerate->user->profile->company) ?: $toGenerate->user->first_name . ' ' . $toGenerate->user->last_name;
        $tax = ($toGenerate->user->profile->tax) ? 'Vat Number:' . $toGenerate->user->profile->tax : $toGenerate->user->email;

        if ($address) {
            $invoice->setTo(array(
                $to,
                $tax,
                $address->line_one,
                $address->line_two,
                $address->city, $address->area_code
            ));

        } else {
            $invoice->setTo(array(
                $to,
                $tax,
                "",
                "",
                "",
                ""
            ));
        }

        foreach ($toGenerate->items as $item) {
            $invoice->addItem(
                $item->name,
                $item->description,
                $item->quantity,
                $toGenerate->vat_rate . '%',
                $item->price,
                $item->discount,
                $item->price * $item->quantity
            );
        }

        if ($toGenerate->status == 'paid') {
            $invoice->addBadge('Paid');
        } else {
            $invoice->addBadge(ucwords($toGenerate->status), '#980000');
        }

        $invoice->addTotal("Sub total", $sub = $toGenerate->sub_total);
        if ($toGenerate->transactions->where('tags', 'Discount')->sum('amount') > 0) {
            $invoice->addTotal("Discount", $discount = $toGenerate->transactions->where('tags', 'Discount')->sum('amount'));
            $invoice->addTotal("Total", $toGenerate->total - $toGenerate->transactions->where('tags', 'Discount')->sum('amount'), true);
        } else {
            $invoice->addTotal("Total", $toGenerate->total, true);
        }
        if ($toGenerate->vat_rate > 0)
            $invoice->addTotal("VAT incl @ $toGenerate->vat_rate%", $toGenerate->total - (($toGenerate->total / ($toGenerate->vat_rate + 100)) * 100));

        $invoice->addTitle("Banking Details");
        $invoice->addParagraph("Bank: ABSA Bank <br>Account Holder: SA Accounting Academy<br>Account Number: 4077695135<br>Branch Code: 632005<br>Reference: $toGenerate->reference");

        $invoice->addTitle("SnapScan");
        $invoice->addParagraph("Did you know that you can settle this invoice using snapscan ? Please use the invoice reference as the reference and the invoice balance as the amount. If the incorrect reference is used, this will result in a delay in allocating your payment");

        if ($this->render == "F") {
            $path = public_path() . '/invoices/Invoice' . $toGenerate->reference . time() . '.pdf';
            $invoice->render($path);
            return $path;
        }
        return $invoice->render('Invoice' . $toGenerate->reference . '.pdf', $this->render);
    }
}