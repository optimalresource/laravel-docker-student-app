<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentCourseController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1/auth', 'middleware' => ['api', 'cors']], function () use ($router) {
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);
    $router->post('signup', [AuthController::class,'signup']);
});

Route::group(['prefix' => 'v1/students', 'middleware' => ['api', 'cors']], function () use ($router) {
    Route::get('/', [StudentController::class,'me']);
    Route::get('all', [StudentController::class,'index']);
    Route::get('{student}', [StudentController::class,'show']);
    Route::post('/', [StudentController::class,'store']);
    Route::put('/', [StudentController::class,'update']);
    Route::delete('/', [StudentController::class,'destroy']);
    // Route::get('/course/{id}', [StudentController::class,'courseStudents']);
});

Route::group(['prefix' => 'v1/courses', 'middleware' => ['api', 'cors']], function () use ($router) {
    Route::get('/', [CourseController::class,'myCourses']);
    Route::get('all', [CourseController::class,'index']);
    Route::get('{course}', [CourseController::class,'show']);
    Route::post('/', [CourseController::class,'store']);
    Route::put('{course}', [CourseController::class,'update']);
    Route::delete('{course}', [CourseController::class,'destroy']);
});

Route::group(['prefix' => 'v1/student_courses', 'middleware' => ['api', 'cors']], function () use ($router) {
    Route::get('/me', [StudentCourseController::class,'index']);
    Route::get('/', [StudentCourseController::class,'courseStudents']);
    Route::get('{studentCourse}', [StudentCourseController::class,'show']);
    Route::post('/', [StudentCourseController::class,'store']);
    Route::put('{studentCourse}/start', [StudentCourseController::class,'start']);
    Route::put('{studentCourse}/complete', [StudentCourseController::class,'complete']);
    Route::delete('{studentCourse}', [StudentCourseController::class,'destroy']);
});

