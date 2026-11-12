<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Room;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {

        $totalHotels = Hotel::count();
        $totalRooms = Room::count();
        $totalPackages = Package::count();
        $totalBookings = Booking::count();
        $totalUsers = User::where('role','user')->count();
        $totalPayments = Payment::count();
        $totalRevenue = Payment::where('payment_status', 'paid')->sum('amount');
        $recentBookings = Booking::with(['user', 'item'])->latest()->take(5)->get();
        $bookingStatusStats = Booking::query()
            ->selectRaw('booking_status as status, COUNT(*) as total')
            ->groupBy('booking_status')
            ->orderByDesc('total')
            ->get();
        $maxBookingStatusCount = max(1, (int) $bookingStatusStats->max('total'));

        return view('admin.dashboard',compact(

            'totalHotels',
            'totalRooms',
            'totalPackages',
            'totalBookings',
            'totalUsers',
            'totalPayments',
            'totalRevenue',
            'recentBookings',
            'bookingStatusStats',
            'maxBookingStatusCount'

        ));

    }
}
