<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Admin panel e eta "Order" hishebe dekhano hoy.
 *
 * order_status:
 *   pending   -> user order diyeche, admin ekhono confirm kore ni
 *   completed -> admin "Enroll" korche (Enrolled) — shudhu eigulo e "kena course" hishebe count hoy
 *   rejected  -> admin reject korche
 *   canceled  -> admin cancel korche
 *   deleted   -> admin delete korche (user er order history te "Deleted" dekhay;
 *                admin chaile "Delete permanently" diye puro muche dite pare)
 */
class Enrollment extends Model
{
    use HasFactory;

    public const ORDER_STATUSES = [
        'pending'   => 'Pending',
        'completed' => 'Enrolled',
        'rejected'  => 'Rejected',
        'canceled'  => 'Canceled',
        'deleted'   => 'Deleted',
    ];

    /** Ei status gulo te due / payment hishab chole. */
    public const OPEN_STATUSES = ['pending', 'completed'];

    protected $fillable = [
        'order_no', 'user_id', 'course_id', 'fee', 'duration', 'status', 'order_status', 'enrolled_at',
    ];

    protected $casts = [
        'enrolled_at' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Enrollment $e) {
            $e->order_no     = $e->order_no ?: static::nextOrderNo();
            $e->order_status = $e->order_status ?: 'pending';
            $e->enrolled_at  = $e->enrolled_at ?: now();
        });
    }

    public static function nextOrderNo(): string
    {
        $max = static::whereNotNull('order_no')->pluck('order_no')
            ->map(fn ($no) => (int) preg_replace('/\D/', '', $no))->max();

        $n = max(1000, (int) $max) + 1;
        while (static::where('order_no', 'ORD-' . $n)->exists()) {
            $n++;
        }

        return 'ORD-' . $n;
    }

    /* ---------- Relations ---------- */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function paidPayments()
    {
        return $this->hasMany(Payment::class)->where('status', 'paid');
    }

    /* ---------- Scopes ---------- */

    /** Admin er delete kora order bad diye. */
    public function scopeNotDeleted(Builder $q): Builder
    {
        return $q->where('order_status', '!=', 'deleted');
    }

    /** Admin "Enroll" kora order (user er kena course). */
    public function scopeEnrolled(Builder $q): Builder
    {
        return $q->where('order_status', 'completed');
    }

    /** Pending + Enrolled (jegulo te payment neya jay). */
    public function scopeOpen(Builder $q): Builder
    {
        return $q->whereIn('order_status', self::OPEN_STATUSES);
    }

    /** withSum(paid_sum) shortcut. */
    public function scopeWithPaid(Builder $q): Builder
    {
        return $q->withSum(['payments as paid_sum' => fn ($p) => $p->where('status', 'paid')], 'amount');
    }

    /** withSum(pending_sum) — admin approve er opekkhay thaka payment. */
    public function scopeWithPending(Builder $q): Builder
    {
        return $q->withSum(['payments as pending_sum' => fn ($p) => $p->where('status', 'pending')], 'amount');
    }

    /* ---------- Money ---------- */

    public function paidTotal(): float
    {
        if (array_key_exists('paid_sum', $this->attributes)) {
            return (float) $this->attributes['paid_sum'];
        }

        return (float) $this->payments()->where('status', 'paid')->sum('amount');
    }

    /** Course price - paid (reject/cancel/delete order e due 0). */
    public function due(): float
    {
        if (! $this->isOpen()) {
            return 0;
        }

        return max(0, round((float) $this->fee - $this->paidTotal(), 2));
    }

    /** Approve er opekkhay thaka (pending) payment er total. */
    public function pendingTotal(): float
    {
        if (array_key_exists('pending_sum', $this->attributes)) {
            return (float) $this->attributes['pending_sum'];
        }

        return (float) $this->payments()->where('status', 'pending')->sum('amount');
    }

    /** Student ekhon koto taka pay korte parbe = due - pending payment. */
    public function payable(): float
    {
        return max(0, round($this->due() - $this->pendingTotal(), 2));
    }

    public function isOpen(): bool
    {
        return in_array($this->order_status, self::OPEN_STATUSES, true);
    }

    public function isEnrolled(): bool
    {
        return $this->order_status === 'completed';
    }

    /* ---------- Admin / User panel helpers ---------- */

    public function getOrderStatusLabelAttribute(): string
    {
        return self::ORDER_STATUSES[$this->order_status] ?? ucfirst((string) $this->order_status);
    }

    /**
     * Paid / Partial / Failed / Pending
     * Speed er jonno ->withPaid() use korun.
     */
    public function getPaymentLabelAttribute(): string
    {
        $paid = $this->paidTotal();
        $fee  = (float) $this->fee;

        if ($fee <= 0 || $paid >= $fee) {
            return 'Paid';
        }
        if ($paid > 0) {
            return 'Partial';
        }
        if ($this->payments()->where('status', 'rejected')->exists()) {
            return 'Failed';
        }

        return 'Due';
    }
}
