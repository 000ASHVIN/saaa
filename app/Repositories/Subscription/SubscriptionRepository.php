<?php
/**
 * Created by PhpStorm.
 * User: Tiaan Theunissen
 * Date: 2/3/2017
 * Time: 1:04 PM
 */

namespace App\Repositories\Subscription;

use App\Billing\Invoice;
use App\Note;
use App\Repositories\CreditMemo\CreditMemoRepository;
use App\Repositories\Sendinblue\SendingblueRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Subscriptions\Models\Subscription;

class SubscriptionRepository
{
    private $creditMemoRepository;
    private $sendingblueRepository;

    public function __construct(CreditMemoRepository $creditMemoRepository, SendingblueRepository $sendingblueRepository)
    {
        $this->creditMemoRepository = $creditMemoRepository;
        $this->sendingblueRepository = $sendingblueRepository;
    }

    public function cancelSubscriptionWithInvoices($request, $message, $user)
    {
        if ($request->has('cancel_invoices')) {
            $this->getSubscriptionInvoicesAndCancel($user);
        }

        if ($request->has('custom_date')) {
            $date = Carbon::parse($request['date_range']);
            $user->subscription('cpd')->cancelAt($date);

            $user->subscription('cpd')->ends_at = $date;
            $user->subscription('cpd')->save();
        } else {
            if ($user->subscription('cpd')->plan->interval != 'year' && Carbon::now()->startOfDay()->diffInDays($user->subscription('cpd')->starts_at) > 2) {
                $date = $user->subscription('cpd')->ends_at->addDay(1);
                $user->subscription('cpd')->cancelAt($date);
            } else {
                $date = $user->subscription('cpd')->ends_at->subDay(1);
                $user->subscription('cpd')->cancelAt($date);
            }
        }

        $subscription = $user->subscription('cpd');
        if($subscription) {
            $events = $subscription->plan->getPlanEventsAll($user);
            if(count($events)) {
                foreach($events as $event) {
                    $ticket = $event->tickets()->where('user_id', $user->id)->first();
                    if($ticket) {
                        $ticket->delete();
                    }
                }
            }
            
            $starts_at = Carbon::now()->startOfDay();
            $ends_at = $starts_at->addYear(1); 
            $new_subscription = Subscription::create([
                'user_id' => $subscription->user_id,
                'plan_id' => 45,
                'name' => $subscription->name,
                'starts_at' => $starts_at,
                'ends_at' => $ends_at,
                'agent_id' => $subscription->agent_id
            ]);
            $subscription->delete();
        }

        $user->notes()->create([
            'type' => 'subscription_cancellation',
            'description' => $message . '<br />' . '<strong>System - </strong> Subscription will be cancelled at ' . date_format($date, 'd F Y'),
            'logged_by' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
        ]);

        $this->sendingblueRepository->removeCPDUserFromList($user);

        // Send email to accounts.
        if(sendMailOrNot($user, 'accounts.cancelled_subscription')) {
        Mail::send('emails.accounts.cancelled_subscription', ['user' => $user], function ($m) {
            $m->from(config('app.email'), config('app.name'));
            $m->to(config('app.email'))->subject('Cancelled CPD subscription');
        });
        }
    }

    public function getSubscriptionInvoicesAndCancel($user)
    {
        $invoices = Invoice::with('transactions', 'items')->where('user_id', $user->id)
            ->where('type', 'subscription')
            ->where('date_settled', null)
            ->where('paid', false)
            ->where('status', 'unpaid')
            ->get();

        if (count($invoices)) {
            $invoices->each(function ($invoice) use ($user) {
                if ($invoice->total == $user->subscriptions->first()->plan->price || $invoice->sub_total == $user->subscriptions->first()->plan->price) {
                    // Create Credit note
                    $creditMemo = $invoice->transactions()->create([
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
                        'tags' => 'Cancellation',
                        'date' => Carbon::now()
                    ]);

                    // Set Cancelled on Invoice
                    $invoice->cancelled = 1;
                    $invoice->status = 'credit noted';
                    $invoice->save();

                    // save the credit memo
                    $this->creditMemoRepository->store($creditMemo);
                }
            });
        }
    }
}
