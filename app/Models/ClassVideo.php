<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Homepage er "ক্লাস ভিডিও" section (prti page e 9 ta video).
 */
class ClassVideo extends Model
{
    public const SOURCES  = ['upload' => 'Upload video file', 'youtube' => 'YouTube link'];
    public const STATUSES = ['active' => 'Active', 'inactive' => 'Inactive'];

    protected $fillable = [
        'title', 'course_id', 'course_label', 'batch', 'class_date', 'date_label',
        'duration', 'source', 'video_path', 'youtube_id', 'thumbnail', 'status', 'sort_order',
    ];

    protected $casts = ['class_date' => 'date'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }

    public function getCourseTextAttribute(): string
    {
        return $this->course?->title ?: (string) $this->course_label;
    }

    public function getDateTextAttribute(): string
    {
        return $this->date_label ?: ($this->class_date ? bn_date($this->class_date) : '');
    }

    /** Uploaded/linked video file er url. */
    public function getVideoUrlAttribute(): ?string
    {
        if (! $this->video_path) {
            return null;
        }
        if (str_starts_with($this->video_path, 'http')) {
            return $this->video_path;
        }
        if (Storage::disk('public')->exists($this->video_path)) {
            return asset('storage/' . $this->video_path);
        }

        return asset($this->video_path);
    }

    public function getThumbUrlAttribute(): ?string
    {
        if (! $this->thumbnail) {
            return null;
        }
        if (str_starts_with($this->thumbnail, 'http')) {
            return $this->thumbnail;
        }
        if (Storage::disk('public')->exists($this->thumbnail)) {
            return asset('storage/' . $this->thumbnail);
        }

        return asset($this->thumbnail);
    }

    /** Frontend JS (videos grid) er jonno. */
    public function toCardArray(): array
    {
        return [
            'title'    => $this->title,
            'course'   => $this->course_text,
            'batch'    => $this->batch,
            'date'     => $this->date_text,
            'duration' => $this->duration,
            'youtube'  => $this->source === 'youtube' ? $this->youtube_id : null,
            'src'      => $this->source === 'youtube' ? null : $this->video_url,
            'poster'   => $this->thumb_url,
        ];
    }
}
