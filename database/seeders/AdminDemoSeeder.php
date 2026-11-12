<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Costing;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Admin panel er chart / report / costing dekhar jonno demo history.
 *   php artisan db:seed --class=AdminDemoSeeder
 * (DatabaseSeeder theke o call hoy.)
 */
class AdminDemoSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();
        if ($courses->isEmpty()) {
            return;
        }

        $first   = ['Arif', 'Nabila', 'Sabbir', 'Tania', 'Rasel', 'Jannat', 'Mehedi', 'Sadia', 'Tamim', 'Rumana', 'Fahim', 'Lamia', 'Siam', 'Nusrat'];
        $last    = ['Hossain', 'Rahman', 'Islam', 'Akter', 'Chowdhury', 'Ahmed', 'Karim', 'Uddin', 'Sarker', 'Khan'];
        $methods = ['bKash', 'Nagad', 'Card', 'Bank', 'Cash'];
        $password = Hash::make('password');
        $seq = User::max('id') ?? 0;

        $start = now()->startOfMonth()->subMonths(23);
        for ($m = 0; $m < 24; $m++) {
            $month = $start->copy()->addMonths($m);

            foreach (range(1, 2 + intdiv($m, 6) + mt_rand(0, 2)) as $_) {
                $seq++;
                $course = $courses->random();
                $date   = $month->copy()->addDays(mt_rand(0, $month->daysInMonth - 1))->min(now());
                $name   = $first[array_rand($first)] . ' ' . $last[array_rand($last)];

                $user = User::create([
                    'name' => $name, 'email' => Str::slug($name, '.') . ".$seq@demo.test", 'password' => $password,
                    'role' => 'user', 'phone' => '01' . mt_rand(3, 9) . mt_rand(10000000, 99999999),
                    'parent_phone' => '01' . mt_rand(3, 9) . mt_rand(10000000, 99999999),
                    'nid' => (string) mt_rand(1990000000, 2005999999), 'status' => mt_rand(0, 9) ? 'active' : 'inactive',
                ]);
                $user->forceFill(['created_at' => $date, 'updated_at' => $date])->save();

                $isRecent = $m >= 22 && mt_rand(0, 1);
                $e = Enrollment::create([
                    'user_id' => $user->id, 'course_id' => $course->id, 'fee' => $course->price, 'duration' => $course->duration,
                    'status' => 'active', 'order_status' => $isRecent ? 'pending' : 'completed', 'enrolled_at' => $date,
                ]);

                $method = $methods[array_rand($methods)];
                Payment::create([
                    'user_id' => $user->id, 'enrollment_id' => $e->id, 'amount' => $isRecent ? round($course->price / 2) : $course->price,
                    'method' => $method, 'status' => $isRecent ? 'pending' : 'paid', 'paid_at' => $date,
                    'account_no' => $method === 'Card' ? '**** ' . mt_rand(1000, 9999) : ($method === 'Cash' ? null : '01' . mt_rand(3, 9) . mt_rand(10000000, 99999999)),
                    'note' => $isRecent ? 'Awaiting verification' : 'Full payment',
                ]);
            }

            foreach ([['Office Rent', 18000], ['Instructor Salary', 22000 + $m * 400], ['Facebook Ads', mt_rand(3000, 9000)]] as [$title, $amount]) {
                Costing::create(['title' => $title, 'amount' => $amount, 'spent_at' => $month->copy()->addDays(4)->min(now())]);
            }
        }
    }
}
