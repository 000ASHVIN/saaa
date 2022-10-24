<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Subscriptions\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\SendAnnualRenewalNotification;
use App\Rep;

class AnnaulRenewalNotification extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:annaul-renewal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Annual renewal notifications. Send notification that the users annual subscription if coming up for renewal (First reminder sent 1 month before expiry date of annual plan) Second reminder sent 2 weeks before expiry date. Final reminder on the day the plan expires.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        if(env('APP_THEME') == 'taxfaculty') {

            // Before 2 weeks
            $after_two_weeks = Carbon::now()->addDays(14);
            $subscriptions = $this->getSubscriptions($after_two_weeks);
            foreach($subscriptions as $subscription) {

                if($subscription->active()) {
                    $this->sendMail($subscription, 'before_2_weeks');
                }

            }
            
            // On the day of plan expiry
            $today = Carbon::now();
            $subscriptions = $this->getSubscriptions($today);
            foreach($subscriptions as $subscription) {
                $this->sendMail($subscription, 'expiry_day');
            }
            
        }

    }

    public function getSubscriptions($date) {

        $subscriptions = Subscription::where('name', 'cpd')
            ->with('agent', 'user')
            ->whereHas('plan', function($query) {
                $query->where('interval', 'year');
                $query->where('price', '>', '0');
            })
            ->whereBetween('ends_at', [$date->format('Y-m-d').' 00:00:00', $date->format('Y-m-d').' 23:59:59'])
            ->get();
        return $subscriptions;

    }

    public function sendMail($subscription, $email) {

        $rep = null;
        // if($subscription->agent && $subscription) {
        //     $rep = $subscription->agent;
        // }

        // if(!$rep) {
        //     $rep = Rep::nextAvailable();
        //     $rep = $rep->user;
        //     $rep->update(['emailedLast' => Carbon::now()]);
        // }

        $job = new SendAnnualRenewalNotification($subscription, $rep, $email);
        $this->dispatch($job);

        $this->info('Sent email to '.$subscription->user->email);

    }

}
