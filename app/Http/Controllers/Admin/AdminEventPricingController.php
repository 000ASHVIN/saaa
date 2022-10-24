<?php

namespace App\Http\Controllers\Admin;

use App\AppEvents\EventRepository;
use App\Repositories\Pricing\PricingRepository;
use App\Repositories\Venue\VenueRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SyncEvent;

class AdminEventPricingController extends Controller
{
    private $pricingRepository, $eventRepository, $venueRepository;

    public function __construct(
        PricingRepository $pricingRepository,
        EventRepository $eventRepository,
        VenueRepository $venueRepository
    )
    {
        $this->pricingRepository = $pricingRepository;
        $this->eventRepository = $eventRepository;
        $this->venueRepository = $venueRepository;
    }

    public function store(Request $request, $slug)
    {
        $event = $this->eventRepository->findBySlug($slug);
        $venue = $this->venueRepository->findVenue($request->venueList);
        $this->pricingRepository->createEventVenuePricing($event, $venue, $request->all());

        $sync_event = new SyncEvent();
        $sync_event->sync_event($event);

        alert()->success('Your pricing has been saved', 'Success!');
        return back()->withInput(['tab' => 'pricings']);
    }

    public function update(Request $request, $id)
    {
        $pricing = $this->pricingRepository->findPricing($id);
        $pricing->update($request->except('venueList', 'event', 'has_subscription_discount', 'subscription_discount'));
        $subscription_discount = $request->has_subscription_discount == 'on' ? $request->subscription_discount : null;
        $pricing->update(['subscription_discount' => $subscription_discount]);
        if ($request->venueList != $pricing->venue->id){
            $venue = $this->venueRepository->findVenue($request->venueList);
            $pricing->update(['venue_id' => $venue->id]);
        }

        $sync_event = new SyncEvent();
        $sync_event->sync_event($pricing->event);

        alert()->success('Your changes has been saved', 'Success!');
        return back()->withInput(['tab' => 'pricings']);
    }

    public function destroy($id)
    {
        $pricing = $this->pricingRepository->findPricing($id);
        $event = $pricing->event;
        $pricing->delete();

        $sync_event = new SyncEvent();
        $sync_event->sync_event($event);
        alert()->success('Your changes has been saved', 'Success!');
        return back()->withInput(['tab' => 'pricings']);
    }
}
