<?php

namespace App\Http\Controllers\Admin;

use App\Subscriptions\Models\Subscription;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class getSubscribersForExport extends Controller
{
    public function getTaxUpdateSubscribers()
    {
        $users = collect();
        $subscriptions = Subscription::with('user', 'plan', 'user')->active()->get();

        /*
         * Check user plan features if he does have access to the relevant event.
         */
        $subscriptions->each(function ($subscription) use($users){
            if ($subscription->plan->features->contains('slug', 'monthly-sars-and-tax-update-webinar')){
                if ($subscription->user->status != 'suspended'){
                    $users->push($subscription->user);
                }


        /*
         * Check if user has custom plan features, if yes, check for the event.
         */
            }elseif ($subscription->user->custom_features){
                if (in_array('monthly-sars-and-tax-update-webinar', $subscription->user->custom_features->features)){
                    if ($subscription->user->status != 'suspended'){
                        $users->push($subscription->user);
                    }
                }
            };
        });

        return Excel::create('Subscribers', function($excel) use($users) {
            $excel->sheet('Exports', function($sheet) use($users) {
                $sheet->fromArray($users->toArray());
            });
        })->export('xls');
    }
}
