<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassVideo;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/**
 * Website er homepage "ক্লাস ভিডিও" section manage kore.
 */
class VideoController extends Controller
{
    public function index(Request $request)
    {
        $videos = ClassVideo::with('course')
            ->when($request->q, fn ($q, $v) => $q->where('title', 'like', "%$v%"))
            ->when($request->course, fn ($q, $v) => $q->where('course_id', $v))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->orderBy('sort_order')->latest('id')
            ->paginate(12)->withQueryString();

        $courses = Course::orderBy('title')->get(['id', 'title']);

        return view('admin.videos.index', compact('videos', 'courses'));
    }

    public function create()
    {
        return view('admin.videos.create', [
            'video'   => new ClassVideo(['source' => 'upload', 'status' => 'active']),
            'courses' => Course::orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('video_file')) {
            $data['video_path'] = $request->file('video_file')->store('videos', 'public');
        }
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('videos/thumbs', 'public');
        }

        ClassVideo::create($data);

        return redirect()->route('admin.videos.index')->with('success', 'Video added successfully.');
    }

    public function edit(ClassVideo $video)
    {
        return view('admin.videos.create', [
            'video'   => $video,
            'courses' => Course::orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function update(Request $request, ClassVideo $video)
    {
        $data = $this->validated($request);

        if ($request->hasFile('video_file')) {
            $this->deleteFile($video->video_path);
            $data['video_path'] = $request->file('video_file')->store('videos', 'public');
        }
        if ($request->hasFile('thumbnail')) {
            $this->deleteFile($video->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('videos/thumbs', 'public');
        }

        $video->update($data);

        return redirect()->route('admin.videos.index')->with('success', 'Video updated successfully.');
    }

    public function destroy(ClassVideo $video)
    {
        $this->deleteFile($video->video_path);
        $this->deleteFile($video->thumbnail);
        $video->delete();

        return back()->with('success', 'Video deleted successfully.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'course_id'    => ['nullable', 'exists:courses,id'],
            'course_label' => ['nullable', 'string', 'max:120'],
            'batch'        => ['nullable', 'string', 'max:60'],
            'class_date'   => ['nullable', 'date'],
            'date_label'   => ['nullable', 'string', 'max:120'],
            'duration'     => ['nullable', 'string', 'max:20'],
            'source'       => ['required', Rule::in(array_keys(ClassVideo::SOURCES))],
            'video_link'   => ['nullable', 'url', 'max:255'],
            'youtube_id'   => ['nullable', 'string', 'max:60'],
            'video_file'   => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:102400'],
            'thumbnail'    => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:4096'],
            'status'       => ['required', Rule::in(array_keys(ClassVideo::STATUSES))],
            'sort_order'   => ['nullable', 'integer', 'min:0'],
        ], [
            'video_file.uploaded'   => 'ভিডিওটি আপলোড হয়নি। সার্ভারের বর্তমান সীমা ' . ini_get('upload_max_filesize')
                . ' (post_max_size ' . ini_get('post_max_size') . ')। php.ini ফাইলে upload_max_filesize ও post_max_size বাড়িয়ে নিন, অথবা YouTube লিংক ব্যবহার করুন।',
            'video_file.mimetypes'  => 'শুধু MP4, WebM বা MOV ভিডিও আপলোড করা যাবে।',
        ]);

        // YouTube: full link dile shudhu ID rakha hoy
        if ($data['source'] === 'youtube') {
            $data['youtube_id'] = $this->youtubeId($data['youtube_id'] ?? null);
        } elseif (! empty($data['video_link'])) {
            $data['video_path'] = $data['video_link'];
        }

        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        unset($data['video_file'], $data['video_link']);

        return $data;
    }

    private function youtubeId(?string $value): ?string
    {
        if (! $value) {
            return null;
        }
        if (preg_match('~(?:youtu\.be/|v=|embed/|shorts/)([A-Za-z0-9_-]{6,})~', $value, $m)) {
            return $m[1];
        }

        return trim($value);
    }

    private function deleteFile(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http') && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
