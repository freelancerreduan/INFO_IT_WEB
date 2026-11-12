<?php

namespace App\View\Composers;

use App\Models\Application;
use App\Models\ContactMessage;
use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\View\View;

/** Topbar notification bell: pending orders, pending payments, new applications. */
class AdminLayoutComposer
{
    private static ?array $cache = null;

    public function compose(View $view): void
    {
        if (self::$cache === null) {
            $orders = Enrollment::with(['user'])->where('order_status', 'pending')->latest('id')->take(4)->get()
                ->map(fn ($o) => [
                    'icon' => 'bi-bag', 'title' => "New order {$o->order_no}",
                    'sub'  => ($o->user?->name ?? '—') . ' · ' . money($o->fee),
                    'url'  => route('admin.orders.index', ['q' => $o->order_no]), 'date' => $o->created_at,
                ]);

            $payments = Payment::with('user')->whereIn('status', ['pending', 'due'])->latest('id')->take(4)->get()
                ->map(fn ($t) => [
                    'icon' => 'bi-cash-coin', 'title' => "Pending payment {$t->trx_id}",
                    'sub'  => ($t->user?->name ?? '—') . ' · ' . $t->method . ' · ' . money($t->amount),
                    'url'  => route('admin.transactions.index', ['q' => $t->trx_id]), 'date' => $t->created_at,
                ]);

            $apps = Application::with('course')->where('status', 'new')->latest()->take(4)->get()
                ->map(fn ($a) => [
                    'icon' => 'bi-envelope-paper', 'title' => "New application — {$a->name}",
                    'sub'  => ($a->course?->title ?? 'General') . ' · ' . ($a->phone ?: $a->email),
                    'url'  => route('admin.applications.index', ['q' => $a->email]), 'date' => $a->created_at,
                ]);

            $messages = ContactMessage::where('status', 'new')->latest('id')->take(4)->get()
                ->map(fn ($m) => [
                    'icon' => 'bi-chat-left-text', 'title' => "New message — {$m->name}",
                    'sub'  => $m->phone . ' · ' . \Illuminate\Support\Str::limit($m->message, 40),
                    'url'  => route('admin.messages.index', ['q' => $m->email]), 'date' => $m->created_at,
                ]);

            self::$cache = [
                'items' => $orders->concat($payments)->concat($apps)->concat($messages)->sortByDesc('date')->take(6)->values(),
                'count' => Enrollment::where('order_status', 'pending')->count()
                    + Payment::whereIn('status', ['pending', 'due'])->count()
                    + Application::where('status', 'new')->count()
                    + ContactMessage::where('status', 'new')->count(),
            ];
        }

        $view->with('adminNotifications', self::$cache);
    }
}
