<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Institute costing (rent, salary, ads...) — Dashboard/Reports "Costing".
 * Table: admin_costings
 */
class Costing extends Model
{
    protected $table = 'admin_costings';

    protected $fillable = ['title', 'course_id', 'amount', 'spent_at'];

    protected $casts = ['spent_at' => 'date'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
