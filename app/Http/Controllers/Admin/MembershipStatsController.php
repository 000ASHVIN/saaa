<?php

namespace App\Http\Controllers\Admin;

use App\Subscriptions\Models\Plan;
use App\Users\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Style_NumberFormat;

class MembershipStatsController extends Controller
{
    public function show($plan = null)
    {
        if ($plan){
            $plan = $this->getPlan($plan);
            $subscriptions = $plan->subscriptions()->active()->paginate(10);
            $active = collect();
            $suspended = collect();

            foreach ($plan->subscriptions()->active()->get() as $subscription){
                if ($subscription->user->status == 'active'){
                    $active->push($subscription);
                }else{
                    $suspended->push($subscription);
                }
            }
            return view('admin.members.stats.show', compact('plan', 'subscriptions', 'active', 'suspended'));
        }else{
            $plan = new Plan([
                "name" => "Free Plan",
                "description" => "Non CPD",
                "price" => "00,00",
                "interval" => "month",
                "interval_count" => 1,
                "trial_period_days" => 0,
                "sort_order" => null,
                "inactive" => 0,
                "created_at" => null,
                "updated_at" => null,
                "is_custom" => 0
            ]);
            $subscriptions = User::whereDoesntHave('subscriptions')->paginate(10);
            return view('admin.members.stats.free_show', compact('plan', 'subscriptions'));
        }

    }

    public function export(Request $request, $plan = null)
    {
        if ($plan){
            $plan = $this->getPlan($plan);
            $subscriptions = $plan->subscriptions()->active()->get();

            $users = collect();
            foreach ($subscriptions as $subscription){
                $users->push($subscription->user);
            }

            Excel::create($plan->name.' '.$request->type.' '.$plan->interval.' '.'Subscribers', function($excel) use($users, $request) {
                $excel->sheet('Subscribers', function($sheet) use($users, $request) {
                    $sheet->appendRow(array(
                        'Created At',
                        'First Name',
                        'Last Name',
                        'Email',
                        'Cell',
                        'Subscription',
                        'Status',
                        'Province',
                        'Address Line One',
                        'Address Line Two',
                        'Address City',
                        'Type',
                    ));

                    if ($request->type != null){
                        foreach ($users as $user) {
                            $address = $user->addresses()->first();
                            $sheet->appendRow(array(
                                date_format($user->subscription('cpd')->created_at, 'd F Y'),
                                $user->first_name,
                                $user->last_name,
                                $user->email,
                                $user->cell,
                                $user->subscription('cpd')->plan->name,
                                $user->status,
                                ($address) ? ucfirst($address->province) : "N/A",
                                ($address) ? ucfirst($address->line_one) : "N/A",
                                ($address) ? ucfirst($address->line_two) : "N/A",
                                ($address) ? ucfirst($address->city) : "N/A",
                                ($address) ? ucfirst($address->type) : "N/A",
                            ));
                        }
                    }else{
                        foreach ($users as $user) {
                            $address = $user->addresses()->first();
                            $sheet->appendRow(array(
                                date_format($user->subscription('cpd')->created_at, 'd F Y'),
                                $user->first_name,
                                $user->last_name,
                                $user->email,
                                $user->cell,
                                $user->subscription('cpd')->plan->name,
                                $user->status,
                                ($address) ? ucfirst($address->province) : "N/A",
                                ($address) ? ucfirst($address->line_one) : "N/A",
                                ($address) ? ucfirst($address->line_two) : "N/A",
                                ($address) ? ucfirst($address->city) : "N/A",
                                ($address) ? ucfirst($address->type) : "N/A",
                            ));
                        }
                    }

                });
            })->export('xls');
        }else{
            $subscriptions = User::whereDoesntHave('subscriptions')->get();
            Excel::create('Free Subscribers', function($excel) use($subscriptions, $request) {
                $excel->sheet('Subscribers', function($sheet) use($subscriptions, $request) {
                    $sheet->appendRow(array(
                        'Created At',
                        'Name',
                        'Email',
                        'Cell',
                        'Designation',
                        'Subscription',
                        'Status',
                        'Province',
                        'address line one',
                        'address line two',
                        'city',
                        'type',
                    ));

                    foreach ($subscriptions as $user) {
                        $address = $user->addresses()->first();
                        $sheet->appendRow(array(
                            date_format($user->created_at, 'd F Y'),
                            $user->first_name.' '.$user->last_name,
                            $user->email,
                            $user->cell,
                            $user->profile->position,
                            "Free Member",
                            $user->status,
                            ($address) ? ucfirst($address->province) : "N/A",
                            ($address) ? ucfirst($address->line_one) : "N/A",
                            ($address) ? ucfirst($address->line_two) : "N/A",
                            ($address) ? ucfirst($address->city) : "N/A",
                            ($address) ? ucfirst($address->type) : "N/A",
                        ));
                    }
                });
            })->export('xls');
        }
    }

    /**
     * @param $plan
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function getPlan($plan)
    {
        $plan = Plan::with('subscriptions', 'subscriptions.user')->find($plan);
        return $plan;
    }
}
