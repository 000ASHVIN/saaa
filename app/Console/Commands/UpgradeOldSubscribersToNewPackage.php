<?php

namespace App\Console\Commands;

use App\Billing\InvoiceRepository;
use App\Subscriptions\Models\Period;
use App\Subscriptions\Models\Plan;
use App\Subscriptions\Models\Subscription;
use App\Users\User;
use Artisan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class UpgradeOldSubscribersToNewPackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upgrade:old-subscribers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will upgrade all 2016 subcribers to 2017 packages';

    private $subscription, $user, $plan, $invoiceRepository;

    /**
     * UpgradeOldSubscribersToNewPackage constructor.
     * @param Subscription $subscription
     * @param User $user
     * @param Plan $plan
     * @param InvoiceRepository $invoiceRepository
     */
    public function __construct(Subscription $subscription, User $user, Plan $plan, InvoiceRepository $invoiceRepository)
    {
        parent::__construct();
        $this->subscription = $subscription;
        $this->user = $user;
        $this->plan = $plan;
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->assignAccountingOnlySubscribers();
        $this->assignAccountingAndTaxSubscribers();
        $this->info('We are done!');
    }

    public function assignAccountingOnlySubscribers()
    {
        $accounting_new_plan = $this->plan->find(12);
        $subscriptions = Subscription::with('user')->where('plan_id', '4')->get();
        $this->info('We have '.count($subscriptions).' '.'Accounting Only Subscribers');

        foreach ($subscriptions as $subscription){
            if ($subscription->user){
                $user = $subscription->user;
                $this->DoUpgradeAndGenerateInvoice($user, $accounting_new_plan);
            }
        }
    }

    public function assignAccountingAndTaxSubscribers()
    {
        $tax_new_plan = $this->plan->find(3);
        $subscriptions = Subscription::with('user')->where('plan_id', '5')->get();
        $this->info('We have '.count($subscriptions).' '.'Accounting & Tax Subscribers');

        foreach ($subscriptions as $subscription){
            if ($subscription->user){
                $user = $subscription->user;
                $this->DoUpgradeAndGenerateInvoice($user, $tax_new_plan);
            }
        }
    }

    /**
     * @param $user
     * @param $plan
     * @internal param $i
     * @internal param $accounting_new_plan
     */
    public function DoUpgradeAndGenerateInvoice($user, $plan)
    {
        $this->info('Upgrading' . ' ' . $user->first_name . ' ' . $user->last_name . ' ' . 'to'.' '.$plan->name.' '.'Package');

        // Upgrade Subscription to new plan
        $user->subscription('cpd')->changePlan($plan->id)->save();

        // Set new subscription period.
        $period = new Period('month', 1, Carbon::parse('25 January 2017'));
        $user->subscription('cpd')->starts_at = $period->getStartDate();
        $user->subscription('cpd')->ends_at = $period->getEndDate();
        $user->subscription('cpd')->canceled_at = null;
        $user->subscription('cpd')->save();

        // Generate Subscription Invoice
        $invoice = $this->invoiceRepository->createSubscriptionInvoice($user, $plan);
        $invoice->created_at = Carbon::parse('25 January 2017');
        $invoice->save();

        $this->info("{$user->first_name} has been notified about his upgraded account");

        if(sendMailOrNot($user, 'upgradedSubscription')) {
        Mail::send('emails.upgradedSubscription', ['user' => $user, 'plan' => $plan ], function ($m) use ($user, $plan) {
            $m->from(config('app.email'), config('app.name'));
            $m->to($user->email, $user->first_name)->subject('Automatic renewal of CPD subscription');
        });
        }

        $clean = Artisan::call('clean:subscriptions');

//         Send Email To User
    }
}
