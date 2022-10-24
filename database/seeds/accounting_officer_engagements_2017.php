<?php

use App\AppEvents\Date;
use App\AppEvents\Event;
use App\AppEvents\Extra;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class accounting_officer_engagements_2017 extends Seeder
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
            'name' => 'Accounting Officer Engagements',
            'slug' => 'accounting-officer-engagements-2017',
            'description' => '<p>Coming soon...</p>',
            'short_description' => 'Accounting officer engagements are the bread and butter offering of most accounting firms. Accounting officers have statutory recognition to issue specific types of reports on financial statements and other information. However, most, if not all of the relevant statutes do not stipulate how a member in practice should perform a reporting engagement. The purpose of this seminar is to assist members in practice to establish an engagement framework that can be applied when issuing accounting officer or similar reports on financial statements.',
            'subscription_event' => '',
            'category' => '',
            'featured_image' => 'http://imagizer.imageshack.us/a/img924/4364/65CNu8.jpg',
            'start_date' => new Carbon('07-03-2017'),
            'end_date' => new Carbon('16-03-2017'),
            'next_date' => new Carbon('07-03-2017'),
            'start_time' => new Carbon('07-03-2017 09:00'),
            'end_time' => new Carbon('07-03-2017 13:00'),
            'published_at' => Carbon::now()
        ]);

        //Venues, Pricings and Dates
        $jhb = Venue::create([
            'name' => 'The Wanderers Club',
            'address_line_one' => '21 North St & Rudd Drive',
            'address_line_two' => 'Illovo',
            'city' => 'Johannesburg',
            'province' => 'Gauteng',
            'country' => 'South Africa',
            'area_code' => '4126',
        ]);
        $jhb->dates()->save(new Date([
            'date' => new Carbon('07-03-2017')
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
        $price->features()->sync([1]);

        $pta = Venue::create([
            'name' => 'Diep in die Berg',
            'address_line_one' => '929 Disselboom Street',
            'address_line_two' => 'Wapadrand',
            'city' => 'Pretoria',
            'province' => 'Gauteng',
            'country' => 'South Africa',
            'area_code' => '0050',
        ]);
        $pta->dates()->save(new Date([
            'date' => new Carbon('08-03-2017')
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
        $price->features()->sync([1]);

        $cpt = Venue::create([
            'name' => 'Coastlands Musgrave Hotel',
            'address_line_one' => '315-319 Ridge Rd',
            'address_line_two' => '',
            'city' => 'Durban',
            'province' => 'Kwazulu Natal',
            'country' => 'South Africa',
            'area_code' => '4001'
        ]);
        $cpt->dates()->save(new Date([
            'date' => new Carbon('14-03-2017')
        ]));
        $seminar->venues()->attach($cpt);
        $price = Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $cpt->id,
            'name' => 'Admission',
            'description' => 'Access to the event',
            'day_count' => 1,
            'price' => 950,
            'cpd_hours' => 4
        ]);
        $price->features()->sync([1]);

        $dbn = Venue::create([
            'name' => 'Lord Charles Hotel',
            'address_line_one' => 'Broadway Boulevard &amp; Main Rd',
            'address_line_two' => 'Heldervue',
            'city' => 'Somerset West',
            'province' => 'Western Cape',
            'country' => 'South Africa',
            'area_code' => '7130',
        ]);
        $dbn->dates()->save(new Date([
            'date' => new Carbon('15-03-2017')
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
        $price->features()->sync([1]);

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
            'date' => new Carbon('16-03-2017')
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
        $price->features()->sync([1]);

        //Extras
        $printedNotes = Extra::create([
            'name' => 'Printed notes',
            'price' => 100.00
        ]);

        $seminar->extras()->save($printedNotes);

        Model::reguard();
    }
}
