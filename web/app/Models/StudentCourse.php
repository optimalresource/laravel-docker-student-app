<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class StudentCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'course_start_date',
        'course_completion_date'
    ];

    public function student() {
        return $this->belongsTo(Student::class); 
    }

    public function course() {
        return $this->belongsTo('App\Models\Course', 'course_id'); 
    }

    public function students() {
        return $this->hasMany(Student::class, 'id', 'student_id');
    }

    public function courses() {
        return $this->hasMany(Course::class, 'id', 'course_id');
    }
}
