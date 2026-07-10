<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::latest()->paginate(10);

        return view('admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.galleries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'image' => 'required',
            'image.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'type' => 'required',
            'description' => 'nullable',
            'status' => 'required'
        ]);

        $images = [];

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $imageName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/gallery'), $imageName);
                $images[] = $imageName;
            }
        }

        Gallery::create([
    'title' => $request->title,
    'image' => $images,
    'type' => $request->type,
    'description' => $request->description,
    'status' => $request->status,
]);

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Gallery Added Successfully');
    }

    public function show(Gallery $gallery)
    {
        return view('admin.galleries.show', compact('gallery'));
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.galleries.edit', compact('gallery'));
    }

private function normalizeImageNames($images)
{
    if (empty($images)) {
        return [];
    }

    if (is_array($images)) {
        return $images;
    }

    $decoded = json_decode($images, true);

    return is_array($decoded) ? $decoded : [$images];
}

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'title' => 'required|max:255',
            'image' => 'nullable',
            'image.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'type' => 'required',
            'description' => 'nullable',
            'status' => 'required'
        ]);

        $images = $this->normalizeImageNames($gallery->image);

        if ($request->hasFile('image')) {
            foreach ($images as $oldImage) {
                $path = public_path('uploads/gallery/'.$oldImage);
                if (File::exists($path)) {
                    File::delete($path);
                }
            }

            $images = [];
            foreach ($request->file('image') as $file) {
                $imageName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/gallery'), $imageName);
                $images[] = $imageName;
            }
        }

        $gallery->update([
            'title' => $request->title,
            'image' => $images,
            'type' => $request->type,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Gallery Updated Successfully');
    }

    public function destroy(Gallery $gallery)
    {
        $images = $this->normalizeImageNames($gallery->image);

        foreach ($images as $image) {
            $path = public_path('uploads/gallery/'.$image);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $gallery->delete();

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Gallery Deleted Successfully');
    }
}
