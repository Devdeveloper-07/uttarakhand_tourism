<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PackageController extends Controller
{

    public function index()
    {
        $packages = Package::latest()->paginate(10);

        return view('admin.packages.index', compact('packages'));
    }


    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_name'     => 'required|max:255',
            'destination'      => 'required|max:255',
            'duration'         => 'required|integer',
            'price'            => 'required|numeric',
            'discount_price'   => 'nullable|numeric',
            'gst_category'     => 'required|in:tour_operator_eligible,tour_operator_with_itc',
            'max_person'       => 'required|integer',
            'thumbnail'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description'      => 'required',
            'includes'         => 'nullable',
            'excludes'         => 'nullable',
            'itinerary'        => 'nullable',
            'featured'         => 'required',
            'status'           => 'required',
        ]);

        $imageName = null;

        if ($request->hasFile('thumbnail')) {

            $image = $request->file('thumbnail');

            $imageName = time() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('uploads/packages'), $imageName);
        }

        Package::create([

            'package_name'    => $validated['package_name'],
            'destination'     => $validated['destination'],
            'duration'        => $validated['duration'],
            'price'           => $validated['price'],
            'discount_price'  => $validated['discount_price'] ?? null,
            'gst_category'    => $validated['gst_category'],
            'gst_percentage'  => $this->rateForCategory($validated['gst_category']),
            'max_person'      => $validated['max_person'],
            'thumbnail'       => $imageName,
            'description'     => $request->description,
            'includes'        => $request->includes,
            'excludes'        => $request->excludes,
            'itinerary'       => $request->itinerary,
            'featured'        => $request->featured,
            'status'          => $request->status,

        ]);

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Package Added Successfully.');
    }


    public function show(Package $package)
    {
        return view('admin.packages.show', compact('package'));
    }

    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'package_name'     => 'required|max:255',
            'destination'      => 'required|max:255',
            'duration'         => 'required|integer',
            'price'            => 'required|numeric',
            'discount_price'   => 'nullable|numeric',
            'gst_category'     => 'required|in:tour_operator_eligible,tour_operator_with_itc',
            'max_person'       => 'required|integer',
            'thumbnail'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description'      => 'required',
            'includes'         => 'nullable',
            'excludes'         => 'nullable',
            'itinerary'        => 'nullable',
            'featured'         => 'required',
            'status'           => 'required',
        ]);

        $imageName = $package->thumbnail;

        if ($request->hasFile('thumbnail')) {

            if ($package->thumbnail &&
                File::exists(public_path('uploads/packages/' . $package->thumbnail))) {

                File::delete(public_path('uploads/packages/' . $package->thumbnail));
            }

            $image = $request->file('thumbnail');

            $imageName = time() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('uploads/packages'), $imageName);
        }

        $package->update([

            'package_name'    => $validated['package_name'],
            'destination'     => $validated['destination'],
            'duration'        => $validated['duration'],
            'price'           => $validated['price'],
            'discount_price'  => $validated['discount_price'] ?? null,
            'gst_category'    => $validated['gst_category'],
            'gst_percentage'  => $this->rateForCategory($validated['gst_category']),
            'max_person'      => $validated['max_person'],
            'thumbnail'       => $imageName,
            'description'     => $request->description,
            'includes'        => $request->includes,
            'excludes'        => $request->excludes,
            'itinerary'       => $request->itinerary,
            'featured'        => $request->featured,
            'status'          => $request->status,

        ]);

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Package Updated Successfully.');
    }

    public function destroy(Package $package)
    {
        if (Booking::query()->where('item_type', 'package')->where('item_id', $package->id)->exists()) {
            return back()->withErrors([
                'package' => 'This package has booking history and cannot be deleted. Disable it instead.',
            ]);
        }

        if ($package->thumbnail &&
            File::exists(public_path('uploads/packages/' . $package->thumbnail))) {

            File::delete(public_path('uploads/packages/' . $package->thumbnail));
        }

        $package->delete();

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Package Deleted Successfully.');
    }

    private function rateForCategory(string $category): int
    {
        return match ($category) {
            'tour_operator_eligible' => 5,
            'tour_operator_with_itc' => 18,
        };
    }
}
