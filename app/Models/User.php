<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const STUDENT_STATUSES = ['active' => 'Active', 'inactive' => 'Inactive'];

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'parent_phone', 'nid', 'status', 'address', 'photo',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /** Notun student toiri holei (admin / register / apply) Student ID auto generate hoy. */
    protected static function booted(): void
    {
        static::creating(function (User $u) {
            if (($u->role ?? 'user') === 'user' && ! $u->student_id && static::ensureStudentIdColumn()) {
                $u->student_id = static::formatStudentId(static::nextStudentNumber());
            }
        });
    }

    /** INFO-0001, INFO-0002 ... (kromik number) */
    public static function formatStudentId(int $n): string
    {
        return 'INFO-' . str_pad((string) $n, 4, '0', STR_PAD_LEFT);
    }

    public static function nextStudentNumber(): int
    {
        $max = static::whereNotNull('student_id')->pluck('student_id')
            ->map(fn ($id) => (int) (preg_match('/(\d+)$/', $id, $m) ? $m[1] : 0))->max();

        return (int) $max + 1;
    }

    /**
     * users table e student_id column na thakle nije theke add kore
     * (migrate / SQL chalano na holeo kaj korbe). Column thakle true.
     */
    public static function ensureStudentIdColumn(): bool
    {
        static $ok = null;
        if ($ok !== null) {
            return $ok;
        }
        try {
            if (! Schema::hasColumn('users', 'student_id')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('student_id', 30)->nullable()->unique();
                });
            }

            return $ok = true;
        } catch (\Throwable $e) {
            report($e);

            return $ok = false;
        }
    }

    /**
     * Jei student der ID nei (ba purono INFO-2026-0001 format), tader INFO-0001 format e ID dey.
     * Student List / Add Payment / Transactions page khullei chole — kom query, kaj na thakle kichui kore na.
     */
    public static function ensureStudentIds(): void
    {
        if (! static::ensureStudentIdColumn()) {
            return;
        }

        // purono format (INFO-2026-0001) -> INFO-0001 (number same thake)
        static::where('student_id', 'like', 'INFO-____-%')->get(['id', 'student_id'])
            ->each(fn ($u) => DB::table('users')->where('id', $u->id)
                ->update(['student_id' => static::formatStudentId((int) preg_replace('/^.*-/', '', $u->student_id))]));

        $missing = static::students()->whereNull('student_id')->orderBy('id')->pluck('id');
        if ($missing->isEmpty()) {
            return;
        }
        $n = static::nextStudentNumber() - 1;
        foreach ($missing as $id) {
            DB::table('users')->where('id', $id)->update(['student_id' => static::formatStudentId(++$n)]);
        }
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /** Most recent enrollment (for admin student list). */
    public function latestEnrollment()
    {
        return $this->hasOne(Enrollment::class)->latestOfMany();
    }

    public function scopeStudents($query)
    {
        return $query->where('role', 'user');
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo && str_starts_with($this->photo, 'http')) {
            return $this->photo;
        }
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            return asset('storage/' . $this->photo);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=EEEBFC&color=3728B5&size=80';
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STUDENT_STATUSES[$this->status ?? 'active'] ?? ucfirst((string) $this->status);
    }
}
