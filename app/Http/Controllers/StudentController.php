<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Course;

class StudentController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    /**
     * Get all students.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        try {
            $students = Student::all();
            return response()->json($students);
        }catch (\Exception $e) {
            return response("A server error occured", 500);
        }
    }

    public function me() {
        try {
            $me = Student::where('user_id', '=', auth()->user()->id)->first();
            return response()->json($me);
        }catch (\Exception $e) {
            return response("A server error occured", 500);
        }
    }
    
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'str_address1' => 'required|string',
            'city' => 'required|string',
            'post_code' => 'required|string',
            'country' => 'required|string',
            'phone' => 'required|string',
            'dob' => 'required|string'
        ]);

        if ($validator->fails()){
            return response($validator->errors(), 404);
        }

        try{
            $student_check = Student::where('user_id', '=', auth()->user()->id)->exists();
            if($student_check) {
                return $this->update($request);
            }
            $student = [];
            $student['user_id'] = auth()->user()->id;
            $student['title'] = strip_tags($request->input('title'));
            $student['first_name'] = strip_tags($request->input('first_name'));
            $student['last_name'] = strip_tags($request->input('last_name'));
            $student['str_address1'] = strip_tags($request->input('str_address1'));
            $student['str_address2'] = $request->input('str_address2') !== null ? $request->input('str_address2') : '';
            $student['city'] = strip_tags($request->input('city'));
            $student['post_code'] = strip_tags($request->input('post_code'));
            $student['country'] = strip_tags($request->input('country'));
            $student['phone'] = strip_tags($request->input('phone'));
            $student['altPhone'] = $request->input('altPhone') !== null ? $request->input('altPhone') : '';
            $student['dob'] = strip_tags($request->input('dob'));

            $new_student = Student::create($student);

            return response()->json($new_student);
        }catch(\Exception $e){
            return response("A server error occurred", 500);
        }
    }

    public function update(Request $request) {
        try {
            $student = Student::where('user_id', '=', auth()->user()->id)->first();
            if($student) {
                $student->title = $request->input('title') !== null ? strip_tags($request->input('title')) : $student->title;
                $student->first_name = $request->input('first_name') !== null ? strip_tags($request->input('first_name')) : $student->first_name;
                $student->last_name = $request->input('last_name') !== null ? strip_tags($request->input('last_name')) : $student->last_name;
                $student->str_address1 = $request->input('str_address1') !== null ? strip_tags($request->input('str_address1')) : $student->str_address1;
                $student->str_address2 = $request->input('str_address2') !== null ? strip_tags($request->input('str_address2')) : $student->str_address2;
                $student->city = $request->input('city') !== null ? strip_tags($request->input('city')) : $student->city;
                $student->post_code = $request->input('post_code') !== null ? strip_tags($request->input('post_code')) : $student->post_code;
                $student->country = $request->input('country') !== null ? strip_tags($request->input('country')) : $student->country;
                $student->phone = $request->input('phone') !== null ? strip_tags($request->input('phone')) : $student->phone;
                $student->altPhone = $request->input('altPhone') !== null ? strip_tags($request->input('altPhone')) : $student->altPhone;
                $student->dob = $request->input('dob') !== null ? strip_tags($request->input('dob')) : $student->dob;
                if($student->save()){
                    return response($student);
                }
            }else {
                return response("Student detail not found", 404);
            }
        }catch (\Exception $e) {
            return response($e, 500);
        }
    }

    public function destroy() {
        try {
            $student = Student::where('user_id', '=', auth()->user()->id)->first();
            if($student){
                if ($student->delete()) {
                    return response("Student deleted successfully");
                }
            }else {
                return response("Student record not found", 404);
            }
        }catch (\Exception $e) {
            return response(['status' =>'failed', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show a student.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Student $student) {
        try {
            return response()->json($student);
        }catch (\Exception $e) {
            return response("A server error occurred", 500);
        }
    }

    // public function courseStudents($id) {
    //     try {
    //         if(is_nan($id)) {
    //             return response("Invalid course id supplied");
    //         }
    //         $id = strip_tags($id);
    //         $students = DB::select("SELECT * FROM students s 
    //                     LEFT JOIN student_courses sc ON sc.student_id = s.id
    //                     WHERE sc.course_id = 2 ORDER BY sc.id DESC");
    //         return response($students);
    //     }catch(Exception $e) {
    //         return response("A server error occured", 500);
    //     }
    // }
}
