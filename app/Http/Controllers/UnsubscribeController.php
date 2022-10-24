<?php

namespace App\Http\Controllers;

use App\AppEvents\Event;
use App\Billing\Invoice;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Jobs\SendNewEventNotification;
use App\Unsubscribe;
use App\Users\User;
use Carbon\Carbon;
use Mail;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Repositories\Invoice\SendInvoiceRepository;
use App\Repositories\Order\SendOrderRepository;
use App\Repositories\Sendinblue\SendingblueRepository;
use App\Resubscribe;

class UnsubscribeController extends Controller
{
    use DispatchesJobs;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function unsubscribe($email)
    {
        $user = User::where('email', $email)->first();

        // $sendingblueRepository = new SendingblueRepository();
        // $input['emailBlacklisted'] = true;
        // $input['smsBlacklisted'] = true;
        // $sendingblueRepository->updateContact($user->email, $input);
        
        $unsubscribed = Unsubscribe::where('email', $email)->first();
        if(!$unsubscribed) {
            $unsubscribe = new Unsubscribe();
            $unsubscribe->user_id = ($user)?$user->id:0;
            $unsubscribe->email = $email;
            $unsubscribe->save();
        }

        if($user) {
            $user->settings()->merge([]);
        }

        return view('unsubscribe.index', compact('user'));
    }

    public function addUnsubscribeReason(Request $request, $email) {
        $unsubscribe = Unsubscribe::where('email', $email)->first();
        if($unsubscribe) {
            $unsubscribe->update(['reason' => json_encode($request->reason)]);
        }
        return redirect('/');
    }

    public function resubscribe($email) {
        $user = User::where('email', $email)->first();

        $unsubscribers = Unsubscribe::where('email', $email)->get();
        if(count($unsubscribers) > 0) {
            foreach($unsubscribers as $unsubscribe) {
                $unsubscribe->delete();
            }
        }
        return view('unsubscribe.resubscribe', compact('user'));
    }

    public function resubscribeType(Request $request, $email) {

        $user = User::where('email', $email)->first();
        
        $unsubscribers = Unsubscribe::where('email', $email)->get();
        $resubscribe = Resubscribe::where('email', $email)->first();
        if(!$resubscribe) {
            $resubscribe = new Resubscribe();
        }

        $resubscribe->user_id = ($user)?$user->id:0;
        $resubscribe->email = $email;
        
        if($request->has('unsubscribe_all') && $request->unsubscribe_all == 'on') {
            if(count($unsubscribers) == 0) {
                $unsubscribe = new Unsubscribe();
                $unsubscribe->user_id = ($user)?$user->id:0;
                $unsubscribe->email = $email;
                $unsubscribe->save();
            }

            $resubscribe->unsubscribe_all = 1;

            if($user) {
                $user->settings()->merge([]);
            }
        } else {
            
            if(count($unsubscribers) > 0) {
                foreach($unsubscribers as $unsubscribe) {
                    $unsubscribe->delete();
                }
            }
            $resubscribe->unsubscribe_all = 0;
            $resubscribe->subscribed_types = json_encode($request->subscribed_types);

            
            if( $request->subscribed_types && in_array('Events and webinars', $request->subscribed_types) ) {
                $user->settings()->merge(['event_notifications' => '1']);
            }
        }
        $resubscribe->save();
        return redirect('/');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
