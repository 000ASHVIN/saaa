<?php

use App\AppEvents\Date;
use App\AppEvents\Event;
use App\AppEvents\Extra;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class PracticalConcernsAndConsiderationsEventSeeder extends Seeder
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
            'name' => 'Companies Act - practical concerns and considerations',
            'slug' => 'companies-act-practical-concerns-and-considerations',
            'description' => '<p>Coming soon...</p>',
            'featured_image' => '/assets/frontend/images/events/2016/companies-act-practical-concerns-and-considerations.jpg',
            'start_date' => new Carbon('27-06-2016'),
            'end_date' => new Carbon('06-07-2016'),
            'next_date' => new Carbon('27-06-2016'),
            'start_time' => new Carbon('27-06-2016 09:00'),
            'end_time' => new Carbon('27-06-2016 13:30'),
            'short_description' => 'An update and overview of the Companies Act illustrated by practical challenges, problems and solutions faced by accountants. Do you understand the responsibilities, liabilities, duties and risks of doing business as a director, shareholder, investor or accounting professional? Join Caryn Maitland for a lively and practical half-day seminar where she will provide information and guidance on key areas within the Act that are relevant to the work that practitioners are faced with on a daily basis.  She will raise flags for you to think about and provoke thoughts on how to improve the service you offer your clients.',
            'published_at' => Carbon::now()
        ]);

        //Plans
        $seminar->plans()->attach([2, 3, 4, 5]); //all cpd subs

        //Venues, pricings and dates
        $ct = Venue::create([
            'name' => 'The Wanderers Club',
            'address_line_one' => 'Broadway Boulevard &amp; Main Rd',
            'address_line_two' => 'Illovo',
            'city' => 'Johannesburg',
            'province' => 'Gauteng',
            'country' => 'South Africa',
            'area_code' => '4126',
        ]);
        $ct->dates()->save(new Date([
            'date' => new Carbon('27-06-2016')
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

        $jhb = Venue::create([
            'name' => 'Diep in die Berg',
            'address_line_one' => 'Disselboom Ave',
            'address_line_two' => '',
            'city' => 'Pretoria',
            'province' => 'Gauteng',
            'country' => 'South Africa',
            'area_code' => '0050'
        ]);
        $jhb->dates()->save(new Date([
            'date' => new Carbon('28-06-2016')
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
        $price->plans()->attach([2, 3, 4, 5], ['discount_value' => 100]);

        $cpt = Venue::create([
            'name' => 'Lord Charles Hotel',
            'address_line_one' => 'Main Rd & Broadway Boulevard',
            'address_line_two' => '',
            'city' => 'Somerset West',
            'province' => 'Cape Town',
            'country' => 'South Africa',
            'area_code' => '7130',
        ]);
        $cpt->dates()->save(new Date([
            'date' => new Carbon('30-06-2016')
        ]));

        $seminar->venues()->attach($cpt);
        $price = Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $cpt->id,
            'name' => 'Admission',
            'description' => 'Access to the event',
            'day_count' => 1,
            'price' => 1199,
            'cpd_hours' => 4
        ]);
        $price->plans()->attach([2, 3, 4, 5], ['discount_value' => 100]);

        $dbn = Venue::create([
            'name' => 'Coastlands Umhlanga',
            'address_line_one' => '329 Umhlanga Rocks Dr',
            'address_line_two' => '',
            'city' => 'Durban',
            'province' => 'KwaZulu-Natal',
            'country' => 'South Africa',
            'area_code' => '4000',
        ]);
        $dbn->dates()->save(new Date([
            'date' => new Carbon('05-07-2016')
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
            'date' => new Carbon('06-07-2016')
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
        Model::reguard();
    }
}
