<?php

namespace App\Http\Controllers;

use App\Note;
use App\Repositories\Subscription\upgradeSubscriptionRepository;
use App\Subscriptions\Models\Plan;
use App\Users\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\UpgradeSubscription;

class upgradeSubscriptionController extends Controller
{
    private $upgradeSubscriptionRepository;
    public function __construct(upgradeSubscriptionRepository $upgradeSubscriptionRepository)
    {
        $this->upgradeSubscriptionRepository = $upgradeSubscriptionRepository;
    }

    public function upgrade(Request $request)
    {
        $this->validate($request, ['reason' => 'required', 'new_subscription_plan' => 'required']);

        $oldPlan = Plan::find($request['current_cpd_package']);
        $newPlan = Plan::find($request['new_subscription_plan']);

        if ($newPlan->is_custom){
            $validator = Validator::make($request->all(), [
                'features' => 'required|array|max:8|min:8'
            ]);

            if ($validator->fails()){
                alert()->warning($validator->errors()->first(), 'warning');
                return back();
            }
        }

        $this->upgradeSubscriptionRepository->submitRequest($request, $oldPlan, $newPlan);
        alert()->success('Your upgrade request has been sent', 'Success!');
        return back();
    }

    public function pendingList(Request $request)
    {
        $upgradeSubscription = UpgradeSubscription::select('subscription_upgrades.*')
        ->leftjoin('subscription_upgrades as sub', function($join)
                {
                $join->on('sub.member_id','=','subscription_upgrades.member_id')->on('subscription_upgrades.id','<','sub.id');

                })
        ->whereRaw('sub.id is null')->where('subscription_upgrades.completed','0')->groupBy('subscription_upgrades.member_id')->paginate(8,['*']);
        return view('admin.members.upgrade.index', compact('upgradeSubscription'));
    }

    
    public function approve(Request $request, $member)
    {
        $member = User::find($member);
        $upgrade = $member->upgrades->last();

        /*
         * Check if the upgrade was not already processed.
         */
        if (! $upgrade->completed){
            $this->upgradeSubscriptionRepository->doTheUpgrade($upgrade);
            alert()->success("The upgrade was successfully processed.", 'Success!')->persistent('close');
            return redirect()->route('home');
        }else{
            alert()->warning("CPD Subscription upgrade was processed already, Cannot perform this function again.", 'Warning!')->persistent('close');
            return redirect()->route('home');
        }
    }

    public function decline(Request $request, $member)
    {
        DB::transaction(function () use($member, $request){
            $member = User::find($member);
            $upgrade = $member->upgrades->last();

            $note = new Note([
                'type' => 'general',
                'description' => 'Subscription upgrade was successfully cancelled!',
                'logged_by' => 'System',
            ]);

            $note->save();
            $member->notes()->save($note);
            $upgrade->update(['completed' => true, 'note_id' => $note->id]);
        });

        alert()->warning('The Upgrade was cancelled!', 'Success');
        return redirect()->route('home');
    }
}
