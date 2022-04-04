<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        try {
            $courses = Course::all();
            return response()->json($courses);
        }catch (\Exception $e) {
            return response("A server error occured", 500);
        }
    }
    
    public function myCourses(Request $request) {
        try {
            $courses = Course::where('created_by', '=', auth()->user()->id)->get();
            return response()->json($courses);
        }catch (\Exception $e) {
            return response("A server error occured", 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'course_title' => 'required|string',
            'course_description' => 'required|string',
            'course_cost_in_dollars' => 'required|string',
            'course_duration_in_months' => 'required|integer'
        ]);

        if ($validator->fails()){
            return response($validator->errors(), 404);
        }

        try{
            $student_check = Course::where('course_title', '=', strip_tags($request->input('course_title')))->exists();
            if($student_check) {
                return response("Course already exist", 400);
            }
            $course = [];
            $course['created_by'] = auth()->user()->id;
            $course['course_title'] = strip_tags($request->input('course_title'));
            $course['course_description'] = strip_tags($request->input('course_description'));
            $course['course_cost_in_dollars'] = strip_tags($request->input('course_cost_in_dollars'));
            $course['course_duration_in_months'] = strip_tags($request->input('course_duration_in_months'));

            $new_course = Course::create($course);

            return response()->json($new_course);
        }catch(\Exception $e){
            return response("A server error occurred", 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        try {
            return response($course);
        }catch(\Exception $e) {
            return response("A server error occurred", 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course) {
        try {            
            if($request->input('course_duration_in_months') !== null) {
                if(is_nan($request->input('course_duration_in_months'))) return response("Invalid course duration supplied", 400);
            }

            $course->course_title = $request->input('course_title') !== null ? strip_tags($request->input('course_title')) : $course->course_title;
            $course->course_description = $request->input('course_description') !== null ? strip_tags($request->input('course_description')) : $course->course_description;
            $course->course_cost_in_dollars = $request->input('course_cost_in_dollars') !== null ? strip_tags($request->input('course_cost_in_dollars')) : $course->course_cost_in_dollars;
            $course->course_duration_in_months = $request->input('course_duration_in_months') !== null ? strip_tags($request->input('course_duration_in_months')) : $course->course_duration_in_months;

            if($course->save()){
                return response($course);
            }
        }catch (\Exception $e) {
            return response("A server error occurred", 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        try {
            if(auth()->user()->id !== $course->created_by && auth()->user()->role !== "admin"){
                return response("You are unauthorized", 401);
            }
            if($course->delete()){
                return response("course deleted successfully");
            }
        }catch(Exception $e) {
            return response("A server error occurred", 500);
        }
    }
}
