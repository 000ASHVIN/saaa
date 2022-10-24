<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Moodle;
use Illuminate\Http\Request;
use App\Users\User;
class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $courses = auth()->user()->courses()->paginate(10);
        return view('dashboard.courses.index', compact('courses'));
    }

    public function show($reference)
    {
        $course = Course::where('reference', $reference)->first();
        $moodle = new Moodle();
        $user = auth()->user();
        
        $courseGrades = null;
        $response = $moodle->userGradesReport($user->moodle_user_id, $course->moodle_course_id);

        if(isset($response['usergrades'])) {
            $courseGrades = $response['usergrades'][0];
        }
        
        return view('dashboard.courses.show', compact('course', 'courseGrades'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
