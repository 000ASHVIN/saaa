<?php

use App\AppEvents\Date;
use App\AppEvents\Event;
use App\AppEvents\Extra;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class business_and_share_valuations_event_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $seminar = Event::create([
            'type' => 'seminar',
            'name' => 'Business and Share Valuations',
            'slug' => 'business-and-share-valuations',
            'description' => '<p>Coming soon...</p>',
            'featured_image' => 'http://imageshack.com/a/img924/4364/65CNu8.jpg',
            'start_date' => new Carbon('17-10-2016'),
            'end_date' => new Carbon('24-10-2016'),
            'next_date' => new Carbon('17-10-2016'),
            'start_time' => new Carbon('17-10-2016 09:00'),
            'end_time' => new Carbon('17-10-2016 13:00'),
            'short_description' => 'Business and share valuations (BSV) could be the new growth area for your accounting practice. Your firm can no longer just rely on traditional accounting work in order to be profitable. BSV services can deliver profits for your firm all year round and the profit margins are much bigger than plain financial statement preparation. Attend this half-day seminar to take up your share of the growing BSV market. ',
            'published_at' => Carbon::now()
        ]);

        //Plans
        $seminar->plans()->attach([2, 3]); // Accounting Only People NOT Accounting and Tax

        //Venues, pricings and dates
        // Start
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
            'date' => new Carbon('17-10-2016')
        ]));
        $seminar->venues()->attach($jhb);
        $price = Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $jhb->id,
            'name' => 'Admission',
            'description' => 'Access to the event',
            'day_count' => 1,
            'price' => 1199,
            'cpd_hours' => 4
        ]);
        $price->plans()->attach([2, 3], ['discount_value' => 100]);
        // End!

        // Start
        $pretoria = Venue::create([
            'name' => 'Diep in die Berg',
            'address_line_one' => '929 Disselboom Ave',
            'address_line_two' => '',
            'city' => 'Pretoria',
            'province' => 'Gauteng',
            'country' => 'South Africa',
            'area_code' => '0050',
        ]);
        $pretoria->dates()->save(new Date([
            'date' => new Carbon('18-10-2016')
        ]));
        $seminar->venues()->attach($pretoria);
        $price = Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $pretoria->id,
            'name' => 'Admission',
            'description' => 'Access to the event',
            'day_count' => 1,
            'price' => 1199,
            'cpd_hours' => 4
        ]);
        $price->plans()->attach([2, 3], ['discount_value' => 100]);
        // End

        //Start
        $durban = Venue::create([
            'name' => 'Coastlands Musgrave',
            'address_line_one' => '315-319 Ridge Rd',
            'address_line_two' => '',
            'city' => 'Durban',
            'province' => 'Kwazulu Natal',
            'country' => 'South Africa',
            'area_code' => '0050',
        ]);
        $durban->dates()->save(new Date([
            'date' => new Carbon('19-10-2016')
        ]));
        $seminar->venues()->attach($durban);
        $price = Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $durban->id,
            'name' => 'Admission',
            'description' => 'Access to the event',
            'day_count' => 1,
            'price' => 1199,
            'cpd_hours' => 4
        ]);
        $price->plans()->attach([2, 3], ['discount_value' => 100]);
        // End

        // Start
        $ct = Venue::create([
            'name' => 'Belmont Square',
            'address_line_one' => 'Belmont Rd',
            'address_line_two' => '',
            'city' => 'Rosebank',
            'province' => 'Cape Town',
            'country' => 'South Africa',
            'area_code' => '7700',
        ]);
        $ct->dates()->save(new Date([
            'date' => new Carbon('20-10-2016')
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
        $price->plans()->attach([2, 3], ['discount_value' => 100]);
        // End

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
            'date' => new Carbon('24-10-2016')
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
        $price->plans()->attach([2, 3], ['discount_value' => 100]);

        //Extras
        $printedNotes = Extra::create([
            'name' => 'Printed notes',
            'price' => 100.00
        ]);
        $seminar->extras()->save($printedNotes);
        Model::reguard();
    }
}
