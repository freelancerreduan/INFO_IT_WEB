<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Table: course_modules — ekta course er total modules / classes. */
class CourseModule extends Model
{
    protected $table = 'course_modules';

    protected $fillable = ['course_id', 'total_modules', 'total_classes'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
