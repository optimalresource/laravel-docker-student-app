<?php

namespace App\Http\Controllers;

use App\Models\StudentCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class StudentCourseController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        try {
            $studentCourses = StudentCourse::where('student_id', '=', auth()->user()->id)->get();
            return response()->json($studentCourses);
        }catch (\Exception $e) {
            return response("A server error occured", 500);
        }
    }

    public function courseStudents(Request $request) {
        try {
            if($request->query('course_id') === null) {
                return response("No course id supplied in query");
            }
            $id = strip_tags($request->query('course_id'));
            $students = DB::select("SELECT * FROM students s 
                        LEFT JOIN student_courses sc ON sc.student_id = s.id
                        WHERE sc.course_id = $id ORDER BY sc.id DESC");
            return response($students);
        }catch(Exception $e) {
            return response("A server error occured", 500);
        }
    }

    /**
     * Store a newly created student course in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|integer',
        ]);

        if ($validator->fails()){
            return response($validator->errors(), 404);
        }

        try{
            if(StudentCourse::where('course_id', '=', $request->input('course_id'))->exists()){
                return response("Course already subscribed", 400);
            }
            
            $studentCourse = [];
            $studentCourse['student_id'] = auth()->user()->id;
            $studentCourse['course_id'] = $request->input('course_id');
            $newStudentCourse = StudentCourse::create($studentCourse);

            return response()->json($newStudentCourse);
        }catch(\Exception $e){
            return response($e, 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentCourse  $studentCourse
     * @return \Illuminate\Http\Response
     */
    public function show(StudentCourse $studentCourse)
    {
        try{
            return response()->json($studentCourse);
        }catch(Exception $e) {
            return response("A server error occurred", 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentCourse  $studentCourse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentCourse $studentCourse)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|string',
        ]);

        try {
            if(strtolower($request->input('action')) === 'start') {
                if(is_null($studentCourse->course_completion_date)) {
                    if(!is_null($studentCourse->course_start_date)) {
                        return response("Course has been started already", 400);
                    }
                    $studentCourse->course_start_date = date('Y-m-d H:i:s', time());
                    if($studentCourse->save()) {
                        return response("Course started successfully", 200);
                    }
                }else {
                    return response("Course has been completed and cannot restart", 400);
                }
            }

            if(strtolower($request->input('action')) === 'complete') {
                if(is_null($studentCourse->course_completion_date)) {
                    $studentCourse->course_completion_date = date('Y-m-d H:i:s', time());
                    if($studentCourse->save()) {
                        return response("Course completed successfully", 200);
                    }
                }else {
                    return response("Course has been completed already", 400);
                }
            }
        }catch(\Exception $e) {
            return response("A server error occurred", 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentCourse  $studentCourse
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentCourse $studentCourse)
    {
        try {
            if(auth()->user()->id !== $studentCourse->student->id && auth()->user()->role !== "admin"){
                return response("You are unauthorized", 401);
            }
            if($course->delete()){
                return response("Student course deleted successfully");
            }
        }catch(Exception $e) {
            return response("A server error occurred", 500);
        }
    }
}
