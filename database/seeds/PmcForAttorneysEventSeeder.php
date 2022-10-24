<?php

use App\AppEvents\Date;
use App\AppEvents\Event;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class PmcForAttorneysEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $conference = Event::create([
            'type' => 'conference',
            'name' => '2nd Annual Practice Management Conference for Attorneys',
            'slug' => '2nd-annual-practice-management-conference-for-attorneys',
            'description' => '<p>Coming soon...</p>',
            'featured_image' => '/assets/frontend/images/events/2016/2nd-annual-practice-management-conference-for-attorneys.jpg',
            'start_date' => new Carbon('01-08-2016'),
            'end_date' => new Carbon('02-08-2016'),
            'next_date' => new Carbon('01-08-2016'),
            'start_time' => new Carbon('01-08-2016 09:00'),
            'end_time' => new Carbon('02-08-2016 17:00'),
            'published_at' => Carbon::now()
        ]);

        $bytes = Venue::create([
            'name' => 'Bytes Conference Centre',
            'address_line_one' => '3rd Rd',
            'address_line_two' => 'Midrand',
            'city' => 'Johannesburg',
            'province' => 'Gauteng',
            'country' => 'South Africa',
            'area_code' => '1685',
        ]);

        $bytes->dates()->save(new Date([
            'date' => new Carbon('01-08-2016')
        ]));

        $bytes->dates()->save(new Date([
            'date' => new Carbon('02-08-2016')
        ]));

        $conference->venues()->attach($bytes);
        Pricing::create([
            'event_id' => $conference->id,
            'venue_id' => $bytes->id,
            'name' => 'Practising Attorneys 1 Day Pass',
            'description' => '1 Day Access to the conference',
            'day_count' => 1,
            'price' => 1250,
            'cpd_hours' => 8
        ]);
        Pricing::create([
            'event_id' => $conference->id,
            'venue_id' => $bytes->id,
            'name' => 'Practising Attorneys 2 Day Pass',
            'description' => '2 Day Access to the conference',
            'day_count' => 2,
            'price' => 2500,
            'cpd_hours' => 8
        ]);
        Pricing::create([
            'event_id' => $conference->id,
            'venue_id' => $bytes->id,
            'name' => 'Non practising Attorneys 1 Day Pass',
            'description' => '1 Day Access to the conference',
            'day_count' => 1,
            'price' => 1990,
            'cpd_hours' => 8
        ]);
        Pricing::create([
            'event_id' => $conference->id,
            'venue_id' => $bytes->id,
            'name' => 'Non practising Attorneys 2 Day Pass',
            'description' => '2 Day Access to the conference',
            'day_count' => 2,
            'price' => 3290,
            'cpd_hours' => 8
        ]);

        $lord = Venue::create([
            'name' => 'Lord Charles Hotel',
            'address_line_one' => 'Main Rd & Broadway Boulevard',
            'address_line_two' => 'Cape Town',
            'city' => 'Croydon',
            'province' => 'Western Cape',
            'country' => 'South Africa',
            'area_code' => '7130',
        ]);

        $lord->dates()->save(new Date([
            'date' => new Carbon('11-08-2016')
        ]));

        $conference->venues()->attach($lord);
        Pricing::create([
            'event_id' => $conference->id,
            'venue_id' => $lord->id,
            'name' => 'Practising Attorneys 1 Day Conference',
            'description' => '1 Day Access to the conference',
            'day_count' => 1,
            'price' => 1250,
            'cpd_hours' => 8
        ]);
        Pricing::create([
            'event_id' => $conference->id,
            'venue_id' => $lord->id,
            'name' => 'Non-practising Attorneys 1 Day Conference',
            'description' => '1 Day Access to the conference',
            'day_count' => 1,
            'price' => 1990,
            'cpd_hours' => 8
        ]);

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
            'date' => new Carbon('01-08-2016')
        ]));

        $web->dates()->save(new Date([
            'date' => new Carbon('02-08-2016')
        ]));

        $conference->venues()->attach($web);
        Pricing::create([
            'event_id' => $conference->id,
            'venue_id' => $web->id,
            'name' => '1 Day Online Pass',
            'description' => '1 Day Online access to the conference',
            'day_count' => 1,
            'price' => 799,
            'cpd_hours' => 8
        ]);
        Pricing::create([
            'event_id' => $conference->id,
            'venue_id' => $web->id,
            'name' => '2 Day Online Pass',
            'description' => '2 Day Online access to the conference',
            'day_count' => 2,
            'price' => 799,
            'cpd_hours' => 8
        ]);
        Model::reguard();
    }
}
