<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testimonials = Testimonial::latest()->paginate(10);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.testimonials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([

            'name' => 'required|max:255',

            'designation' => 'nullable|max:255',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'rating' => 'required|integer|min:1|max:5',

            'message' => 'required',

            'status' => 'required',

        ]);

        $imageName = null;

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $imageName = time().'.'.$image->getClientOriginalExtension();

            $image->move(public_path('uploads/testimonials'), $imageName);

        }

        Testimonial::create([

            'name' => $request->name,

            'designation' => $request->designation,

            'image' => $imageName,

            'rating' => $request->rating,

            'message' => $request->message,

            'status' => $request->status,

        ]);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimonial Added Successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Testimonial $testimonial)
    {
        return view('admin.testimonials.show', compact('testimonial'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([

            'name' => 'required|max:255',

            'designation' => 'nullable|max:255',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'rating' => 'required|integer|min:1|max:5',

            'message' => 'required',

            'status' => 'required',

        ]);

        $imageName = $testimonial->image;

        if ($request->hasFile('image')) {

            if ($testimonial->image &&
                File::exists(public_path('uploads/testimonials/'.$testimonial->image))) {

                File::delete(public_path('uploads/testimonials/'.$testimonial->image));

            }

            $image = $request->file('image');

            $imageName = time().'.'.$image->getClientOriginalExtension();

            $image->move(public_path('uploads/testimonials'), $imageName);

        }

        $testimonial->update([

            'name' => $request->name,

            'designation' => $request->designation,

            'image' => $imageName,

            'rating' => $request->rating,

            'message' => $request->message,

            'status' => $request->status,

        ]);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimonial Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->image &&
            File::exists(public_path('uploads/testimonials/'.$testimonial->image))) {

            File::delete(public_path('uploads/testimonials/'.$testimonial->image));

        }

        $testimonial->delete();

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimonial Deleted Successfully.');
    }
}
