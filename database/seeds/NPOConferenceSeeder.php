<?php

use App\AppEvents\Date;
use App\AppEvents\Event;
use App\AppEvents\Extra;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class NPOConferenceSeeder extends Seeder
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
            'name' => '3rd Annual Not-for-Profit Industry Conference',
            'slug' => '3rd-annual-not-for-profit-industry-conference',
            'description' => '<p>Coming soon...</p>',
            'featured_image' => '/assets/frontend/images/events/2016/3rd-annual-not-for-profit-industry-conference.jpg',
            'start_date' => new Carbon('24-05-2016'),
            'end_date' => new Carbon('25-05-2016'),
            'next_date' => new Carbon('24-05-2016'),
            'start_time' => new Carbon('24-05-2016 09:00'),
            'end_time' => new Carbon('25-05-2016 17:00'),
            'published_at' => Carbon::now()
        ]);

        $bytes = Venue::create([
            'name' => 'Bytes Conference Centre',
            'address_line_one' => '241 Third Road',
            'address_line_two' => 'Halfway Gardens',
            'city' => 'Midrand',
            'province' => 'Gauteng',
            'country' => 'South Africa',
            'area_code' => '1685',
        ]);
        $bytes->dates()->save(new Date([
            'date' => new Carbon('24-05-2016')
        ]));
        $bytes->dates()->save(new Date([
            'date' => new Carbon('25-05-2016')
        ]));
        $conference->venues()->attach($bytes);
        Pricing::create([
            'event_id' => $conference->id,
            'venue_id' => $bytes->id,
            'name' => '1 Day Pass',
            'description' => 'Access to the conference',
            'day_count' => 1,
            'price' => 2199.00,
            'cpd_hours' => 8
        ]);
        Pricing::create([
            'event_id' => $conference->id,
            'venue_id' => $bytes->id,
            'name' => '2 Day Pass',
            'description' => 'Access to the conference',
            'day_count' => 2,
            'price' => 3299.00,
            'cpd_hours' => 16
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
            'date' => new Carbon('24-05-2016')
        ]));
        $web->dates()->save(new Date([
            'date' => new Carbon('25-05-2016')
        ]));
        $conference->venues()->attach($web);
        Pricing::create([
            'event_id' => $conference->id,
            'venue_id' => $web->id,
            'name' => '1 Day Online Pass',
            'description' => 'Online access to the conference',
            'day_count' => 1,
            'price' => 799.00,
            'cpd_hours' => 8
        ]);
        Pricing::create([
            'event_id' => $conference->id,
            'venue_id' => $web->id,
            'name' => '2 Day Online Pass',
            'description' => 'Online access to the conference',
            'day_count' => 2,
            'price' => 1598.00,
            'cpd_hours' => 16
        ]);

        //Extras
        $printedNotes = Extra::create([
            'name' => 'Printed notes',
            'price' => 100.00
        ]);

        $conference->extras()->save($printedNotes);
        Model::reguard();
    }
}
