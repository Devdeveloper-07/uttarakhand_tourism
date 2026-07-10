<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingPackage;
use App\Models\Hotel;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Review;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class DashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $user = Auth::user();

        $totalBookings = Booking::count();

        $activeBookings = 0;

        $totalPackages = BookingPackage::count();

        $totalReviews = Review::count();

        $totalPayments = 0;

        $recentBookings = Booking::latest()
            ->take(5)
            ->get();

        $recentPackages = BookingPackage::latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact(
            'user',
            'totalBookings',
            'activeBookings',
            'totalPackages',
            'totalReviews',
            'totalPayments',
            'recentBookings',
            'recentPackages'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | My Bookings
    |--------------------------------------------------------------------------
    */

    public function bookings()
    {
        $bookings = Booking::latest()
            ->paginate(10);

        return view('user.bookings.index', compact('bookings'));
    }

    /*
    |--------------------------------------------------------------------------
    | Booking Details
    |--------------------------------------------------------------------------
    */

    public function bookingShow($id)
    {
        $booking = Booking::findOrFail($id);

        return view('user.bookings.show', compact('booking'));
    }


        /*
    |--------------------------------------------------------------------------
    | My Hotels
    |--------------------------------------------------------------------------
    */

    public function hotels()
    {
        $hotels = Hotel::latest()
            ->paginate(10);

        return view('user.hotels.index', compact('hotels'));
    }

    /*
    |--------------------------------------------------------------------------
    | Hotel Details
    |--------------------------------------------------------------------------
    */

    public function hotelShow($id)
    {
        $hotel = Hotel::findOrFail($id);

        return view('user.hotels.show', compact('hotel'));
    }

    /*
    |--------------------------------------------------------------------------
    | My Packages
    |--------------------------------------------------------------------------
    */

    public function packages()
    {
        $packages = Package::latest()
            ->paginate(10);

        return view('user.packages.index', compact('packages'));
    }

    /*
    |--------------------------------------------------------------------------
    | Package Details
    |--------------------------------------------------------------------------
    */

    public function packageShow($id)
    {
        $package = Package::findOrFail($id);

        return view('user.packages.show', compact('package'));
    }


        /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    public function profile()
    {
        $user = Auth::user();

        $detail = UserDetail::firstOrCreate([
            'user_id' => $user->id
        ]);

        return view('user.profile.index', compact('user', 'detail'));
    }

    /*
    |--------------------------------------------------------------------------
    | Update Profile
    |--------------------------------------------------------------------------
    */

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email,' . $user->id,

            'mobile' => 'nullable|max:20',

            'gender' => 'nullable|in:Male,Female,Other',

            'dob' => 'nullable|date',

            'address' => 'nullable|string',

            'city' => 'nullable|string|max:100',

            'state' => 'nullable|string|max:100',

            'country' => 'nullable|string|max:100',

            'pincode' => 'nullable|string|max:10',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

        ]);

        // Update User Table

        $user->update([

            'name' => $request->name,

            'email' => $request->email,

        ]);

        // Update User Details

        $detail = UserDetail::firstOrCreate([

            'user_id' => $user->id

        ]);

        if ($request->hasFile('image')) {

            if (
                $detail->image &&
                File::exists(public_path('uploads/users/' . $detail->image))
            ) {

                File::delete(public_path('uploads/users/' . $detail->image));

            }

            $imageName = time() . '.' . $request->image->getClientOriginalExtension();

            $request->image->move(
                public_path('uploads/users'),
                $imageName
            );

            $detail->image = $imageName;
        }

        $detail->mobile = $request->mobile;

        $detail->gender = $request->gender;

        $detail->dob = $request->dob;

        $detail->address = $request->address;

        $detail->city = $request->city;

        $detail->state = $request->state;

        $detail->country = $request->country;

        $detail->pincode = $request->pincode;

        $detail->save();

        return redirect()
            ->route('user.profile')
            ->with('success', 'Profile Updated Successfully.');
    }
}
