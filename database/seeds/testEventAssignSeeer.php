<?php

use App\AppEvents\Event;
use App\AppEvents\Date;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class testEventAssignSeeer extends Seeder
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
            'name' => 'Test assign',
            'slug' => 'test-assign',
            'description' => '<p>Coming soon...</p>',
            'short_description' => 'Test assign',
            'featured_image' => 'http://imageshack.com/a/img923/6335/cEYhpL.jpg',
            'start_date' => new Carbon('14-09-2016'),
            'end_date' => new Carbon('14-09-2016'),
            'next_date' => new Carbon('14-09-2016'),
            'start_time' => new Carbon('14-09-2016 10:00'),
            'end_time' => new Carbon('14-09-2016 12:00'),
            'subscription_event' => '1',
            'category' => 'all_cpd_subs',
            'published_at' => Carbon::now()
        ]);

        //Plans
        $webinar->plans()->attach([2, 3, 4, 5, 6]); // Everyone

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
            'date' => new Carbon('14-09-2016')
        ]));
        $webinar->venues()->attach($web);
        $price = Pricing::create([
            'event_id' => $webinar->id,
            'venue_id' => $web->id,
            'name' => 'Online admission',
            'description' => 'Access to the webinar / recording',
            'day_count' => 1,
            'price' => 0,
            'cpd_hours' => 2
        ]);
        $price->plans()->attach([2, 3, 4, 5, 6], ['discount_value' => 100]);
        Model::reguard();
    }
}
