<?php
/**
 * Created by PhpStorm.
 * User: Tiaan Theunissen
 * Date: 3/27/2017
 * Time: 11:23 AM
 */

namespace App\Repositories\CreditMemo;


use App\Billing\Transaction;
use App\CreditMemo;
use App\Note;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CreditMemoRepository
{
    private $creditMemo;
    public function __construct(CreditMemo $creditMemo)
    {
        $this->creditMemo = $creditMemo;
    }

    public function show($memo)
    {
        $memo = $this->creditMemo->with('invoice', 'invoice.user')->find($memo);
        return $memo;
    }

    public function store($transaction)
    {
        $user = $transaction->user->id;
        $invoice = $transaction->invoice;
        $reference = 'Invoice #'.$transaction->invoice->reference;

        if ($transaction->amount * 100 != 0){
            $memo = New CreditMemo([
                'user_id' => $user,
                'reference' => $reference,
                'tags' => $transaction->tags,
                'description' => $transaction->description,
                'category' => $transaction->category,
                'amount' => ($transaction->amount * 100),
                'transaction_date' => $transaction->created_at
            ]);

            $invoice->creditmemos()->save($memo);
            return $memo;
        }
    }

    public function creditNoteAllocate(Request $request, $invoice)
    {
        $note = new Note([
            'type' => 'credit_note',
            'description' => 'I have created a credit note for  R' . number_format($request['amount'], 2) . ' to Invoice #' . $invoice['reference'],
            'logged_by' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
        ]);
        $invoice->user->notes()->save($note);
    }

    public function cancelInvoiceAndCreditNote($invoice, $description)
    {
        $transaction = $invoice->transactions()->create([
            'user_id' => $invoice->user->id,
            'invoice_id' => $invoice->id,
            'type' => 'credit',
            'display_type' => 'Credit Note',
            'status' => 'Closed',
            'category' => $invoice->type,
            'amount' => $invoice->balance,
            'ref' => $invoice->reference,
            'method' => 'Void',
            'description' => "Invoice #{$invoice->reference} cancellation",
            'tags' => "Cancellation",
            'date' => Carbon::now()
        ]);

        $invoice->cancelled = 1;
        $invoice->status = 'credit noted';
        $invoice->save();
        $this->store($transaction);

        $note = new Note([
            'type' => 'credit_note',
            'description' => '# '.$invoice->reference.' '.$description,
            'logged_by' => 'system',
        ]);

        $note->invoice()->associate($invoice);
        $invoice->user->notes()->save($note);
    }
}