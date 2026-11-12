<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    public const STATUSES = ['new' => 'New', 'contacted' => 'Contacted', 'enrolled' => 'Enrolled', 'rejected' => 'Rejected'];

    protected $fillable = ['course_id', 'name', 'email', 'phone', 'message', 'status'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
