<?php

use App\AppEvents\Event;
use App\AppEvents\Date;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class create_technical_udpate_webinar_3_seeder extends Seeder
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
            'name' => 'Monthly accounting technical update',
            'slug' => 'monthly-accounting-technical-update-3',
            'description' => '<p>Coming soon...</p>',
            'short_description' => 'These monthly webinars will give you all you need for an essential technical update in just one to two hours per month. Gerhard Stols will cover all the vital technical issues and will update you thoroughly each month with the emphasis firmly on the practical issues so that action can be taken at the right time.',
            'featured_image' => 'http://imageshack.com/a/img923/6335/cEYhpL.jpg',
            'start_date' => new Carbon('13-10-2016'),
            'end_date' => new Carbon('13-10-2016'),
            'next_date' => new Carbon('13-10-2016'),
            'start_time' => new Carbon('13-10-2016 14:00'),
            'end_time' => new Carbon('13-10-2016 15:00'),
            'subscription_event' => '0',
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
            'date' => new Carbon('13-10-2016')
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
