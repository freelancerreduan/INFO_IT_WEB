<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Homepage er "আপকামিং সেমিনার সময়সূচী" table.
 */
class Seminar extends Model
{
    public const VENUES   = ['online' => 'Online', 'offline' => 'Offline'];
    public const STATUSES = ['active' => 'Active', 'inactive' => 'Inactive'];

    protected $fillable = [
        'title', 'course_id', 'seminar_date', 'date_label', 'time_label',
        'venue', 'registration_link', 'live_link', 'is_featured', 'status', 'sort_order',
    ];

    protected $casts = [
        'seminar_date' => 'date',
        'is_featured'  => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }

    /** Website e dekhano tarikh — date_label na thakle bangla tarikh banay. */
    public function getDateTextAttribute(): string
    {
        return $this->date_label ?: bn_date($this->seminar_date);
    }

    public function getVenueLabelAttribute(): string
    {
        return $this->venue === 'offline' ? 'অফলাইন' : 'অনলাইন';
    }

    /** Registration button er link. */
    public function getRegisterUrlAttribute(): string
    {
        if ($this->registration_link) {
            return $this->registration_link;
        }

        return $this->course
            ? route('apply', ['course' => $this->course->slug])
            : route('apply');
    }
}
