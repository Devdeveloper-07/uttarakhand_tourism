<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Hotel;
use App\Models\Package;
use App\Models\Testimonial;
use App\Services\RoomAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{


    public function index()
    {
        $featuredHotels = Hotel::where('status', 1)
            ->with(['amenities' => fn ($query) => $query->where('status', 1)])
            ->latest()
            ->take(6)
            ->get();

        $featuredPackages = Package::where('status', 1)
            ->latest()
            ->take(6)
            ->get();

        $galleries = Gallery::latest()
            ->take(8)
            ->get();

        $testimonials = Testimonial::where('status', 1)
            ->latest()
            ->take(6)
            ->get();

        $locations = Hotel::where('status', 1)
            ->select('location')
            ->distinct()
            ->orderBy('location')
            ->pluck('location');

        $destinations = Package::where('status', 1)
            ->select('destination')
            ->distinct()
            ->orderBy('destination')
            ->pluck('destination');

        $homeStats = [
            [
                'count' => Hotel::where('status', 1)->count(),
                'label' => 'Available Stays',
                'icon' => 'fa-hotel',
            ],
            [
                'count' => Package::where('status', 1)->count(),
                'label' => 'Tour Packages',
                'icon' => 'fa-route',
            ],
            [
                'count' => Hotel::where('status', 1)
                    ->whereNotNull('location')
                    ->where('location', '!=', '')
                    ->distinct()
                    ->count('location'),
                'label' => 'Hotel Locations',
                'icon' => 'fa-location-dot',
            ],
            [
                'count' => Testimonial::where('status', 1)->count(),
                'label' => 'Traveler Stories',
                'icon' => 'fa-comments',
            ],
        ];

        return view('frontend.home', compact(
            'featuredHotels',
            'featuredPackages',
            'galleries',
            'testimonials',
            'homeStats',
            'locations',
            'destinations'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Hotels
    |--------------------------------------------------------------------------
    */

public function hotels(Request $request)
{
    $dates = $request->validate([
        'check_in' => ['nullable', 'required_with:check_out', 'date', 'after_or_equal:today'],
        'check_out' => ['nullable', 'required_with:check_in', 'date', 'after:check_in'],
    ]);

    $query = Hotel::where('status', 1);

    // Search filter - hotel name ya location dono mein search
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('hotel_name', 'like', '%' . $request->search . '%')
              ->orWhere('location', 'like', '%' . $request->search . '%');
        });
    }

    // Location filter - exact match
    if ($request->filled('location')) {
        $query->where('location', $request->location);
    }

    // Price filter - price ya starting_price dono check
    if ($request->filled('price')) {
        $query->where('starting_price', '<=', $request->price);
    }

    // Sorting
    switch ($request->get('sort')) {
        case 'price_low':
            $query->orderBy('starting_price');
            break;
        case 'price_high':
            $query->orderByDesc('starting_price');
            break;
        case 'rating':
            $query->orderBy('rating', 'DESC');
            break;
        default:
            $query->latest();
    }

    // Database se unique locations lein (sirf active hotels ki)
    $locations = Hotel::where('status', 1)
        ->select('location')
        ->distinct()
        ->orderBy('location')
        ->pluck('location');

    $hotels = $query->with(['rooms' => fn ($rooms) => $rooms->where('status', true)])
        ->paginate(9)->withQueryString();
    $hotelAvailability = [];

    if (! empty($dates['check_in']) && ! empty($dates['check_out'])) {
        $rooms = $hotels->getCollection()->flatMap(fn ($hotel) => $hotel->rooms);
        $roomAvailability = app(RoomAvailability::class)->forRooms(
            $rooms,
            $dates['check_in'],
            $dates['check_out']
        );

        foreach ($hotels as $hotel) {
            $hotelAvailability[$hotel->id] = [
                'total' => 0,
                'available' => 0,
            ];
            foreach ($hotel->rooms as $room) {
                $availability = $roomAvailability[$room->id] ?? null;
                if ($availability) {
                    $hotelAvailability[$hotel->id]['total'] += $availability['total'];
                    $hotelAvailability[$hotel->id]['available'] += $availability['available'];
                }
            }
        }
    }

    return view('frontend.hotels.index', compact('hotels', 'locations', 'hotelAvailability'));
}

    /*
    |--------------------------------------------------------------------------
    | Hotel Details
    |--------------------------------------------------------------------------
    */

public function hotelDetails(Request $request, $id)
{
    $hotel = Hotel::where('status', 1)->findOrFail($id);
    $hotel->load(['rooms' => fn ($query) => $query
        ->where('status', true)
        ->with('roomType')]);
    $hasRoomInventory = $hotel->rooms()->exists();

    // Pehle same city ke hotels dhoondho
    $relatedHotels = Hotel::where('id', '!=', $hotel->id)
        ->where('status', 1);

    // If city exists, filter by city
    if($hotel->city) {
        $relatedHotels = $relatedHotels->where('city', $hotel->city);
    }

    $relatedHotels = $relatedHotels->latest()->take(4)->get();

    // Agar same city mein hotels nahi mile, to sab se latest lao
    if($relatedHotels->isEmpty()) {
        $relatedHotels = Hotel::where('id', '!=', $hotel->id)
            ->where('status', 1)
            ->latest()
            ->take(4)
            ->get();
    }

    return view('frontend.hotels.show', compact('hotel', 'relatedHotels', 'hasRoomInventory'));
}

public function hotelAvailability(Request $request, Hotel $hotel)
{
    abort_unless($hotel->status, 404);

    $validated = $request->validate([
        'check_in' => ['required', 'date', 'after_or_equal:today'],
        'check_out' => ['required', 'date', 'after:check_in'],
    ]);

    $rooms = $hotel->rooms()
        ->where('status', true)
        ->with('roomType')
        ->get();
    $availability = app(RoomAvailability::class)->forRooms(
        $rooms,
        $validated['check_in'],
        $validated['check_out']
    );

    return response()->json([
        'check_in' => Carbon::parse($validated['check_in'])->toDateString(),
        'check_out' => Carbon::parse($validated['check_out'])->toDateString(),
        'rooms' => $rooms->map(fn ($room) => [
            'id' => $room->id,
            'room_type' => $room->roomType?->name ?? $room->room_name,
            'name' => $room->room_name,
            ...($availability[$room->id] ?? [
                'total' => (int) $room->total_rooms,
                'booked' => 0,
                'reserved' => 0,
                'available' => (int) $room->total_rooms,
                'daily' => [],
            ]),
        ])->values(),
    ]);
}

    /*
    |--------------------------------------------------------------------------
    | Packages
    |--------------------------------------------------------------------------
    */

   public function packages(Request $request)
{
    $query = Package::where('status',1);

    if($request->filled('search'))
    {
        $query->where('package_name','like','%'.$request->search.'%');
    }

    if($request->filled('destination'))
    {
        $query->where('destination',$request->destination);
    }

    if($request->filled('price'))
    {
        $query->where('price','<=',$request->price);
    }

    $packages = $query->latest()->paginate(9)->withQueryString();

    return view('frontend.packages.index',compact('packages'));
}

    /*
    |--------------------------------------------------------------------------
    | Package Details
    |--------------------------------------------------------------------------
    */

public function packageDetails($id)
{
    $package = Package::where('status', 1)->findOrFail($id);

    $relatedPackages = Package::where('id','!=',$package->id)
        ->where('status',1)
        ->latest()
        ->take(4)
        ->get();

    return view('frontend.packages.show', compact(
        'package',
        'relatedPackages'
    ));
}

    /*
    |--------------------------------------------------------------------------
    | Gallery
    |--------------------------------------------------------------------------
    */

    public function gallery()
    {
        $galleries = Gallery::latest()
            ->paginate(12);

        return view('frontend.gallery', compact('galleries'));
    }

    /*
    |--------------------------------------------------------------------------
    | About
    |--------------------------------------------------------------------------
    */

    public function about()
    {
        $testimonials = Testimonial::where('status', 1)
            ->latest()
            ->take(6)
            ->get();

        return view('frontend.about', compact('testimonials'));
    }

    /*
    |--------------------------------------------------------------------------
    | Contact
    |--------------------------------------------------------------------------
    */

    public function contact()
    {
        return view('frontend.contact');
    }
}
