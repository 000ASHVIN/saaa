<?php

use App\AppEvents\Event;
use App\AppEvents\Date;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class EthicsWebinarSeeder extends Seeder
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
            'name' => 'Why ethical principles are important for professional accountants',
            'slug' => 'why-ethical-principles-are-important-for-professional-accountants',
            'description' => '<p>Coming soon...</p>',
            'featured_image' => '/assets/frontend/images/events/2016/why-ethical-principles-are-important-for-professional-accountants.jpg',
            'start_date' => new Carbon('18-02-2016'),
            'end_date' => new Carbon('18-02-2016'),
            'next_date' => new Carbon('18-02-2016'),
            'start_time' => new Carbon('18-02-2016 14:00'),
            'end_time' => new Carbon('18-02-2016 16:00'),
            'published_at' => Carbon::now()
        ]);

        //Plans
        $webinar->plans()->attach([2, 3, 4, 5]); //all cpd subs

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
            'date' => new Carbon('18-02-2016')
        ]));
        $webinar->venues()->attach($web);
        $price = Pricing::create([
            'event_id' => $webinar->id,
            'venue_id' => $web->id,
            'name' => 'Online admission',
            'description' => 'Access to the webinar / recording',
            'day_count' => 1,
            'price' => 425,
            'cpd_hours' => 2
        ]);
        $price->plans()->attach([2, 3, 4, 5], ['discount_value' => 100]);
        Model::reguard();
    }

}
