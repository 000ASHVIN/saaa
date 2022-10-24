<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderOverdueReminder extends Job implements SelfHandling, ShouldQueue
{
    private $day;
    private $order;

    /**
     * Create a new job instance.
     *
     * @param $day
     * @param $order
     */
    public function __construct($day, $order)
    {
        $this->day = $day;
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $day = $this->day;
        $order = $this->order;

        // Send E-mail
        if(sendMailOrNot($order->user, 'orders.unpaid.'.$day)) {
        Mail::send('emails.orders.unpaid.'.$day, ['order' => $order], function ($m) use ($order) {
            $m->from(config('app.email'), config('app.name'));
            $m->to($order->user->email)->subject('You have an unpaid purchase order');
        });
        }
    }
}
