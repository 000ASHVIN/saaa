<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\Mail;

class sendUpgradeNotification extends Job implements SelfHandling
{
    private $user;
    private $note;
    private $viewFile;
    private $from;
    private $whereTo;
    private $subject;
    private $oldPlan;
    private $newPlan;

    /**
     * Create a new job instance.
     * @param $viewFile
     * @param $user
     * @param $note
     * @param $from
     * @param $whereTo
     * @param $subject
     * @param $oldPlan
     * @param $newPlan
     */
    public function __construct($viewFile, $user, $note, $from, $whereTo, $subject, $oldPlan, $newPlan)
    {
        $this->viewFile = $viewFile;
        $this->user = $user;
        $this->note = $note;
        $this->from = $from;
        $this->whereTo = $whereTo;
        $this->subject = $subject;
        $this->oldPlan = $oldPlan;
        $this->newPlan = $newPlan;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $viewFile = $this->viewFile;
        $user = $this->user;
        $note = $this->note;
        $from = $this->from;
        $emailAccounts = $this->whereTo;
        $subject = $this->subject;

        $newPlan = $this->newPlan;
        $oldPlan = $this->oldPlan;

        Mail::send($viewFile, ['user' => $user, 'note' => $note, 'oldPlan' => $oldPlan, 'newPlan' => $newPlan], function ($m) use($from, $emailAccounts, $subject){
            $m->from($from, config('app.name'));
            $m->to($emailAccounts)->subject($subject);
        });
    }
}
