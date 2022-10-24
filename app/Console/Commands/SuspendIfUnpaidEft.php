<?php

namespace App\Console\Commands;

use App\Subscriptions\Models\Subscription;
use App\SuspendNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Mail;

class SuspendIfUnpaidEft extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'suspend:eftunpaids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will suspend all users that chosen eft with unpaid susbcription invoices';
    /**
     * @var Subscription
     */
    private $subscription;

    /**
     * Create a new command instance.
     * @param Subscription $subscription
     * @internal param User $user
     */
    public function __construct(Subscription $subscription)
    {
        parent::__construct();
        $this->subscription = $subscription;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->subscription = $this->subscription->with('user', 'user.invoices', 'user.subscriptions')->get();
        $subscriptions = $this->subscription->filter(function ($subscription){
            if ($subscription->user->payment_method == 'eft' && $subscription->plan->interval == 'year'){
                return $subscription;
            }
        });

        foreach ($subscriptions as $subscription){

            if ($subscription->user){
                if (! $subscription->user->suspended_notification->contains('day', 5)){

                    // Check for unpaid invoices and add entry.
                    $invoices = $this->checkUnpaidSubscriptionInvoices($subscription);
                    $this->addEntryDayFive($invoices, $subscription);

                }elseif (! $subscription->user->suspended_notification->contains('day', 15)){
                    // Check for unpaid invoices and add entry.
                    $invoices = $this->checkUnpaidSubscriptionInvoices($subscription);
                    $this->addEntryDayFifteen($invoices, $subscription);

                    // The Magic needs to happen here!
                    // get the subscription package for this user
//                    $topics = $subscription->plan->features;

                    // get all the user's tickets
//                    $tickets = $subscription->user->tickets;

//                    foreach ($tickets as $ticket){
//                        foreach ($ticket->event->pricings as $pricing){
//                            $matched = $topics->where('slug', $pricing->features->first()->slug);
//                            if (count($matched)){
//                                dd($pricing->event);
//                            }
//                        }
//                    }
//                    dd('failed');
                                                            /*
                     * Get all events related to this subscription = subscription topics.
                     * Cancel the invoice.
                     * Cancel all the event tickets.
                     * Cancel Subscription.
                     */
                }
            }
        }
    }

    /**
     * @param $subscription
     * @return mixed
     */
    public function checkUnpaidSubscriptionInvoices($subscription)
    {
        $invoices = $subscription->user->invoices
            ->where('type', 'subscription')
            ->where('paid', 0)
            ->where('status', 'unpaid');
        return $invoices->first();
    }

    /**
     * @param $invoices
     * @param $subscription
     * @return void
     */
    public function addEntryDayFive($invoices, $subscription)
    {
        if ($invoices && $invoices->count() >= 1){
            if (Carbon::parse($invoices->created_at)->addDays(5) <= Carbon::now()) {
                $notification = new SuspendNotification([
                    'subject' => 'Your account is overdue',
                    'day' => '5',
                    'email_address' => $subscription->user->email,
                    'sent_on' => date_format(Carbon::now(), 'y-m-d'),
                    'invoice_date' => date_format($invoices->created_at, 'y-m-d'),
                    'invoice_reference' => $invoices->reference
                ]);

                $subscription->user->suspended_notification()->save($notification);
                $this->info($subscription->user->first_name.' '.'has unpaid subscription invoice for 5 days');
                $this->sendEmailNotification($subscription->user, 'Your account is overdue', '5');
            }
        }
    }

    /**
     * @param $invoices
     * @param $subscription
     */
    public function addEntryDayFifteen($invoices, $subscription)
    {
        if ($invoices && $invoices->count() >= 1){
            if (Carbon::parse($invoices->created_at)->addDays(15) <= Carbon::now()) {
                $notification = new SuspendNotification([
                    'subject' => 'Your account is overdue',
                    'day' => '15',
                    'email_address' => $subscription->user->email,
                    'sent_on' => date_format(Carbon::now(), 'y-m-d'),
                    'invoice_date' => date_format($invoices->created_at, 'y-m-d'),
                    'invoice_reference' => $invoices->reference
                ]);

                $subscription->user->suspended_notification()->save($notification);
                $this->info($subscription->user->first_name.' '.'has unpaid subscription invoices for 15 days');
                $this->sendEmailNotification($subscription->user, 'Your account is overdue', '15');
            }
        }
    }

    public function sendEmailNotification($data, $subject, $date)
    {
        if(sendMailOrNot($data, 'accounts.overdueSubscriptionInvoiceDay')) {
        Mail::send('emails.accounts.overdueSubscriptionInvoiceDay'.$date, ['data' => $data ], function ($m) use ($data, $subject) {
            $m->from(config('app.email'), config('app.name'));
            $m->to($data->email, $data->first_name)->subject($subject);
        });
        }
    }
}
