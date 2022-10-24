<?php

use App\AppEvents\Event;
use App\AppEvents\Date;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class webinar_on_practice_management_event_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $webinar = Event::create([
            'type' => 'webinar',
            'name' => 'Essential insights into growing your practice in 2016',
            'slug' => 'essentials_insights_into_growing_your_practice',
            'description' => '<p>Coming soon...</p>',
            'short_description' => 'Join UK Practice Management expert Mark Lloydbottom for a free one-hour webinar on all aspects of running a successful practice in today’s highly competitive and fast-moving environment.  This webinar is a preview of what you can expect at Mark’s three seminars which he is presenting in person at the end of July',
            'featured_image' => 'http://imageshack.com/a/img923/6335/cEYhpL.jpg',
            'start_date' => new Carbon('13-06-2016'),
            'end_date' => new Carbon('13-06-2016'),
            'next_date' => new Carbon('13-06-2016'),
            'start_time' => new Carbon('13-06-2016 14:00'),
            'end_time' => new Carbon('13-06-2016 16:00'),
            'subscription_event' => '0',
            'published_at' => Carbon::now()
        ]);

        //Plans
        //All Member types on our website,

        $webinar->plans()->attach([2, 3, 4, 5, 6]);

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
            'date' => new Carbon('13-06-2016')
        ]));

        $webinar->venues()->attach($web);
        $price = Pricing::create([
            'event_id' => $webinar->id,
            'venue_id' => $web->id,
            'name' => 'Online admission',
            'description' => 'Access to the webinar / recording',
            'day_count' => 1,
            'price' => 425,
            'cpd_hours' => 1
        ]);

        $price->plans()->attach([2, 3, 4, 5, 6], ['discount_value' => 100]);
        Model::reguard();
    }
}
