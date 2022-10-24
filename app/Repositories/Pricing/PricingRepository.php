<?php
/**
 * Created by PhpStorm.
 * User: Tiaan Theunissen
 * Date: 2/2/2017
 * Time: 5:38 PM
 */

namespace App\Repositories\Pricing;


use App\AppEvents\Event;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use App\Body;
use Carbon\Carbon;
use DB;

class PricingRepository
{
    private $pricing;
    public function __construct(Pricing $pricing)
    {
        $this->pricing = $pricing;
    }

    // Create new pricing for given event with venue
    public function createEventVenuePricing(Event $event, Venue $venue, $data)
    {
        $seminarBodies = collect();
        $webinarBodies = collect();

        $bodies = Body::all();

        $bodies->filter(function ($body) use($seminarBodies, $webinarBodies){
            if ($body->webinar > 0){
                $webinarBodies->push($body);
            }

            if ($body->seminar > 0){
                $seminarBodies->push($body);
            }
        });

        DB::transaction(function () use($event, $venue, $data, $webinarBodies, $seminarBodies){
            $pricing = $this->pricing->create([
                'event_id' => $event->id,
                'venue_id' => $venue->id,
                'start_time' => Carbon::now(),
                'end_time' => Carbon::now(),
                'name' => ucfirst($data['name']) ? : "No Name",
                'price' => $data['price'],
                'subscription_discount' => (isset($data['has_subscription_discount']) && $data['has_subscription_discount'] == 'on') ? $data['subscription_discount'] : null,
                'is_active' => $data['is_active'] ? : false,
                'cpd_hours' => $data['cpd_hours'] ? : '0',
                'day_count' => $data['day_count'] ? : '0',
                'description' => $data['description'] ? : 'No Description',
                'can_manually_claim_cpd' => $data['can_manually_claim_cpd'] ? : false,
            ]);

            if ($pricing->venue->type == 'online'){
                // Webinar Pricings for professional bodies.
                if (count($webinarBodies)){
                    foreach ($webinarBodies as $body){
                        $pricing = $this->pricing->create([
                            'event_id' => $event->id,
                            'venue_id' => $venue->id,
                            'start_time' => Carbon::now(),
                            'end_time' => Carbon::now(),
                            'name' => ucfirst($body->title).' - '.$data['name'] ? : "No Name",
                            'price' => $body['webinar'],
                            'is_active' => $data['is_active'] ? : false,
                            'cpd_hours' => $data['cpd_hours'] ? : '0',
                            'day_count' => $data['day_count'] ? : '0',
                            'description' => $data['description'] ? : 'No Description',
                            'can_manually_claim_cpd' => $data['can_manually_claim_cpd'] ? : false,
                        ]);
                        $pricing->bodies()->save($body);
                    }
                }

            }else{
                // Seminar Pricings for professional bodies.
                if (count($seminarBodies)){
                    foreach ($seminarBodies as $body){
                        $pricing = $this->pricing->create([
                            'event_id' => $event->id,
                            'venue_id' => $venue->id,
                            'start_time' => Carbon::now(),
                            'end_time' => Carbon::now(),
                            'name' => ucfirst($body->title).' - '.$data['name'] ? : "No Name",
                            'price' => $body['seminar'],
                            'is_active' => $data['is_active'] ? : false,
                            'cpd_hours' => $data['cpd_hours'] ? : '0',
                            'day_count' => $data['day_count'] ? : '0',
                            'description' => $data['description'] ? : 'No Description',
                            'can_manually_claim_cpd' => $data['can_manually_claim_cpd'] ? : false,
                        ]);
                        $pricing->bodies()->save($body);
                    }
                }
            }
        });
    }

    public function findPricing($id)
    {
        return $this->pricing->find($id);
    }
}