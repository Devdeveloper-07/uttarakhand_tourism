<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Gallery;
use App\Models\Hotel;
use App\Models\Package;
use App\Models\Review;
use App\Models\Room;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index()
    {

        $totalHotels = Hotel::count();
        $totalRooms = Room::count();
        $totalPackages = Package::count();
        $totalBookings = Booking::count();
        $totalUsers = User::where('role','user')->count();

        $activities = collect();

        foreach (Booking::latest()->take(5)->get() as $booking) {

            $activities->push([

                'icon' => 'fa-calendar-check',
                'color' => 'success',

                'title' => 'New Booking',

                'message' => '#BK-'.$booking->id,

                'time' => $booking->created_at,

            ]);

        }

        foreach (Hotel::latest()->take(5)->get() as $hotel) {

            $activities->push([

                'icon'=>'fa-hotel',

                'color'=>'primary',

                'title'=>'Hotel Added',

                'message'=>$hotel->hotel_name,

                'time'=>$hotel->created_at,

            ]);

        }

        foreach (Room::latest()->take(5)->get() as $room) {

            $activities->push([

                'icon'=>'fa-bed',

                'color'=>'warning',

                'title'=>'Room Added',

                'message'=>$room->room_name,

                'time'=>$room->created_at,

            ]);

        }

        foreach (Package::latest()->take(5)->get() as $package) {

            $activities->push([

                'icon'=>'fa-suitcase',

                'color'=>'info',

                'title'=>'Package Added',

                'message'=>$package->package_name,

                'time'=>$package->created_at,

            ]);

        }

        foreach (Gallery::latest()->take(5)->get() as $gallery) {

            $activities->push([

                'icon'=>'fa-image',

                'color'=>'secondary',

                'title'=>'Gallery Image',

                'message'=>'New Image Uploaded',

                'time'=>$gallery->created_at,

            ]);

        }

        foreach (User::where('role','user')->latest()->take(5)->get() as $user) {

            $activities->push([

                'icon'=>'fa-user',

                'color'=>'dark',

                'title'=>'New User',

                'message'=>$user->name,

                'time'=>$user->created_at,

            ]);

        }

        foreach (Testimonial::latest()->take(5)->get() as $testimonial) {

            $activities->push([

                'icon'=>'fa-star',

                'color'=>'warning',

                'title'=>'Testimonial',

                'message'=>$testimonial->name,

                'time'=>$testimonial->created_at,

            ]);

        }

        $activities = $activities

            ->sortByDesc('time')

            ->take(4);

        return view('admin.dashboard',compact(

            'totalHotels',
            'totalRooms',
            'totalPackages',
            'totalBookings',
            'totalUsers',
            'activities'

        ));

    }
}
