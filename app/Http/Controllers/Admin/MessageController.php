<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Services\CsvExport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Website er contact form theke asha message gulo.
 */
class MessageController extends Controller
{
    public function index(Request $request)
    {
        $messages = $this->filtered($request)->paginate(10)->withQueryString();

        $counts = [
            'all'     => ContactMessage::count(),
            'new'     => ContactMessage::where('status', 'new')->count(),
            'read'    => ContactMessage::where('status', 'read')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
        ];

        return view('admin.messages.index', compact('messages', 'counts'));
    }

    /** Status: New / Read / Replied */
    public function update(Request $request, ContactMessage $message)
    {
        $request->validate(['status' => ['required', Rule::in(array_keys(ContactMessage::STATUSES))]]);
        $message->update(['status' => $request->status]);

        return back()->with('success', 'Message marked as ' . ContactMessage::STATUSES[$request->status] . '.');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return back()->with('success', 'Message deleted successfully.');
    }

    public function export(Request $request)
    {
        $rows = $this->filtered($request)->get()->map(fn (ContactMessage $m) => [
            $m->name, $m->phone, $m->email, $m->message, $m->source_label, $m->status_label, local_time($m->created_at)?->format('Y-m-d H:i'),
        ]);

        return CsvExport::download('messages-' . now()->format('Y-m-d') . '.csv',
            ['Name', 'Phone', 'Email', 'Message', 'From', 'Status', 'Date'], $rows);
    }

    private function filtered(Request $request)
    {
        return ContactMessage::query()
            ->when($request->q, fn ($q, $v) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%$v%")->orWhere('email', 'like', "%$v%")
                ->orWhere('phone', 'like', "%$v%")->orWhere('message', 'like', "%$v%")))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->date, fn ($q, $v) => $q->whereDate('created_at', $v))
            ->latest('id');
    }
}
