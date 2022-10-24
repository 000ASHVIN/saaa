<?php

use App\AppEvents\Date;
use App\AppEvents\Event;
use App\AppEvents\Extra;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class next_directors_duties_update extends Seeder
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
            'name' => 'Directors’ Duties Update',
            'slug' => 'directors-duties-update-2017',
            'description' => '<p>Coming soon...</p>',
            'short_description' => 'This two-hour webinar will provide you with a detailed update on the requirements, roles, rights and responsibilities of directors under the Companies Act, 2008. With so many complex rules and regulations governing directors, you simply cannot afford not to attend. If you are a director of a company or an accountant advising clients who are directors, it is imperative that you understand the full extent of directors’ duties and obligations.',
            'subscription_event' => '',
            'category' => '',
            'featured_image' => 'http://imagizer.imageshack.us/a/img923/6335/cEYhpL.jpg',
            'start_date' => new Carbon('02-02-2017'),
            'end_date' => new Carbon('02-02-2017'),
            'next_date' => new Carbon('02-02-2017'),
            'start_time' => new Carbon('02-02-2017 1400'),
            'end_time' => new Carbon('02-02-2017 16:00'),
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
            'date' => new Carbon('02-02-2017')
        ]));

        $price = Pricing::create([
            'event_id' => $seminar->id,
            'venue_id' => $web->id,
            'name' => 'Online admission',
            'description' => 'Access to the webinar / recording',
            'day_count' => 1,
            'price' => 299,
            'cpd_hours' => 2
        ]);
        $price->features()->sync([27]);

        $seminar->venues()->attach($web);

        Model::reguard();
    }
}
