<?php

use App\AppEvents\Date;
use App\AppEvents\Event;
use App\AppEvents\Extra;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class create_know_your_responsibilities_events_seeder extends Seeder
{
    public function run()
    {
        Model::unguard();
        $seminar = Event::create([
            'type' => 'seminar',
            'name' => 'Accountants’ liabilities and ethics – understand your rights and responsibilities',
            'slug' => 'accountants-liabilities-and-ethics-understand-your-rights-and-responsibilities',
            'description' => '<p>Coming soon...</p>',
            'short_description' => 'Disputes can arise between providers of professional services and their clients or other (third) parties for a number of reasons. Accordingly, accountants need to understand their risk and the extent of their potential liability when providing their services to clients and third parties.  The other side of the coin is the importance of ethical behaviour by accountants and their ethical responsibilities as professionals.  This half-day seminar will cover both aspects of the professional accountant’s role and will provide clarity on the legal requirements for practitioners and how you can protect yourself from against litigation, as well as provide guidance on how to deal with ethical issues.',
            'featured_image' => 'http://imageshack.com/a/img924/4364/65CNu8.jpg',
            'start_date' => new Carbon('20-09-2016'),
            'end_date' => new Carbon('28-09-2016'),
            'next_date' => new Carbon('20-09-2016'),
            'start_time' => new Carbon('20-09-2016 09:00'),
            'end_time' => new Carbon('20-09-2016 13:00'),
            'published_at' => Carbon::now(),
        ]);

        //Plans
        $seminar->plans()->attach([2, 3]); //only accounting cpd subscribers

        //Venues, Pricings and Dates
        $jhb = Venue::create([
            'name' => 'Mazars, Glenhove',
            'address_line_one' => '54 Glenhove Rd',
            'address_line_two' => '',
            'city' => 'Johannesburg',
            'province' => 'Gauteng',
            'country' => 'South Africa',
            'area_code' => '2196'
        ]);

        $jhb->dates()->save(new Date([
            'date' => new Carbon('20-09-2016')
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

        $price1->plans()->attach([2, 3], ['discount_value' => 100]);

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
            'date' => new Carbon('22-09-2016')
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
            'name' => 'Belmont Square',
            'address_line_one' => 'Belmont Rd',
            'address_line_two' => '',
            'city' => 'Rosebank',
            'province' => 'Cape Town',
            'country' => 'South Africa',
            'area_code' => '7700',
        ]);
        $ct->dates()->save(new Date([
            'date' => new Carbon('26-09-2016')
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

        $dbn = Venue::create([
            'name' => 'Coastlands Umhlanga',
            'address_line_one' => '329 Umhlanga Rocks Dr',
            'address_line_two' => '',
            'city' => 'Durban',
            'province' => 'Kwazulu Natal',
            'country' => 'South Africa',
            'area_code' => '4000',
        ]);
        $dbn->dates()->save(new Date([
            'date' => new Carbon('27-09-2016')
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
            'date' => new Carbon('28-09-2016')
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
    }
}
