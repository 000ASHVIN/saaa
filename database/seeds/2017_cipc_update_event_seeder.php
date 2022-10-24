<?php

use App\AppEvents\Date;
use App\AppEvents\Event;
use App\AppEvents\Extra;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class next_cipc_update_event_seeder extends Seeder
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
            'name' => 'CIPC Update 2017',
            'slug' => 'cipc-update-2017',
            'description' => '<p>Coming soon...</p>',
            'short_description' => 'This half-day seminar has been designed to bring you up to date with the latest legislative developments relating to CIPC lodgements and company secretarial work, enabling you to ensure that the correct procedures are followed when starting and running a company.  It will provide sufficient detail for those who require an update as well as an overview of the constitution of a company for those new to the subject.',
            'subscription_event' => '',
            'category' => '',
            'featured_image' => 'http://imagizer.imageshack.us/a/img924/4364/65CNu8.jpg',
            'start_date' => new Carbon('14-02-2017'),
            'end_date' => new Carbon('23-02-2017'),
            'next_date' => new Carbon('14-02-2017'),
            'start_time' => new Carbon('14-02-2017 09:00'),
            'end_time' => new Carbon('14-02-2017 13:00'),
            'published_at' => Carbon::now()
        ]);

        //Venues, Pricings and Dates
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
            'date' => new Carbon('14-02-2017')
        ]));
        $seminar->venues()->attach($ct);
        $price = Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $ct->id,
            'name' => 'Admission',
            'description' => 'Access to the event',
            'day_count' => 1,
            'price' => 950,
            'cpd_hours' => 4
        ]);
        $price->features()->sync([3]);

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
            'date' => new Carbon('15-02-2017')
        ]));
        $seminar->venues()->attach($pta);
        $price = Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $pta->id,
            'name' => 'Admission',
            'description' => 'Access to the event',
            'day_count' => 1,
            'price' => 950,
            'cpd_hours' => 4
        ]);
        $price->features()->sync([3]);

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
            'date' => new Carbon('21-02-2017')
        ]));
        $seminar->venues()->attach($web);
        $price = Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $web->id,
            'name' => 'Online admission',
            'description' => 'Access to the webinar / recording',
            'day_count' => 1,
            'price' => 399,
            'cpd_hours' => 4
        ]);
        $price->features()->sync([3]);

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
            'date' => new Carbon('22-02-2017')
        ]));
        $seminar->venues()->attach($dbn);
        $price = Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $dbn->id,
            'name' => 'Admission',
            'description' => 'Access to the event',
            'day_count' => 1,
            'price' => 950,
            'cpd_hours' => 4
        ]);
        $price->features()->sync([3]);

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
            'date' => new Carbon('23-02-2017')
        ]));

        $seminar->venues()->attach($jhb);

        $price = Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $jhb->id,
            'name' => 'Admission',
            'description' => 'Access to the event',
            'day_count' => 1,
            'price' => 950,
            'cpd_hours' => 4
        ]);
        $price->features()->sync([3]);

        //Extras
        $printedNotes = Extra::create([
            'name' => 'Printed notes',
            'price' => 100.00
        ]);

        $seminar->extras()->save($printedNotes);

        Model::reguard();
    }
}
