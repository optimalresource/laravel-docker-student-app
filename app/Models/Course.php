<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_title',
        'course_description',
        'course_cost_in_dollars',
        'course_duration_in_months',
        'created_by'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User', 'created_by'); 
    }
}
