<?php

use App\AppEvents\Date;
use App\AppEvents\Event;
use App\AppEvents\Extra;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class create_accounting_legislation_update_event extends Seeder
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
            'name' => '2016 Update on Legislation affecting Accountants and their Clients',
            'slug' => '2016-update-on-legislation-affecting-accountants-and-their-clients',
            'description' => '<p><C></C>oming soon...</p>',
            'short_description' => 'As the year draws to a close, it is a good time to look back and take stock of the key legislative changes affecting your practice and your clients’ businesses, so that you can be fully informed and up to date for the year ahead.  Join our expert presenter for this four-hour session, as she unpacks all the key amendments and updates to existing Acts, including an extended look at the key changes to the Tax Administration Act.  Newly released Acts, such as the Employment Services Act and the forthcoming King IV Code, will also be examined.  ',
            'featured_image' => 'http://imageshack.com/a/img924/4364/65CNu8.jpg',
            'start_date' => new Carbon('14-11-2016'),
            'end_date' => new Carbon('23-11-2016'),
            'next_date' => new Carbon('14-11-2016'),
            'start_time' => new Carbon('14-11-2016 09:00'),
            'end_time' => new Carbon('14-11-2016 13:00'),
            'published_at' => Carbon::now(),
            'subscription_event' => '1',
            'category' => 'accounting_event'
        ]);

        //Plans
        $seminar->plans()->attach([2, 3]); // Accounting Only Event

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
            'date' => new Carbon('14-11-2016')
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
            'date' => new Carbon('16-11-2016')
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
        $price->plans()->attach([2, 3], ['discount_value' => 100]);

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
            'date' => new Carbon('17-11-2016')
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
            'date' => new Carbon('22-11-2016')
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
        $price->plans()->attach([2, 3], ['discount_value' => 100]);

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
            'date' => new Carbon('23-11-2016')
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

        //Extras
        $printedNotes = Extra::create([
            'name' => 'Printed notes',
            'price' => 100.00
        ]);

        $seminar->extras()->save($printedNotes);
        Model::reguard();
    }
}
