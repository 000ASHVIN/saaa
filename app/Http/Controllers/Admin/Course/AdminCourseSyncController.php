<?php

namespace App\Http\Controllers\Admin\Course;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Moodle;
use App\Repositories\DatatableRepository\DatatableRepository;
use App\Subscriptions\Models\Plan;

class AdminCourseSyncController extends Controller
{
    private $datatableRepository;

    public function __construct(DatatableRepository $datatableRepository)
    {
        $this->datatableRepository = $datatableRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(date("Y-m-d", 1542133800));
        $courses = Course::all();
        // dd($synced_courses);
        $moodle = new Moodle;
        $moodle_courses = $moodle->getCourseList();
        $moodle_course_ids = array_column($moodle_courses, 'id');
        // dd($moodle_courses);
        return view('admin.courses.sync.course_list', compact('courses', 'moodle_courses', 'moodle_course_ids'));
    }

    public function syncCourse(Request $request) {
        $moodle = New Moodle();
        // dd($request->all());
        if($request->course) {
            if($request->has('is_moodle_course') && $request->is_moodle_course) {
                $moodle_course = $moodle->getSingleCourseDetails($request->course);
                // dd($moodle_course);
                if($request->type == 'create') {
                    if(isset($moodle_course['courses'][0])) {
                        $course = $this->createCourse($request, $moodle_course['courses'][0]);
                    }
                }
                
                if($request->type == 'update') {
                    if(isset($moodle_course['courses'][0])) {
                        $course = $this->updateCourse($request, $moodle_course['courses'][0]);
                    }
                }
            } else {
                $course = Course::find($request->course);
                $theme = env('APP_THEME') == 'saaa' ? 'saaa' : 'ttf';
                // dd($theme);
                if($course->moodle_course_id <= 0) {
                    //create course on moodle
                    $moodle = new Moodle;
                    $course1 = new \stdClass();
                    $course1->fullname = strval($course->title);
                    $course1->shortname = strval($theme."-".$course->slug);
                    $course1->startdate = strtotime($course->start_date);
                    $course1->enddate = strtotime($course->end_date);
                    $course1->summary = strval($course->description);
                    $course1->categoryid = '3'; 

                    $output = $moodle->courseCreate($course1);
                    $moodle_id = 0;
                    if(isset($output[0]))
                    {
                        $moodle_id = intval($output[0]['id']);
                    }

                    $course->moodle_course_id = $moodle_id;
                    $course->save();

                    alert()->Success('Course is successfully linked with moodle.', 'Success');
                }

                if($course->moodle_course_id > 0) {
                    $course1 = new \stdClass();
                    $course1->id =  (int)$course->moodle_course_id;
                    
                    $course1->fullname = strval($course->title);
                    $course1->shortname =  strval($theme."-".$course->slug);
                    $course1->startdate = strtotime($course->start_date);
                    $course1->enddate = strtotime($course->end_date);
                    $course1->summary = strval($course->description);
                    $course1->categoryid = '3'; 

                    $output = $moodle->courseUpdate($course1);

                    alert()->Success('Course is successfully synced with moodle.', 'Success');
                }
            }
        }
        // dd($course);
        return response()->json(['success']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCourse($request, $moodle_course)
    {
        $course = Course::create([
            'title' => preg_replace('/[^a-zA-Z0-9_ -]/s','',$moodle_course['fullname']),
            'description' => $moodle_course['summary'],
            'short_description' => $moodle_course['summary'],
            'max_students' => 1,
            'start_date' => date("Y-m-d", $moodle_course['startdate']),
            'end_date' => date("Y-m-d", $moodle_course['enddate']),
            'monthly_enrollment_fee' => 0,
            'recurring_monthly_fee' => 0,
            'yearly_enrollment_fee' => 0,
            'recurring_yearly_fee' => 0,
            'link' => '',
            'exclude_vat' => 0,
            'language' => $moodle_course['lang'],
            'no_of_debit_order' => 0,
            'is_publish' => 0,
            'display_in_front' => 0,
            'intro_video' => '',
            // 'course_type' => $request->course_type,
            'type_of_course' => '',
            'no_of_semesters' => '',
            'semester_price' => '',
            'order_price' => '',
            'moodle_course_id' => $moodle_course['id']
        ]);

        // $studentNumber = $course->id.'_'.date('y').'_'.date('m').'_';
        // foreach(explode(' ',preg_replace('/[^a-zA-Z0-9_ -]/s','',$request->title)) as $title){
        //     if($title != '' && ctype_alpha($title)){
        //         $studentNumber .= strtoupper($title[0]);
        //     }
        // }

        // if($request->image){
        //     $image = UploadOrReplaceImage::UploadOrReplace('courses', 'image', $course,'full');
        // } 

        // if($request->brochure){
        //     $brochure = UploadOrReplaceBrochure::UploadOrReplace('courses', 'brochure', $course,'full');
        // }

        // Sync the presenters for this event
        // $course->presenters()->sync(! $request->presenter ? [] : $request->presenter);

        // Sync the tags for this event
        // $course->categories()->sync(! $request->tags ? [] : $request->tags);

        // Create the new monthly and yearly plan and save it to the DB.
        $monthly = Plan::create([
            'name' => $course->title,
            'is_practice' => 0,
            'enable_bf' => 0,
            'last_minute' => 0,
            'interval' => 'month',
            'inactive' => 0,
            'is_custom' => 0,
            'price' => $course->recurring_monthly_fee,
            'bf_price' => 0,
            'interval_count' => 1,
            'description' => $course->title,
            'trial_period_days' => 0,
            'invoice_description' => 'Course: '.str_limit($course->reference, 20)
        ]);

        $yearly = Plan::create([
            'name' => $course->title,
            'is_practice' => 0,
            'enable_bf' => 0,
            'last_minute' => 0,
            'interval' => 'year',
            'inactive' => 0,
            'is_custom' => 0,
            'price' => $course->recurring_yearly_fee,
            'bf_price' => 0,
            'interval_count' => 1,
            'description' => $course->title,
            'trial_period_days' => 0,
            'invoice_description' => 'Course: '.str_limit($course->reference, 20)
        ]);

        $course->update([
            'monthly_plan_id' => $monthly->id,
            'yearly_plan_id' => $yearly->id,
            // 'student_number' => $studentNumber.'_1'
        ]);
        alert()->Success('Course created on our system successfully.', 'Success');
        return $course;
    }

    public function updateCourse($request, $moodle_course) {
        $course = Course::where('moodle_course_id', $moodle_course['id'])->first();
        $course->update([
            'title' => preg_replace('/[^a-zA-Z0-9_ -]/s','',$moodle_course['fullname']),
            'description' => $moodle_course['summary'],
            'short_description' => $moodle_course['summary'],
            'start_date' => date("Y-m-d", $moodle_course['startdate']),
            'end_date' => date("Y-m-d", $moodle_course['enddate']),
            'language' => $moodle_course['lang'],
        ]);
        
        $monthly_plan = Plan::find($course->monthly_plan_id);
        if($monthly_plan) {
            $monthly = $monthly_plan->update(['price' => $course->fresh()->recurring_monthly_fee]);
        }
        
        $yearly_plan = Plan::find($course->yearly_plan_id);
        if($yearly_plan) {
            $yearly = Plan::find($course->yearly_plan_id)->update(['price' => $course->fresh()->recurring_yearly_fee]);   
        }

        alert()->Success('Course updated on our system successfully.', 'Success');
        return $course;
    }

    public function getSyncedCoursesList() {
        return $this->datatableRepository->list_synced_courses();
    }
}
