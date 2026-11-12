<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = GalleryItem::latest()->paginate(12);

        return view('admin.gallery.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.gallery.create', ['gallery' => new GalleryItem()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:png,jpg,jpeg,webp', 'max:4096'],
        ]);
        $data['image']    = $request->file('image')->store('gallery', 'public');
        $data['category'] = 'Campus';

        GalleryItem::create($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery photo uploaded successfully.');
    }

    public function edit(GalleryItem $gallery)
    {
        return view('admin.gallery.create', compact('gallery'));
    }

    public function update(Request $request, GalleryItem $gallery)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:4096'],
        ]);
        unset($data['image']);

        if ($request->hasFile('image')) {
            $this->deleteFile($gallery->image);
            $data['image'] = $request->file('image')->store('gallery', 'public');
        }

        $gallery->update($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery photo updated successfully.');
    }

    public function destroy(GalleryItem $gallery)
    {
        $this->deleteFile($gallery->image);
        $gallery->delete();

        return back()->with('success', 'Photo deleted successfully.');
    }

    private function deleteFile(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http') && ! str_starts_with($path, 'gallery/placeholder')) {
            Storage::disk('public')->delete($path);
        }
    }
}
