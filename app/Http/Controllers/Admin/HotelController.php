<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::latest()->paginate(10);

        return view('admin.hotels.index', compact('hotels'));
    }

    public function create()
    {
        return view('admin.hotels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_name' => 'required|max:255',
            'location' => 'required',
            'city' => 'required',
            'description' => 'required',
            'starting_price' => 'required|numeric',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $image = null;

        if ($request->hasFile('thumbnail')) {

            $file = $request->file('thumbnail');

            $image = time().'_'.$file->getClientOriginalName();

            $file->move(public_path('uploads/hotels'), $image);
        }

        Hotel::create([
            'hotel_name' => $request->hotel_name,
            'slug' => Str::slug($request->hotel_name),
            'location' => $request->location,
            'city' => $request->city,
            'state' => 'Uttarakhand',
            'description' => $request->description,
            'thumbnail' => $image,
            'starting_price' => $request->starting_price,
            'rating' => 5,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel Added Successfully.');
    }

    public function edit(Hotel $hotel)
    {
        return view('admin.hotels.edit', compact('hotel'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $request->validate([
            'hotel_name' => 'required|max:255',
            'location' => 'required',
            'city' => 'required',
            'description' => 'required',
            'starting_price' => 'required|numeric',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $image = $hotel->thumbnail;

        if ($request->hasFile('thumbnail')) {

            if ($hotel->thumbnail && File::exists(public_path('uploads/hotels/'.$hotel->thumbnail))) {

                File::delete(public_path('uploads/hotels/'.$hotel->thumbnail));

            }

            $file = $request->file('thumbnail');

            $image = time().'_'.$file->getClientOriginalName();

            $file->move(public_path('uploads/hotels'), $image);
        }

        $hotel->update([
            'hotel_name' => $request->hotel_name,
            'slug' => Str::slug($request->hotel_name),
            'location' => $request->location,
            'city' => $request->city,
            'description' => $request->description,
            'thumbnail' => $image,
            'starting_price' => $request->starting_price,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('admin.hotels.index')
            ->with('success', 'Hotel Updated Successfully.');
    }

    public function destroy(Hotel $hotel)
    {
        if (Booking::query()->where('item_type', 'hotel')->where('item_id', $hotel->id)->exists()) {
            return back()->withErrors([
                'hotel' => 'This hotel has booking history and cannot be deleted. Disable it instead.',
            ]);
        }

        if ($hotel->thumbnail && File::exists(public_path('uploads/hotels/'.$hotel->thumbnail))) {

            File::delete(public_path('uploads/hotels/'.$hotel->thumbnail));
        }

        $hotel->delete();

        return redirect()->back()
            ->with('success', 'Hotel Deleted Successfully.');
    }

    public function show(Hotel $hotel)
{
    return view('admin.hotels.show', compact('hotel'));
}
}
