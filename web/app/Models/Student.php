<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'first_name',
        'last_name',
        'str_address1',
        'str_address2',
        'city',
        'post_code',
        'country',
        'phone',
        'altPhone',
        'dob'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id'); 
    }

    public function studentCourses() {
        return $this->hasMany(StudentCourse::class, 'id', 'student_id');
    }
}
