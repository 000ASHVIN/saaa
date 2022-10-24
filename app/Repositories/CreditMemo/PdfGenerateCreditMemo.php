<?php namespace App\Repositories\CreditMemo;
use App\CreditMemo;
use invoicr;

/**
 * Created by PhpStorm.
 * User: Tiaan Theunissen
 * Date: 3/27/2017
 * Time: 11:32 AM
 */

class PdfGenerateCreditMemo
{
    protected $render = "I";
    protected $type = "CN";

    public function generate(CreditMemo $toGenerate)
    {

        $credit = new invoicr("A4", "R", "en");
        $credit->setNumberFormat('.', ',');
        $credit->setLogo(\URL::to('/') . "/assets/frontend/images/logo_light.png", 100);

        $credit->setColor("#173175");
        $credit->setType("Credit Note #".$toGenerate->id);
        $credit->setReference('CN#'.$toGenerate->id);
        $credit->setDate($toGenerate->transaction_date);
        $credit->setDue($toGenerate->transaction_date);
        $credit->setFrom(array(config('app.name'), "Vat Number: 4850255789", "Tel: 010 593 0466", "The Broadacres Business Centre,", "Cnr 3rd Ave & Cedar Road, Broadacres,", "2191"));

        $address = $toGenerate->invoice->user->getAddress("billing");

        $to = ($toGenerate->invoice->user->profile->company) ? : $toGenerate->invoice->user->first_name . ' ' . $toGenerate->invoice->user->last_name;
        $tax = ($toGenerate->invoice->user->profile->tax) ? 'Vat Number:' . $toGenerate->invoice->user->profile->tax : $toGenerate->invoice->user->email;

        if ($address) {
            $credit->setTo(array(
                $to,
                $tax,
                $address->line_one,
                $address->line_two,
                $address->city, $address->area_code
            ));

        } else {
            $credit->setTo(array(
                $to,
                $tax,
                "",
                "",
                "",
                ""
            ));
        }

        $credit->addItem(
            "CN".$toGenerate->id .' '.' - '.'Invoice #'. $toGenerate->invoice->reference,
            null,
            1,
            null,
            null,
            null,
            ($toGenerate->amount / 100)
        );

        $credit->setFooternote("Vat Number: 4850255789");

        if ($this->render == "F") {
            $path = public_path() . '/invoices/creditNotes' . $toGenerate->reference . time() . '.pdf';
            $credit->render($path);
            return $path;
        }

        return $credit->render('CreditNote' . $toGenerate->id . '.pdf', $this->render);
    }
}