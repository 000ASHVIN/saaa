<?php

use App\AppEvents\Date;
use App\AppEvents\Event;
use App\AppEvents\Extra;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class practice_management_for_accountnats_2016 extends Seeder
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
            'name' => '3rd Annual Practice Management Conference for Accountants',
            'slug' => '3rd-annual-practice-management-conference-for-accountants',
            'description' => '<p>Coming Soon..</p>',
            'short_description' => 'We are living in uncertain times, within South Africa and globally.  As economic and political crises surround us, the business world is struggling and small and medium enterprises are battling with the challenges of doing more with less.  Small and medium accounting practices are feeling the trickle-down effects, as well as the threats of emerging technologies, which have the potential to render traditional book-keeping and compliance services obsolete.',
            'featured_image' => 'http://imageshack.com/a/img924/4364/65CNu8.jpg',
            'start_date' => new Carbon('01-11-2016'),
            'end_date' => new Carbon('03-11-2016'),
            'next_date' => new Carbon('01-11-2016'),
            'start_time' => new Carbon('01-11-2016 00:00'),
            'end_time' => new Carbon('02-11-2016 00:00'),
            'published_at' => Carbon::now()
        ]);

        // Create the venue for this event
        $hotel = Venue::create([
            'name' => 'Premier Hotel OR Tambo',
            'address_line_one' => '73 Gladiator St',
            'address_line_two' => '',
            'city' => 'Johannesburg',
            'province' => 'Gauteng',
            'country' => 'South Africa',
            'area_code' => '1631',
        ]);

        // Day 1
        $hotel->dates()->save(new Date([
            'date' => new Carbon('01-11-2016')
        ]));

        // Day 2
        $hotel->dates()->save(new Date([
            'date' => new Carbon('02-11-2016')
        ]));

        // Day 3
        $hotel->dates()->save(new Date([
            'date' => new Carbon('03-11-2016')
        ]));

        // Attach the venues to the conference
        $conference->venues()->attach($hotel);

        // create all the pricing options for this event
        Pricing::create([
            'event_id' => $conference->id,
            'venue_id' => $hotel->id,
            'name' => 'Professional body members / 1 Day Pass',
            'description' => 'Access to the conference',
            'day_count' => 1,
            'price' => 1850.00,
            'cpd_hours' => 8
        ]);

        Pricing::create([
            'event_id' => $conference->id,
            'venue_id' => $hotel->id,
            'name' => 'Professional body members / 2 Day Pass',
            'description' => 'Access to the conference',
            'day_count' => 2,
            'price' => 2750.00,
            'cpd_hours' => 16
        ]);

        Pricing::create([
            'event_id' => $conference->id,
            'venue_id' => $hotel->id,
            'name' => 'Professional body members / 3 Day Pass',
            'description' => 'Access to the conference',
            'day_count' => 3,
            'price' => 3990.00,
            'cpd_hours' => 24
        ]);

        Pricing::create([
            'event_id' => $conference->id,
            'venue_id' => $hotel->id,
            'name' => 'Non Members / 1 Day Pass',
            'description' => 'Access to the conference',
            'day_count' => 1,
            'price' => 2090.00,
            'cpd_hours' => 8
        ]);

        Pricing::create([
            'event_id' => $conference->id,
            'venue_id' => $hotel->id,
            'name' => 'Non Members / 2 Day Pass',
            'description' => 'Access to the conference',
            'day_count' => 2,
            'price' => 2990.00,
            'cpd_hours' => 16
        ]);

        Pricing::create([
            'event_id' => $conference->id,
            'venue_id' => $hotel->id,
            'name' => 'Non Members / 3 Day Pass',
            'description' => 'Access to the conference',
            'day_count' => 3,
            'price' => 4390.00,
            'cpd_hours' => 24
        ]);

        // Webinar Venue
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

        // Day 1 Online
        $web->dates()->save(new Date([
            'date' => new Carbon('01-11-2016')
        ]));

        // Day 2 Online
        $web->dates()->save(new Date([
            'date' => new Carbon('02-11-2016')
        ]));

        // Day 3 Online
        $web->dates()->save(new Date([
            'date' => new Carbon('03-11-2016')
        ]));

        // Attach the webinar to the conference
        $conference->venues()->attach($web);

        // Create the pricing options for the webinar
        Pricing::create([
            'event_id' => $conference->id,
            'venue_id' => $web->id,
            'name' => '1 Day online Pass',
            'description' => 'Online access to the conference',
            'day_count' => 1,
            'price' => 799.00,
            'cpd_hours' => 8
        ]);

        Pricing::create([
            'event_id' => $conference->id,
            'venue_id' => $web->id,
            'name' => '2 Days Online Pass',
            'description' => 'Online access to the conference',
            'day_count' => 2,
            'price' => 398.00,
            'cpd_hours' => 16
        ]);

        Pricing::create([
            'event_id' => $conference->id,
            'venue_id' => $web->id,
            'name' => '3 Days Online Pass',
            'description' => 'Online access to the conference',
            'day_count' => 3,
            'price' => 2397.00,
            'cpd_hours' => 24
        ]);

        Model::reguard();
    }
}
