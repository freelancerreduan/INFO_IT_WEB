<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    /** Admin panel category list (existing categories in DB also shown). */
    public const CATEGORIES = ['Web Development', 'Graphic Design', 'Marketing', 'Data Science', 'Development', 'Design', 'Business', 'Language'];
    public const STATUSES   = ['active' => 'Active', 'inactive' => 'Inactive'];

    protected $fillable = [
        'title', 'slug', 'category', 'short_desc', 'long_desc', 'duration',
        'classes', 'instructor', 'instructor_role', 'price', 'old_price',
        'image', 'mode', 'certificate', 'rating', 'curriculum', 'outcomes', 'status',
        'batch_no', 'batch_start', 'batch_schedule', 'batch_deadline', 'total_seats', 'seats_left', 'show_batch',
    ];

    protected $casts = [
        'curriculum' => 'array',
        'outcomes' => 'array',
        'show_batch' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Course $course) {
            if (! $course->slug) {
                $course->slug = Str::slug($course->title) . '-' . Str::lower(Str::random(5));
            }
        });
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /** Total modules / classes (course_modules table) */
    public function module()
    {
        return $this->hasOne(CourseModule::class);
    }

    public function seminars()
    {
        return $this->hasMany(Seminar::class);
    }

    public function videos()
    {
        return $this->hasMany(ClassVideo::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function studentsCount(): int
    {
        return $this->enrollments()->count();
    }

    /* ---------- Admin panel helpers ---------- */

    public function getTotalModulesAttribute(): int
    {
        return (int) ($this->module?->total_modules ?? 0);
    }

    public function getTotalClassesAttribute(): int
    {
        return (int) ($this->module?->total_classes ?? 0);
    }

    /** Price the student pays. */
    public function getSellingPriceAttribute(): float
    {
        return (float) $this->price;
    }

    /** Regular (crossed) price – old_price if set, otherwise price. */
    public function getRegularPriceAttribute(): float
    {
        return (float) ($this->old_price ?: $this->price);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }

    /* ---------- Website "আসন্ন ব্যাচ" helpers ---------- */

    /** Koto % ason puron hoyeche. */
    public function getSeatProgressAttribute(): int
    {
        $total = (int) $this->total_seats;
        if ($total <= 0) {
            return 0;
        }
        $left = min($total, max(0, (int) $this->seats_left));

        return (int) round((($total - $left) / $total) * 100);
    }

    /** Open / Almost full (4 ba tar kom asson baki thakle). */
    public function getBatchStatusAttribute(): string
    {
        if ((int) $this->seats_left <= 0 && (int) $this->total_seats > 0) {
            return 'পূর্ণ';
        }

        return $this->seat_progress >= 80 ? 'প্রায় পূর্ণ' : 'খোলা';
    }

    public function getThumbUrlAttribute(): string
    {
        if ($this->image && str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        if ($this->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }

        return 'data:image/svg+xml;utf8,' . rawurlencode(
            '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="250"><rect width="100%" height="100%" fill="#EEEBFC"/><text x="50%" y="56%" text-anchor="middle" font-family="Arial" font-size="56" font-weight="bold" fill="#4C3AE3">' . e(initials($this->title)) . '</text></svg>'
        );
    }
}
