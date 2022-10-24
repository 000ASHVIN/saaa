<?php

namespace App\Console\Commands;

use App\ActivityLog;
use App\CourseProcess;
use App\Models\Course;
use Illuminate\Console\Command;

class ManualAddLeadActivities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manual:add_lead_activities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $leads = CourseProcess::all();
        foreach($leads as $lead) {
            if(!$lead->latestActivities->count()) {
                if($lead->type == 'download_brochure' || $lead->type == 'talk_to_human') {

                    $courseData = [];
                    if($lead->course_id) {
                        $course = Course::find($lead->course_id);
                        if($course) {
                            $courseData = [
                                'course_name' => $course->title,
                                'course_id' => $course->id
                            ];
                        }
                    }

                    $ActivityLog = ActivityLog::create([
                        'user_id'=> 0,
                        'model'=> CourseProcess::class,
                        'model_id'=> $lead->id,
                        'action_by'=> 'manually',
                        'action'=> $lead->type,
                        'data'=> json_encode($courseData),
                        'request_url'=> 'courses/course_process',
                        'created_at' => $lead->created_at
                    ]);

                }
            }
        }

        $leads = CourseProcess::with('latestActivities')->get();
        foreach($leads as $lead) {
            $duplicates = CourseProcess::where('email', $lead->email)
                ->where('id', '>', $lead->id)
                ->get();

            foreach($duplicates as $duplicate) {

                $activities = $duplicate->latestActivities;
                foreach($activities as $activity) {
                    $activity->update(['model_id' => $lead->id]);
                    echo 'Activity merged: '.$duplicate->id.' to '.$lead->id;
                }
                $duplicate->delete();

            }
        }
    }
}
