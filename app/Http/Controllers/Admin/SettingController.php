<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public const CURRENCIES = ['BDT' => 'BDT (৳)', 'USD' => 'USD ($)'];

    public const TIMEZONES = [
        'Asia/Dhaka'   => 'Asia/Dhaka (GMT+6)',
        'Asia/Kolkata' => 'Asia/Kolkata (GMT+5:30)',
        'Asia/Dubai'   => 'Asia/Dubai (GMT+4)',
        'Europe/London'=> 'Europe/London (GMT+0)',
        'UTC'          => 'UTC (GMT+0)',
    ];

    public function index()
    {
        return view('admin.settings.index', [
            'settings'   => Setting::allCached(),
            'currencies' => self::CURRENCIES,
            'timezones'  => self::TIMEZONES,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'institute_name' => ['required', 'string', 'max:120'],
            'support_email'  => ['required', 'email', 'max:150'],
            'currency'       => ['required', Rule::in(array_keys(self::CURRENCIES))],
            'timezone'       => ['required', Rule::in(array_keys(self::TIMEZONES))],
            // ---- website (frontend) information ----
            'phone'          => ['nullable', 'string', 'max:40'],
            'phone_display'  => ['nullable', 'string', 'max:60'],
            'phone_short'    => ['nullable', 'string', 'max:40'],
            'whatsapp'       => ['nullable', 'string', 'max:40'],
            'address'        => ['nullable', 'string', 'max:255'],
            'office_hours'   => ['nullable', 'string', 'max:255'],
            'office_note'    => ['nullable', 'string', 'max:255'],
            'website'        => ['nullable', 'string', 'max:120'],
            'map_query'      => ['nullable', 'string', 'max:255'],
            'facebook'       => ['nullable', 'string', 'max:255'],
            'instagram'      => ['nullable', 'string', 'max:255'],
            'linkedin'       => ['nullable', 'string', 'max:255'],
            'youtube'        => ['nullable', 'string', 'max:255'],
            'stat_students'  => ['nullable', 'integer', 'min:0'],
            'stat_trainers'  => ['nullable', 'integer', 'min:0'],
            'stat_courses'   => ['nullable', 'integer', 'min:0'],
            'stat_satisfaction' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        Setting::put(array_map(fn ($v) => (string) $v, array_filter($data, fn ($v) => $v !== null)));

        return back()->with('success', 'Settings saved successfully.');
    }
}
