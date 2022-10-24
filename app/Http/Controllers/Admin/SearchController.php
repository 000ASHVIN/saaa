<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminSearchRequest;
use App\InvoiceOrder;
use App\Users\Profile;
use App\Users\User;
use App\Http\Requests;
use App\Billing\Invoice;
use App\Http\Controllers\Controller;
use App\Wallet;
use DB;
use Illuminate\Http\Request;
use App\Rep;
use Carbon\Carbon;

use App\Subscriptions\Models\Plan;
use App\Subscriptions\Models\Subscription;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $members = collect();
        $search = "";
        return view('admin.members.search',compact('members','search'));
    }
    public function searchData(Request $request)
    {
        $user =  User::withTrashed()->where('is_merged', false);
        if($request->full_name!=""){
            $sanitizedSearch = $this->trim($request->full_name);
            $strippedSanitizedSearch = preg_replace('/\s/', '', $sanitizedSearch);
            $user= $user->where(function($query) use($sanitizedSearch, $strippedSanitizedSearch) {
                $query->where('first_name', 'LIKE', '%' . $sanitizedSearch . '%')
                ->orWhere('last_name', 'LIKE', '%' . $sanitizedSearch . '%')
                ->orWhereRaw('CONCAT(first_name,last_name) like ?', ['%' . $strippedSanitizedSearch . '%']); 
            });
        } 
        if($request->email!=""){
            $sanitizedSearch = $this->trim($request->email);
            $user= $user->Where('email', 'LIKE', '%' . $sanitizedSearch . '%');
        }
        if($request->cell!=""){
            $sanitizedSearch = $this->trim($request->cell);
            $user= $user->Where('cell', 'LIKE', '%' . $sanitizedSearch . '%');
        }
        if($request->status!=""){
            if($request->status != 'all') {
                $sanitizedSearch = $this->trim($request->status);
                $user= $user->Where('status',  $sanitizedSearch);
            }
        }
        if($request->subscription!=""){
           $user = $user->select('users.*')->join(DB::raw("(select * from (SELECT * FROM `subscriptions` WHERE name='cpd' group by id ) s where 1 group by s.user_id) sub"),'sub.user_id','=','users.id')
             ->where(function($query) {
                return $query->Where('sub.canceled_at', null)
                ->orwhere('sub.canceled_at', '<=', Carbon::now());
                })
            ->where('sub.ends_at', '>=', Carbon::now())->where('sub.plan_id',$request->subscription);

            // $profiles = Subscription::with('plan')->join('plans','subscriptions.plan_id','=','plans.id')->where('plans.name','!=','None')->where(DB::raw('Lower(plans.name)'), 'LIKE', '%' . $search . '%')->get();
        } 
        if($request->account!=""){
            // dd($request->account);
            $sanitizedSearch =$request->account;
            if($request->subscription==""){
                $user = $user->select('users.*')->join(DB::raw("(select * from (SELECT * FROM `subscriptions` WHERE name='cpd' group by id ) s where 1 group by s.user_id) sub"),'sub.user_id','=','users.id')
                ->where(function($query) {
                   return $query->Where('sub.canceled_at', null)
                   ->orwhere('sub.canceled_at', '<=', Carbon::now());
                   })
               ->where('sub.ends_at', '>=', Carbon::now());
            } 
        
            $user = $user->where('agent_id',$sanitizedSearch);
        }
        if($request->body!=""){
            $sanitizedSearch = trim(strtolower($request->body));
            $strippedSanitizedSearch = preg_replace('/\s/', '', $sanitizedSearch);
            $user = $user->join('profiles','profiles.user_id','=','users.id')->where('profiles.body','!=','None')->where(DB::raw('Lower(profiles.body)'), 'LIKE', '%' . $sanitizedSearch . '%');
        }
        $user = $user->paginate(8);
        $members = $user; 
        
        return view('admin.members.search', ['search' => "", 'members' => $members]);
        
    }
  
    public function search(AdminSearchRequest $request)
    { 
        $search = $request->search;
        if ($request['tag'] == 'name' || $request['tag'] == 'email'){
            return $this->searchForMembersByEmailNameAndSurname($search);

        }elseif($request['tag'] == 'cell' || $request['tag'] == 'id_number'){
            return $this->searchForMembersByCellAndID($search);

        }elseif ($request['tag'] == 'invoice') {
            return $this->getMemberProfileByInvoiceNo($search);

        }elseif ($request['tag'] == 'company'){
            return $this->getMemberProfileByCompanyName($search);

        }elseif ($request['tag'] == 'wallet'){
            return $this->getMemberProfileByWalletNo($search);
        }
        elseif ($request['tag'] == 'status'){
            return $this->searchForMembersByStatus($search);
        }
        elseif ($request['tag'] == 'bodies'){
            return $this->searchForMembersByBodies($search);
        }
        elseif ($request['tag'] == 'subscription'){
            return $this->searchForMembersBySubscription($search);
        }
        else{
            return $this->getMemberProfileByOrderNo($search);
        }
        
    }

    public function getMemberProfileByInvoiceNo($search)
    {
        $invoice = Invoice::where('reference', 'LIKE', '%' . $search)->first();
        if (! $invoice) {
            alert()->error('No such invoice found.', 'Error');
            return redirect()->back();
        }
        $member = $invoice->user;
        alert()->success('Found the invoice', 'Success');
        return redirect()->route('admin.members.show', $member->id);
    } 

    public function getMemberProfileByWalletNo($search)
    {
        $wallet = Wallet::where('reference', 'LIKE', '%' . $search)->first();
        if (! $wallet) {
            alert()->error('No such U-Wallet found.', 'Error');
            return redirect()->back();
        }
        $member = $wallet->user;
        alert()->success('Found the U-Wallet', 'Success');
        return redirect()->route('admin.members.show', $member->id);
    }

    public function getMemberProfileByOrderNo($search)
    {
        $po = InvoiceOrder::where('reference', 'LIKE', '%' . $search)->first();
        if (! $po) {
            alert()->error('No such purchase order found.', 'Error');
            return redirect()->back();
        }
        $member = $po->user;
        alert()->success('Found the purchase order', 'Success');
        return redirect()->route('admin.members.show', $member->id);
    }

    public function getMemberProfileByCompanyName($search)
    {
        $profiles = Profile::where('company', 'LIKE', '%' . $search)->get();

        if (count($profiles) >= 2){
            $members = collect();
            foreach ($profiles as $profile){
                $members->push($profile->user);
            }

            return view('admin.members.search', ['search' => $search, 'members' => $members]);

        }elseif (count($profiles) >= 1){
            $member = $profiles->first()->user;
            alert()->success('Found the profile', 'Success');
            return redirect()->route('admin.members.show', $member->id);

        }elseif (! $profiles){
            alert()->error('No such profile found.', 'Error');
            return redirect()->back();
        }else{
            alert()->error('No such profile found.', 'Error');
            return redirect()->back();
        }
    }

    public function searchForMembersByEMailNameAndSurname($search)
    {
        $sanitizedSearch = trim(strtolower($search));
        $strippedSanitizedSearch = preg_replace('/\s/', '', $sanitizedSearch);

        $members = User::withTrashed()->where('is_merged', false)->where(function($query) use($sanitizedSearch, $strippedSanitizedSearch) {
            $query->where('first_name', 'LIKE', '%' . $sanitizedSearch . '%')
            ->orWhere('last_name', 'LIKE', '%' . $sanitizedSearch . '%')
            ->orWhereRaw('CONCAT(first_name,last_name) like ?', ['%' . $strippedSanitizedSearch . '%'])
            ->orWhere('email', 'LIKE', '%' . $sanitizedSearch . '%');
        })->get();

        if (count($members) > 0)
            alert()->success('Found ' . count($members) . ' member(s) for "' . $search . '"', 'Success');
        else
            alert()->error('No results found for "' . $search . '"', 'Error');
        if (count($members) == 1){
            if ($members->first()->status == 'active'){
                return redirect()->route('admin.members.show', $members->first()->id);
            }else{
                return view('admin.members.search', ['search' => $sanitizedSearch, 'members' => $members]);
            }
        }else{
            return view('admin.members.search', ['search' => $sanitizedSearch, 'members' => $members]);
        } 
    }

    public function searchForMembersByCellAndID($search)
    {
        $sanitizedSearch = trim(strtolower($search));
        $members = User::where('is_merged', false)->where('cell', 'LIKE', '%' . $sanitizedSearch . '%')
            ->orWhere('id_number', 'LIKE', '%' . $sanitizedSearch . '%')
            ->orWhere('alternative_cell', 'LIKE', '%' . $sanitizedSearch . '%')
            ->get();

        if (count($members) > 0)
            alert()->success('Found ' . count($members) . ' member(s) for "' . $search . '"', 'Success');
        else
            alert()->error('No results found for "' . $search . '"', 'Error');
        if (count($members) == 1){
            if ($members->first()->status == 'active'){
                return redirect()->route('admin.members.show', $members->first()->id);
            }else{
                return view('admin.members.search', ['search' => $sanitizedSearch, 'members' => $members]);
            }
        }else{
            return view('admin.members.search', ['search' => $sanitizedSearch, 'members' => $members]);
        }
    }

    public function searchForMembersByStatus($search)
    {
        $sanitizedSearch = trim(strtolower($search));
        $strippedSanitizedSearch = preg_replace('/\s/', '', $sanitizedSearch);
        $members = User::where('is_merged', false)->where('status', 'LIKE', '%' . $sanitizedSearch . '%')->get();

        if (count($members) > 0)
            alert()->success('Found ' . count($members) . ' member(s) for "' . $search . '"', 'Success');
        else
            alert()->error('No results found for "' . $search . '"', 'Error');
        if (count($members) == 1){
            if ($members->first()->status == 'active'){
                return redirect()->route('admin.members.show', $members->first()->id);
            }else{
                return view('admin.members.search', ['search' => $sanitizedSearch, 'members' => $members]);
            }
        }else{
            return view('admin.members.search', ['search' => $sanitizedSearch, 'members' => $members]);
        } 
    } 

    public function searchForMembersByBodies($search)
    {
      
        $sanitizedSearch = trim(strtolower($search));
        $strippedSanitizedSearch = preg_replace('/\s/', '', $sanitizedSearch);
        $members = User::with('profile')->join('profiles','profiles.user_id','=','users.id')->where('users.is_merged',false)->where('profiles.body','!=','None')->where(DB::raw('Lower(profiles.body)'), 'LIKE', '%' . $sanitizedSearch . '%')->get();
        // dd($members);
 
        if (count($members) > 0) 
            alert()->success('Found ' . count($members) . ' member(s) for "' . $search . '"', 'Success');
        else
            alert()->error('No results found for "' . $search . '"', 'Error');
        if (count($members) == 1){
            if ($members->first()->status == 'active'){
                return redirect()->route('admin.members.show', $members->first()->id);
            }else{
                return view('admin.members.search', ['search' => $sanitizedSearch, 'members' => $members]);
            }
        }else{
            return view('admin.members.search', ['search' => $sanitizedSearch, 'members' => $members]);
        } 
    } 

    public function searchForMembersBySubscription($search)
    {
        $profiles = Subscription::with('plan')->join('plans','subscriptions.plan_id','=','plans.id')->where('plans.name','!=','None')->where(DB::raw('Lower(plans.name)'), 'LIKE', '%' . $search . '%')->get();

        // $profiles = Plan::with('subscriptions')->join('subscriptions','subscriptions.plan_id','=','plans.id')->where('plans.name','!=','None')->where(DB::raw('Lower(plans.name)'), 'LIKE', '%' . $search . '%')->get();

        //  dd($profiles);

        if (count($profiles) >= 2){
            $members = collect();
           
            foreach ($profiles as $profile){
                $members->push($profile->user);
            }

            return view('admin.members.search', ['search' => $search, 'members' => $members]);

        }elseif (count($profiles) >= 1){
            $member = $profiles->first()->user;
            alert()->success('Found the profile', 'Success');
            return redirect()->route('admin.members.show', $member->id);

        }elseif (! $profiles){
            alert()->error('No such profile found.', 'Error');
            return redirect()->back();
        }
    }
    private function trim($search)
    {
        $sanitizedSearch = trim(strtolower($search));
        $strippedSanitizedSearch = preg_replace('/\s/', '', $sanitizedSearch);
        return $strippedSanitizedSearch;
    }
   
}
