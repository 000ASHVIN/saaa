<?php

use App\AppEvents\Event;
use App\AppEvents\Date;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class six_challenges_the_cloud_webinar_event_seeder extends Seeder
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
            'name' => 'Six challenges the Cloud creates for accountancy practices',
            'slug' => 'six_challenges_the_cloud_create_for_accountancy_practices',
            'description' => '<p>Coming soon...</p>',
            'short_description' => 'As technology moves more of our working relationships online, this webinar explores some of the unexpected challenges and opportunities it creates for firms. The benefits and risks to firms are well documented - with considerable experience being built up in New Zealand, Australia, Netherlands and UK for us to learn from.  What is clear is that there are challenges that arise that probably weren’t expected, and if these can be mastered early on they can provide significant profit advantages. Our expert international speaker will explore the six major challenges that have arisen, look at practical steps to overcome them and the profit payoff for firms.',
            'featured_image' => 'http://imageshack.com/a/img923/6335/cEYhpL.jpg',
            'start_date' => new Carbon('29-06-2016'),
            'end_date' => new Carbon('29-06-2016'),
            'next_date' => new Carbon('29-06-2016'),
            'start_time' => new Carbon('29-06-2016 14:00'),
            'end_time' => new Carbon('29-06-2016 16:00'),
            'is_redirect' => '0',
            'redirect_url' => '',
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
            'date' => new Carbon('29-06-2016')
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
