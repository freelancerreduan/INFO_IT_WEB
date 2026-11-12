<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Admin panel e eta "Transaction" hishebe dekhano hoy.
 * DB status: paid | pending | due | rejected
 */
class Payment extends Model
{
    use HasFactory;

    public const METHODS  = ['bKash', 'Nagad', 'Rocket', 'Card', 'Bank', 'Cash'];
    public const STATUSES = ['paid' => 'Completed', 'pending' => 'Pending', 'due' => 'Due', 'rejected' => 'Rejected'];

    /** Student panel theke ei method gulo diye payment kora jay. */
    public const STUDENT_METHODS = ['bKash', 'Nagad', 'Rocket', 'Bank'];

    /** Student panel e status er nam (rejected -> Failed). */
    public const STUDENT_STATUSES = ['paid' => 'Paid', 'pending' => 'Pending', 'due' => 'Due', 'rejected' => 'Failed'];

    protected $fillable = [
        'trx_id', 'user_id', 'enrollment_id', 'amount', 'method', 'account_no', 'status', 'paid_at', 'note',
    ];

    protected $casts = [
        'paid_at' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Payment $p) {
            if (! $p->trx_id) {
                do {
                    $id = strtoupper(Str::random(9));
                } while (static::where('trx_id', $id)->exists());
                $p->trx_id = $id;
            }
            $p->paid_at = $p->paid_at ?: now();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }

    /** Student panel er label: Paid / Pending / Failed */
    public function getStudentStatusLabelAttribute(): string
    {
        return self::STUDENT_STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }

    /** Label -> DB value (Completed -> paid) */
    public static function statusFromLabel(?string $label): ?string
    {
        if (! $label) {
            return null;
        }
        $key = array_search($label, self::STATUSES, true);

        return $key !== false ? $key : (array_key_exists(strtolower($label), self::STATUSES) ? strtolower($label) : null);
    }
}
