<?php

use App\AppEvents\Date;
use App\AppEvents\Event;
use App\AppEvents\Extra;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CIPCUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seminar = Event::create([
            'type' => 'seminar',
            'name' => 'CIPC Update',
            'slug' => 'cipc-update',
            'description' => '<p>Coming soon...</p>',
            'featured_image' => '/assets/frontend/images/events/2016/cipc-update.jpg',
            'start_date' => new Carbon('10-02-2016'),
            'end_date' => new Carbon('24-02-2016'),
            'next_date' => new Carbon('10-02-2016'),
            'start_time' => new Carbon('10-02-2016 09:00'),
            'end_time' => new Carbon('10-02-2016 13:00'),
            'published_at' => Carbon::now()
        ]);

        //Plans
        $seminar->plans()->attach([2, 3, 4, 5]); //all cpd subs

        //Venues, Pricings and Dates
        $jhb = Venue::create([
            'name' => 'The Wanderers Club',
            'address_line_one' => '21 North St',
            'address_line_two' => '',
            'city' => 'Johannesburg',
            'province' => 'Gauteng',
            'country' => 'South Africa',
            'area_code' => '2196'
        ]);
        $jhb->dates()->save(new Date([
            'date' => new Carbon('10-02-2016')
        ]));
        $seminar->venues()->attach($jhb);
        $price1 = Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $jhb->id,
            'name' => 'Admission',
            'description' => 'Access to the event',
            'day_count' => 1,
            'price' => 1199,
            'cpd_hours' => 4
        ]);
        $price2 = Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $jhb->id,
            'name' => 'Admission 2 Test',
            'description' => 'Access to the event Test',
            'day_count' => 1,
            'price' => 1299,
            'cpd_hours' => 4
        ]);
        $price1->plans()->attach([2, 3, 4, 5], ['discount_value' => 100]);
        $price2->plans()->attach([2, 3, 4, 5], ['discount_value' => 100]);

        $dbn = Venue::create([
            'name' => 'Riverside Hotel',
            'address_line_one' => '10 Kenneth Kaunda Road',
            'address_line_two' => 'Durban North',
            'city' => 'Durban',
            'province' => 'KZN',
            'country' => 'South Africa',
            'area_code' => '4065',
        ]);
        $dbn->dates()->save(new Date([
            'date' => new Carbon('11-02-2016')
        ]));
        $seminar->venues()->attach($dbn);
        $price = Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $dbn->id,
            'name' => 'Admission',
            'description' => 'Access to the event',
            'day_count' => 1,
            'price' => 1199,
            'cpd_hours' => 4
        ]);
        $price->plans()->attach([2, 3, 4, 5], ['discount_value' => 100]);

        $pta = Venue::create([
            'name' => 'Diep in die Berg',
            'address_line_one' => '929 Disselboom Ave',
            'address_line_two' => '',
            'city' => 'Pretoria',
            'province' => 'Gauteng',
            'country' => 'South Africa',
            'area_code' => '0050',
        ]);
        $pta->dates()->save(new Date([
            'date' => new Carbon('16-02-2016')
        ]));
        $seminar->venues()->attach($pta);
        $price = Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $pta->id,
            'name' => 'Admission',
            'description' => 'Access to the event',
            'day_count' => 1,
            'price' => 1199,
            'cpd_hours' => 4
        ]);
        $price->plans()->attach([2, 3, 4, 5], ['discount_value' => 100]);

        $ct = Venue::create([
            'name' => 'Lord Charles Hotel',
            'address_line_one' => 'Broadway Boulevard &amp; Main Rd',
            'address_line_two' => 'Heldervue',
            'city' => 'Somerset West',
            'province' => 'Western Cape',
            'country' => 'South Africa',
            'area_code' => '7130',
        ]);
        $ct->dates()->save(new Date([
            'date' => new Carbon('17-02-2016')
        ]));
        $seminar->venues()->attach($ct);
        $price = Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $ct->id,
            'name' => 'Admission',
            'description' => 'Access to the event',
            'day_count' => 1,
            'price' => 1199,
            'cpd_hours' => 4
        ]);
        $price->plans()->attach([2, 3, 4, 5], ['discount_value' => 100]);

        $web = Venue::create([
            'name' => 'Online webinar / recording',
            'address_line_one' => 'Online',
            'address_line_two' => '',
            'city' => '',
            'province' => '',
            'country' => '',
            'area_code' => '',
            'type' => 'online'
        ]);
        $web->dates()->save(new Date([
            'date' => new Carbon('24-02-2016')
        ]));
        $seminar->venues()->attach($web);
        $price = Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $web->id,
            'name' => 'Online admission',
            'description' => 'Access to the webinar / recording',
            'day_count' => 1,
            'price' => 599,
            'cpd_hours' => 4
        ]);
        $price->plans()->attach([2, 3, 4, 5], ['discount_value' => 100]);

        //Extras
        $printedNotes = Extra::create([
            'name' => 'Printed notes',
            'price' => 100.00
        ]);

        $seminar->extras()->save($printedNotes);
    }
}
