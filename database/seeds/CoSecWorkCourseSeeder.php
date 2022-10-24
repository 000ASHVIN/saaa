<?php

use App\AppEvents\Date;
use App\AppEvents\Extra;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use App\AppEvents\Event;

class CoSecWorkCourseSeeder extends Seeder
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
            'name' => 'Company Secretarial Work Online Certificate Course and Workshop',
            'slug' => 'company-secretarial-work-online-certificate-course-and-workshop',
            'description' => '<p>Coming soon...</p>',
            'featured_image' => '/assets/frontend/images/events/2016/company-secretarial-work-online-certificate-course-and-workshop.jpg',
            'start_date' => new Carbon('14-06-2016'),
            'end_date' => new Carbon('22-06-2016'),
            'next_date' => new Carbon('14-06-2016'),
            'start_time' => new Carbon('14-06-2016 09:00'),
            'end_time' => new Carbon('14-06-2016 17:00'),
            'published_at' => Carbon::now()
        ]);
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
            'date' => new Carbon('14-06-2016')
        ]));
        $seminar->venues()->attach($jhb);
        Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $jhb->id,
            'name' => 'Admission',
            'description' => 'Access to the event',
            'day_count' => 1,
            'price' => 2199,
            'cpd_hours' => 8
        ]);

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
            'date' => new Carbon('15-06-2016')
        ]));
        $seminar->venues()->attach($pta);
        Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $pta->id,
            'name' => 'Admission',
            'description' => 'Access to the event',
            'day_count' => 1,
            'price' => 2199,
            'cpd_hours' => 8
        ]);

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
            'date' => new Carbon('21-06-2016')
        ]));
        $seminar->venues()->attach($dbn);
        Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $dbn->id,
            'name' => 'Admission',
            'description' => 'Access to the event',
            'day_count' => 1,
            'price' => 2199,
            'cpd_hours' => 8
        ]);

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
            'date' => new Carbon('22-06-2016')
        ]));
        $seminar->venues()->attach($ct);
        Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $ct->id,
            'name' => 'Admission',
            'description' => 'Access to the event',
            'day_count' => 1,
            'price' => 2199,
            'cpd_hours' => 8
        ]);

        $certificate = Venue::create([
            'name' => 'Online certificate course only',
            'address_line_one' => 'Online',
            'address_line_two' => '',
            'city' => '',
            'province' => '',
            'country' => '',
            'area_code' => '',
            'type' => 'online'
        ]);
        $certificate->dates()->save(new Date([
            'date' => new Carbon('15-04-2016')
        ]));
        $seminar->venues()->attach($certificate);
        Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $certificate->id,
            'name' => 'Online certificate course only',
            'description' => 'Company Secretarial Work',
            'day_count' => 1,
            'price' => 7999.00,
            'cpd_hours' => 30
        ]);

        //Extras
        $certificateCourse = Extra::create([
            'name' => 'Online certificate course',
            'price' => 7500.00,
            'cpd_hours' => 30
        ]);

        $seminar->extras()->save($certificateCourse);

    }
}
