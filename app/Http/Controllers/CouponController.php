<?php

namespace App\Http\Controllers;

use App\AppEvents\EventRepository;
use App\AppEvents\PromoCode;
use App\CouponUser;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Course;

class CouponController extends Controller
{

    /**
     * @var EventRepository
     */
    private $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {

        $this->eventRepository = $eventRepository;
    }

    public function check(Request $request, $id)
    {
        $user = auth()->user();
        // Find the promo code.
        $promo = PromoCode::findByCode($request->code);
        
        // check if promo codes are greater than 1
        if (count($promo)){

            if($request['type'] == 'course'){

                $course = Course::find($id);

                if ($course->promoCodes->contains('code', $promo->code)){
                    $coupon = new CouponUser([
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email_address' => $user->email,
                        'package' => ($user->subscribed('cpd') ? $user->subscription('cpd')->plan->name : "Free Member"),
                        'event_name' => $request['event_name'],
                        'type' => $request['type'],
                        'code' => $request['code'],
                    ]);

                    session()->put(PromoCode::SESSION_KEY . '.' . $promo->code, $promo->code);
                    $coupon->user()->associate($user);
                    $coupon->save();

                    alert()->success('Discount has been applied', 'Success!');
                    if($request->ajax()){
                        return response()->json(['message'=>'Discount has been applied','status'=>'1','object'=>$course->tojson()]);
                    }
                    return back();
                }else{
                    alert()->warning('Your discount code does not match this event.', 'Warning');
                    if($request->ajax()){
                        return response()->json(['message'=>'Your discount code does not match this event.','status'=>'0']);
                    }
                    return back();
                }

            }else{
                $event = $this->eventRepository->find($id);

                if ($event->promoCodes->contains('code', $promo->code)){
                    $coupon = new CouponUser([
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email_address' => $user->email,
                        'package' => ($user->subscribed('cpd') ? $user->subscription('cpd')->plan->name : "Free Member"),
                        'event_name' => $request['event_name'],
                        'type' => $request['type'],
                        'code' => $request['code'],
                    ]);

                    session()->put(PromoCode::SESSION_KEY . '.' . $promo->code, $promo->code);
                    $coupon->user()->associate($user);
                    $coupon->save();

                    alert()->success('Discount has been applied', 'Success!');
                    if($request->ajax()){
                        return response()->json(['message'=>'Discount has been applied','status'=>'1']);
                    }
                    return back();
                }else{
                    alert()->warning('Your discount code does not match this event.', 'Warning');
                    if($request->ajax()){
                        return response()->json(['message'=>'Your discount code does not match this event.','status'=>'0']);
                    }
                    return back();
                }
            }
        }else
            alert()->warning('Discount code supplied is invalid', 'Warning');
            if($request->ajax()){
                return response()->json(['message'=>'Discount code supplied is invalid','status'=>'0']);
            }
            return back();
    }
}
