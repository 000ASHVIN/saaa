<?php

namespace App\Http\Controllers\Admin;

use App\Blog\Category;
use App\Console\Commands\backdateUserNotes;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Presenters\Presenter;
use App\Repositories\DatatableRepository\DatatableRepository;
use App\Subscriptions\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\UploadOrReplaceImage;
use App\UploadOrReplaceBrochure;
use App\Moodle;

class CourseController extends Controller
{
    private $datatableRepository;

    public function __construct(DatatableRepository $datatableRepository)
    {
        $this->datatableRepository = $datatableRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.courses.index');
    }

    public function list_courses()
    {
        return $this->datatableRepository->list_courses();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $presenters = Presenter::all()->pluck('name', 'id');
        $tags = Category::all()->pluck('title', 'id');
        $course_types = Course::getCourseTypes();
        return view('admin.courses.create', compact('presenters', 'tags', 'course_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     * @throws \Exception
     */
    public function store(Request $request)
    {   
        $this->validate($request, [
            'title' => 'required',
            'max_students' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'monthly_enrollment_fee' => 'required',
            'recurring_monthly_fee' => 'required',
            'yearly_enrollment_fee' => 'required',
            'recurring_yearly_fee' => 'required',
            'link' => 'required',
            'description' => 'required',
            'short_description' => 'required',
            'is_publish' => 'required',
        ]);

        \DB::transaction(function () use($request){
            $course = Course::create([
                'title' => preg_replace('/[^a-zA-Z0-9_ -]/s','',$request->title),
                'description' => $request->description,
                'short_description' => $request->short_description,
                'max_students' => $request->max_students,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'monthly_enrollment_fee' => ($request->monthly_enrollment_fee * 100),
                'recurring_monthly_fee' => ($request->recurring_monthly_fee * 100),
                'yearly_enrollment_fee' => ($request->yearly_enrollment_fee * 100),
                'recurring_yearly_fee' => ($request->recurring_yearly_fee * 100),
                'link' => $request->link,
                'exclude_vat' => $request->exclude_vat,
                'language' => $request->language,
                'no_of_debit_order' => $request->no_of_debit_order,
                'is_publish' => $request->is_publish,
                'display_in_front' => $request->display_in_front,
                'intro_video' => $request->intro_video,
                'course_type' => $request->course_type,
                'type_of_course' => $request->type_of_course,
                'no_of_semesters' => $request->type_of_course == 'semester' ? $request->no_of_semesters : '',
                'semester_price' => $request->type_of_course == 'semester' ? $request->semester_price : '',
                'order_price' => $request->type_of_course == 'semester' ? $request->order_price : '',
            ]);

            $studentNumber = $course->id.'_'.date('y').'_'.date('m').'_';
            foreach(explode(' ',preg_replace('/[^a-zA-Z0-9_ -]/s','',$request->title)) as $title){
                if($title != '' && ctype_alpha($title)){
                    $studentNumber .= strtoupper($title[0]);
                }
            }

            if($request->image){
                $image = UploadOrReplaceImage::UploadOrReplace('courses', 'image', $course,'full');
            } 

            if($request->brochure){
                $brochure = UploadOrReplaceBrochure::UploadOrReplace('courses', 'brochure', $course, 'full');
            }

            // Sync the presenters for this event
            $course->presenters()->sync(! $request->presenter ? [] : $request->presenter);
           
            $course1 = new \stdClass();
            $theme = env('APP_THEME') == 'saaa' ? 'saaa' : 'ttf';
            $course1->fullname = $request->title;
            $course1->shortname = strval($theme."-".$course->slug);
            $course1->startdate = strtotime($request->start_date);
            $course1->enddate = strtotime($request->end_date);
            $course1->summary = $request->description;
            $course1->categoryid = '3'; 
            $moodle = New Moodle();
            $output = $moodle->courseCreate($course1);
            $moodle_id = 0;
            if(isset($output[0]))
            {
                $moodle_id = intval($output[0]['id']);
            }
            // Sync the tags for this event
            $course->categories()->sync(! $request->tags ? [] : $request->tags);

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
                'student_number' => $studentNumber.'_1',
                'moodle_course_id'=> $moodle_id
            ]);
        });

        alert()->success('Your course has been created!', 'Success');
        return redirect()->route('admin.courses.index');
    }
    public function create_moodle_course($courseId) {
        $course = Course::find($courseId);
        $theme = env('APP_THEME') == 'saaa' ? 'saaa' : 'ttf';
        $course1 = new \stdClass();
        
        $course1->fullname = strval($course->title);
        $course1->shortname = strval($theme."-".$course->slug);
        $course1->startdate = strtotime($course->start_date);
        $course1->enddate = strtotime($course->end_date);
        $course1->summary = strval($course->description);
        $course1->categoryid = '3'; 
        $moodle = New Moodle();
        $output = $moodle->courseCreate($course1);
        $moodle_id = 0;

        if(isset($output[0]))
        {
            $moodle_id = intval($output[0]['id']);
        }

        $course->moodle_course_id = $moodle_id;
        $course->save();

        if($moodle_id == 0) {
            alert()->error('There is some problem, Please try again!', 'Error');
        } else {
            alert()->success('Your course has been added with Moodle!', 'Success');
        }
        
        return redirect()->route('admin.courses.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $course = Course::find($id);
        $presenters = Presenter::all()->pluck('name', 'id');
        $tags = Category::all()->pluck('title', 'id');
        $course_types = Course::getCourseTypes();
        return view('admin.courses.show', compact('course', 'presenters', 'tags', 'course_types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $course = Course::find($id);
        $data = $request->except('_token', 'tags', 'presenter', 'image', 'brochure', 'remove_image', 'remove_brochure', 'semester_price','order_price');

        $data['semester_price'] = ($request->semester_price);
        $data['order_price'] = ($request->order_price);
        if($data['type_of_course'] == 'short') {
            $data['no_of_semesters'] = 0;
            $data['semester_price'] = '';
            $data['order_price'] = '';
        }
        
        if($request->remove_image) {
            $data['image'] = '';
        }
        if($request->remove_brochure) {
            $data['brochure'] = '';
        }

        $course->update($data);

        $course->update([
            'monthly_enrollment_fee' => ($request->monthly_enrollment_fee * 100),
            'recurring_monthly_fee' => ($request->recurring_monthly_fee * 100),
            'yearly_enrollment_fee' => ($request->yearly_enrollment_fee * 100),
            'recurring_yearly_fee' => ($request->recurring_yearly_fee * 100),
        ]);

        // update if course is sync with moodle
        if($course->moodle_course_id != 0 && $course->moodle_course_id != '' && $course->moodle_course_id != null) {
            $course1 = new \stdClass();
            $course1->id =  (int)$course->moodle_course_id;
            $theme = env('APP_THEME') == 'saaa' ? 'saaa' : 'ttf';
            $course1->fullname = strval($course->title);
            $course1->shortname =  strval($theme."-".$course->slug);
            $course1->startdate = strtotime($course->start_date);
            $course1->enddate = strtotime($course->end_date);
            $course1->summary = strval($course->description);
            $course1->categoryid = '3'; 
            $moodle = New Moodle();
            $output = $moodle->courseUpdate($course1);
        }

        // Sync the presenters for this event
        $course->presenters()->sync(! $request->presenter ? [] : $request->presenter);

        // Sync the tags for this event
        $course->categories()->sync(! $request->tags ? [] : $request->tags);

        // Update the pricing..
        $monthly = Plan::find($course->monthly_plan_id)->update(['price' => $course->fresh()->recurring_monthly_fee]);
        $yearly = Plan::find($course->yearly_plan_id)->update(['price' => $course->fresh()->recurring_yearly_fee]);

        // Image upload
        if($request->image){
            $image = UploadOrReplaceImage::UploadOrReplace('courses', 'image', $course,'full');
        }

        // Brochure Upload
        if($request->brochure){
            $brochure = UploadOrReplaceBrochure::UploadOrReplace('courses', 'brochure', $course,'full');
        }

        alert()->success('Your course has been udpated successfully', 'Success!');
        return back();
    }

    public function list_students($courseId)
    {
        $course = Course::find($courseId);
        return $this->datatableRepository->list_students($course);
    }

    public function list_invoices($courseId)
    {
        $course = Course::find($courseId);
        return $this->datatableRepository->list_course_invoices($course);
    }
    public function duplicate($id){
        $CourseGet = Course::find($id);
        
        $course = Course::create([
            'title' => preg_replace('/[^a-zA-Z0-9_ -]/s','',$CourseGet->title),
            'description' => $CourseGet->description,
            'short_description' => $CourseGet->short_description,
            'max_students' => $CourseGet->max_students,
            'start_date' => $CourseGet->start_date,
            'end_date' => $CourseGet->end_date,
            'monthly_enrollment_fee' => ($CourseGet->monthly_enrollment_fee * 100),
            'recurring_monthly_fee' => ($CourseGet->recurring_monthly_fee * 100),
            'yearly_enrollment_fee' => ($CourseGet->yearly_enrollment_fee * 100),
            'recurring_yearly_fee' => ($CourseGet->recurring_yearly_fee * 100),
            'link' => $CourseGet->link,
            'exclude_vat' => $CourseGet->exclude_vat,
            'language' => $CourseGet->language,
            'no_of_debit_order' => $CourseGet->no_of_debit_order,
            'is_publish' => $CourseGet->is_publish,
            'display_in_front' => $CourseGet->display_in_front,
        ]);

        // Sync the presenters for this event
        $course->presenters()->sync(! $CourseGet->presenter ? [] : $CourseGet->presenter);

        // Sync the tags for this event
        $course->categories()->sync(! $CourseGet->tags ? [] : $CourseGet->tags);

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
            'yearly_plan_id' => $yearly->id
        ]);

        alert()->success('Your duplicate course has been created!', 'Success');
        return redirect()->route('admin.courses.show',$course->id);
    }
}
