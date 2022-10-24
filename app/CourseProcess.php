<?php

namespace App;

use App\Users\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use App\LeadStatus;
use App\Rep;
use Carbon\Carbon;

class CourseProcess extends Model
{
    protected $table = 'course_process';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'course_id',
        'user_id',
        'mobile',
        'type',
        'owner_id',
        'is_converted'
    ];

     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class)->withTrashed();
    }

    public function leadOwner() {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function leadStatus() {
        return $this->belongsTo(LeadStatus::class, 'lead_status_id');
    }

    public function latestActivities() {
        return $this->hasMany(ActivityLog::class, 'model_id')
            ->where('model', CourseProcess::class)
            ->latest();
    }

    public function addActivity($action, $data) {
        $ActivityLog = ActivityLog::create([
            'user_id'=> 0,
            'model'=> CourseProcess::class,
            'model_id'=> $this->attributes['id'],
            'action_by'=> 'manually',
            'action'=> $action,
            'data'=> json_encode($data),
            'request_url'=> request()->path()
        ]);
        $this->touch();
    }

    public static function createOrUpdate($data) {

        $course_process = self::where('email', trim($data['email']))
            ->first();
        if(!$course_process) {

            // Assign by round robin
            $rep = Rep::nextAvailable();
            $rep->update(['emailedLast' => Carbon::now()]);
            $rep = $rep->user;
            
            $data['owner_id'] = $rep->id;
            $course_process = new CourseProcess;
            $course_process = $course_process->create($data);
        }

        return $course_process;

    }

    public function getSourceAttribute() {
        return ucwords(str_replace('_',' ', $this->type));
    }

    public function converted() {
        $this->is_converted = true;
        $this->save();
    }

    public static function convertLeadFromInvoice($invoice) {
        if($invoice->user && $invoice->user->email) {
            $lead = CourseProcess::where('email', $invoice->user->email)->first();
            if($lead) {
                $lead->is_converted=1;
                $lead->save();
            }
        }
    }

}
 
?>