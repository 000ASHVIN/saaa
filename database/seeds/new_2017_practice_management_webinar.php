<?php

use App\AppEvents\Date;
use App\AppEvents\Event;
use App\AppEvents\Extra;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class new_2017_practice_management_webinar extends Seeder
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
            'type' => 'webinar',
            'name' => 'Practice Management Webinar 1',
            'slug' => 'practice-management-webinar-2017-1',
            'description' => '<p>Coming soon...</p>',
            'short_description' => 'Each month, Gerhard will take a detailed look at a particular aspect of running a successful accountancy practice, such as staff management, marketing and cross-selling your services and will give you practical tips and guidance on steps that you can take immediately to improve your employees’ and your practice’s performance. ',
            'subscription_event' => '',
            'category' => '',
            'is_redirect' => false,
            'redirect_url' => '',
            'featured_image' => 'http://imagizer.imageshack.us/a/img923/6335/cEYhpL.jpg',
            'start_date' => new Carbon('23-02-2017'),
            'end_date' => new Carbon('23-02-2017'),
            'next_date' => new Carbon('23-02-2017'),
            'start_time' => new Carbon('23-02-2017 14:00'),
            'end_time' => new Carbon('23-02-2017 15:00'),
            'published_at' => Carbon::now()
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
            'date' => new Carbon('23-02-2017')
        ]));
        $seminar->venues()->attach($web);
        $price = Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $web->id,
            'name' => 'Online admission',
            'description' => 'Access to the webinar / recording',
            'day_count' => 1,
            'price' => 0,
            'cpd_hours' => 1
        ]);
        $price->features()->sync([20]);

        Model::reguard();
    }
}
