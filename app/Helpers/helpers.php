<?php

use App\Models\Setting;
use Illuminate\Support\HtmlString;

/*
|--------------------------------------------------------------------------
| Admin panel helpers
|--------------------------------------------------------------------------
| AppServiceProvider::register() theke load hoy (composer.json "files" e o add kora ache)
*/

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return Setting::allCached()[$key] ?? $default;
    }
}

if (! function_exists('currency_symbol')) {
    function currency_symbol(): string
    {
        return setting('currency', 'BDT') === 'USD' ? '$' : '৳';
    }
}

if (! function_exists('money')) {
    /** ৳12,000 (same as JS money() in the original design) */
    function money($amount): string
    {
        $amount = (float) $amount;

        return ($amount < 0 ? '-' : '') . currency_symbol() . number_format(abs($amount));
    }
}

if (! function_exists('money_lakh')) {
    /** ৳18,42,500 style (South-Asian grouping) used on stat cards. USD -> normal grouping. */
    function money_lakh($amount): string
    {
        $amount = (int) round((float) $amount);
        if (setting('currency', 'BDT') === 'USD') {
            return '$' . number_format($amount);
        }
        $neg = $amount < 0;
        $s = (string) abs($amount);
        if (strlen($s) > 3) {
            $last3 = substr($s, -3);
            $rest  = substr($s, 0, -3);
            $rest  = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
            $s = $rest . ',' . $last3;
        }

        return ($neg ? '-' : '') . '৳' . $s;
    }
}

if (! function_exists('status_pill')) {
    function status_pill(?string $status): HtmlString
    {
        $status = $status ?: '—';

        return new HtmlString(
            '<span class="status-pill status-' . e(strtolower($status)) . '">' . e($status) . '</span>'
        );
    }
}

if (! function_exists('initials')) {
    function initials(?string $name): string
    {
        $words = preg_split('/\s+/', trim((string) $name)) ?: [];

        return strtoupper(implode('', array_map(fn ($w) => mb_substr($w, 0, 1), array_slice(array_filter($words), 0, 2))));
    }
}

if (! function_exists('bn_num')) {
    /** English digit -> Bangla digit (1,250 -> ১,২৫০) */
    function bn_num($value): string
    {
        return strtr((string) $value, ['0' => '০', '1' => '১', '2' => '২', '3' => '৩', '4' => '৪', '5' => '৫', '6' => '৬', '7' => '৭', '8' => '৮', '9' => '৯']);
    }
}

if (! function_exists('bn_money')) {
    /** ৳১৫,০০০ — website er jonno (Bangla digit) */
    function bn_money($amount): string
    {
        return '৳' . bn_num(number_format((float) $amount));
    }
}

if (! function_exists('bn_date')) {
    /** 2026-09-25 -> "শুক্রবার, ২৫ সেপ্টেম্বর ২০২৬" */
    function bn_date($date, bool $withDay = true, bool $withYear = true): string
    {
        if (! $date) {
            return '';
        }
        $d = $date instanceof \DateTimeInterface ? \Illuminate\Support\Carbon::instance($date) : \Illuminate\Support\Carbon::parse($date);

        $days = ['Sunday' => 'রবিবার', 'Monday' => 'সোমবার', 'Tuesday' => 'মঙ্গলবার', 'Wednesday' => 'বুধবার',
                 'Thursday' => 'বৃহস্পতিবার', 'Friday' => 'শুক্রবার', 'Saturday' => 'শনিবার'];
        $months = [1 => 'জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];

        $out = bn_num($d->format('j')) . ' ' . $months[(int) $d->format('n')];
        if ($withYear) {
            $out .= ' ' . bn_num($d->format('Y'));
        }
        if ($withDay) {
            $out = $days[$d->format('l')] . ', ' . $out;
        }

        return $out;
    }
}

if (! function_exists('site_logo')) {
    /**
     * Institute logo URL — website, admin panel, student panel shob jaygay same logo.
     * Logo change korte: public/img/logo.jpg file ta replace korun.
     */
    function site_logo(): string
    {
        return asset('img/logo.jpg');
    }
}

if (! function_exists('local_time')) {
    /**
     * Database e shob shomoy UTC te save hoy — admin panel e Bangladesh time (Asia/Dhaka) e dekhai.
     * local_time($m->created_at)->format('d M Y, h:i A')
     */
    function local_time($date): ?\Illuminate\Support\Carbon
    {
        if (! $date) {
            return null;
        }
        $d = $date instanceof \DateTimeInterface ? \Illuminate\Support\Carbon::instance($date) : \Illuminate\Support\Carbon::parse($date);

        return $d->copy()->setTimezone('Asia/Dhaka');
    }
}

if (! function_exists('human_time')) {
    /** "এইমাত্র", "৫ মিনিট আগে", "২ ঘণ্টা আগে", "গতকাল", "৩ দিন আগে" — ১ সপ্তাহের বেশি হলে তারিখ */
    function human_time($date): string
    {
        $d = local_time($date);
        if (! $d) {
            return '—';
        }
        $now  = now('Asia/Dhaka');
        $mins = (int) floor(max(0, $now->getTimestamp() - $d->getTimestamp()) / 60);

        if ($mins < 1) {
            return 'এইমাত্র';
        }
        if ($mins < 60) {
            return bn_num($mins) . ' মিনিট আগে';
        }
        if ($d->isSameDay($now)) {
            return bn_num(intdiv($mins, 60)) . ' ঘণ্টা আগে';
        }
        if ($d->isSameDay($now->copy()->subDay())) {
            return 'গতকাল';
        }
        $days = (int) $d->copy()->startOfDay()->diffInDays($now->copy()->startOfDay());
        if ($days < 7) {
            return bn_num($days) . ' দিন আগে';
        }

        return bn_date($d, false, ! $d->isSameYear($now));
    }
}
