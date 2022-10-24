<?php
/**
 * Created by PhpStorm.
 * User: Tiaan Theunissen
 * Date: 2016/09/12
 * Time: 2:18 PM
 */

namespace App\Repositories\Lists;


use App\AppEvents\EventRepository;
use App\Subscriptions\Plan;
use App\Users\Profile;

class ListRepository
{

    // SAIBA CPD Subsribers
    public function saibaMembers()
    {
        $saiba_members = collect();
        $profiles = Profile::with('user')->where('BODY', 'SAIBA')->get();
        foreach ($profiles as $profile){
            if ($profile->user){
                $saiba_members->push($profile->user);
            }
        }
        return $saiba_members;
    }

    // All CPD Subscribers
    public function allCpdSubscribers()
    {
        $all_cpd_subscribers = collect();
        $plans = Plan::with('subscriptions.user')->where('id', '!=', '6')->get();
        foreach ($plans as $plan){
            foreach ($plan->subscriptions as $subscription){
                if(! $subscription->user == null){
                    $all_cpd_subscribers->push($subscription->user);
                }
            }
        }
        return $all_cpd_subscribers;
    }

    // Accounting Only CPD Subscriber
    public function accountingOnlyCpdSubscribers()
    {
        $accounting_only_cpd_subs = collect();
        $plans = Plan::with('subscriptions.user')->where('id', 2)->orWhere('id', 3)->get();
        foreach ($plans as $plan){
            foreach ($plan->subscriptions as $subscription){
                if(! $subscription->user == null){
                    $accounting_only_cpd_subs->push($subscription->user);
                }
            }
        }
        return $accounting_only_cpd_subs;
    }

    // Accounting and Tax Only CPD Subscriber
    public function AccountingAndTaxCpdSubscribers()
    {
        $accounting_and_tax_only_cpd_subs = collect();
        $plans = Plan::with('subscriptions.user')->where('id', 4)->orWhere('id', 5)->get();
        foreach ($plans as $plan){
            foreach ($plan->subscriptions as $subscription){
                if(! $subscription->user == null){
                    $accounting_and_tax_only_cpd_subs->push($subscription->user);
                }
            }
        }
        return $accounting_and_tax_only_cpd_subs;
    }
}