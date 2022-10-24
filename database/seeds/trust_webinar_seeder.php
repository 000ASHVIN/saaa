<?php

use App\AppEvents\Event;
use App\AppEvents\Date;
use App\AppEvents\Pricing;<h4>Overview</h4>
<p>
There is a common misconception at times that trusts are not regulated, and that trusts are going to become a rare
    animal going forward. As an accountant, your work often starts where the attorney's work ends. This session will
    cover and debate the following key concepts:
</p>

<ul>
    <li>Why would I still want to have a trust?</li>
    <li>The importance of a well drafted trust deed and the implications if this is not done with care</li>
    <li>The alter ego challenge</li>
    <li>Regulation of trusts through statute and case law</li>
    <li>The rights, responsibilities, liabilities and obligations of trustees</li>
    <li>The rights of beneficiaries</li>
    <li>And much more through lively debate!</li>
</ul>

<p>
    During this two-hour webinar, the presenter’s aim is to give insight into how the accounting profession views
    matters and the challenges we face that can be resolved through putting legal and accounting minds together to find
    solutions.
</p>
<hr>

<h4>Presenter</h4>
<div class="col-md-3">
    <img class="thumbnail" src="http://imageshack.com/a/img922/4228/I5DIFE.png" width="100%"
         alt="">
</div>

<p><strong>Caryn Maitland</strong>, Owner, <strong>Maitland & Associates</strong></p>
<p>
    Caryn Maitland graduated in 1998 from the University of Natal, Pietermaritzburg (now UKZN), with a BCom Honours degree in Accounting and completed her articles with KPMG in 2001 and qualified as a Chartered Accountant (SA).  She has been a member of IRBA as a registered auditor and accountant since 2002 and is also a registered member of SAICA.
</p>

<p>
    Caryn has lectured in Auditing, second and third year subjects, for Varsity College from 1999 to 2001. In 2002, she was appointed as senior lecturer at UKZN and coordinated the Financial Accounting 300 course until June 2006. In addition, she has lectured in Advanced Financial Accounting since June 2006 until December 2011.
</p>

<p>
    Caryn has conducted independent workshops and seminars for professional accountants since 2006 on various topics, and has consulted on a number of technical issues.  Since January 2011, she has focused on her own business as technical freelance consultant and trainer.
</p>

<p>
    Caryn also held the position Editor-in-Chief of an online continual professional development (CPD) website for accounting professional, eCPD Accountancy, having resigned from this post in September 2013.
</p>

<p>
    Caryn has presented on the national tour for the annual Legislation Update with ProBeta Training (Pty) Ltd in 2014 and 2015, as well as on the FASSET national tour for the Tax Administration Act in 2014.  She has also been involved with the SAICA SMP Technical tour (2014 to present).
</p>

<hr>
<h4>CPD:</h4>
<p>Attending the webinar will accrue 2 hours’ CPD for members of a relevant professional body. </p>
use App\AppEvents\Venue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class trust_webinar_seeder extends Seeder
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
            'name' => 'The Law of Trusts',
            'slug' => 'the-law-of-trusts',
            'description' => '<p>Coming soon...</p>',
            'short_description' => 'There is a common misconception at times that trusts are not regulated, and that trusts are going to become a rare animal going forward. As an accountant, your work often starts where the attorneys work ends. This session will cover and debate the following key concepts:',
            'featured_image' => 'http://imageshack.com/a/img923/6335/cEYhpL.jpg',
            'start_date' => new Carbon('23-11-2016'),
            'end_date' => new Carbon('23-11-2016'),
            'next_date' => new Carbon('23-11-2016'),
            'start_time' => new Carbon('23-11-2016 14:00'),
            'end_time' => new Carbon('23-11-2016 16:00'),
            'subscription_event' => '1',
            'category' => 'all_cpd_subs',
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
            'date' => new Carbon('23-11-2016')
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
