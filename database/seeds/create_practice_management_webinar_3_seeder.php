<?php

use App\AppEvents\Event;
use App\AppEvents\Date;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class create_practice_management_webinar_3_seeder extends Seeder
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
            'name' => 'Monthly Practice Management Webinar',
            'slug' => 'monthly-practice-management-webinar-3',
            'description' => '<p>Coming soon...</p>',
            'short_description' => 'Each month, Gerhard will take a detailed look at a particular aspect of running a successful accountancy practice, such as staff management, marketing and cross-selling your services and will give you practical tips and guidance on steps that you can take immediately to improve your employees’ and your practice’s performance.',
            'featured_image' => 'http://imageshack.com/a/img923/6335/cEYhpL.jpg',
            'start_date' => new Carbon('17-10-2016'),
            'end_date' => new Carbon('17-10-2016'),
            'next_date' => new Carbon('17-10-2016'),
            'start_time' => new Carbon('17-10-2016 14:00'),
            'end_time' => new Carbon('17-10-2016 15:00'),
            'published_at' => Carbon::now()
        ]);

        //Plans
        $webinar->plans()->attach([2, 3, 4, 5, 6]); //all cpd subs

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
            'date' => new Carbon('17-10-2016')
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