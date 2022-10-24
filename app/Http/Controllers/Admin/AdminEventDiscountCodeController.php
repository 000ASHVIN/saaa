<?php

namespace App\Http\Controllers\Admin;

use App\AppEvents\EventRepository;
use App\AppEvents\PromoCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SyncEvent;

class AdminEventDiscountCodeController extends Controller
{
    /**
     * @var EventRepository
     */
    private $eventRepository;
    /**
     * AdminEventDiscountCodeController constructor.
     * @param EventRepository $eventRepository
     */
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function store(Request $request, $event)
    {
        $validator = \Validator::make($request->except('_token'), [
            'code' => 'required|unique:promo_codes'
        ]);

        if ($validator->fails()){
            alert()->error('Discount code already exists', 'Error');
            return back()->withInput(['tab' => 'discounts']);
        }

        $event = $this->eventRepository->findBySlug($event);
        $discount = new PromoCode($request->except('_token'));
        $event->promoCodes()->save($discount);

        // $sync_event = new SyncEvent();
        // $sync_event->sync_event($event);

        alert()->success('Your discount code has been created!', 'Success!');
        return back()->withInput(['tab' => 'discount']);
   }

    public function update(Request $request, $code)
    {
        $code = PromoCode::findByCode($code);
        $code->update($request->except('_token'));

        // $event = $code->events->first();
        // $sync_event = new SyncEvent();
        // $sync_event->sync_event($event);

        alert()->success('Your discount code has been updated!', 'Success!');
        return back()->withInput(['tab' => 'discount']);
   }

    public function destroy($code)
    {
        $code = PromoCode::findByCode($code);
        // $event = $code->events->first();
        
        $code->delete();

        // $sync_event = new SyncEvent();
        // $sync_event->sync_event($event);
        alert()->success('Your discount code has been deleted!', 'Success!');
        return back()->withInput(['tab' => 'discount']);
   }
}
