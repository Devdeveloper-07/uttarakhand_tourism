<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Hotel;
use App\Models\Package;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{


    public function index()
    {
        $featuredHotels = Hotel::where('status', 1)
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

        return view('frontend.home', compact(
            'featuredHotels',
            'featuredPackages',
            'galleries',
            'testimonials'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Hotels
    |--------------------------------------------------------------------------
    */

public function hotels(Request $request)
{
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
        $query->where(function ($q) use ($request) {
            $q->where('starting_price', '<=', $request->price)
              ->orWhere('starting_price', '<=', $request->price);
        });
    }

    // Sorting
    switch ($request->get('sort')) {
        case 'price_low':
            $query->orderByRaw('COALESCE(price, starting_price, 0) ASC');
            break;
        case 'price_high':
            $query->orderByRaw('COALESCE(price, starting_price, 0) DESC');
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

    $hotels = $query->paginate(9)->withQueryString();

    return view('frontend.hotels.index', compact('hotels', 'locations'));
}

    /*
    |--------------------------------------------------------------------------
    | Hotel Details
    |--------------------------------------------------------------------------
    */

public function hotelDetails($id)
{
    $hotel = Hotel::findOrFail($id);

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

    return view('frontend.hotels.show', compact('hotel', 'relatedHotels'));
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
    $package = Package::findOrFail($id);

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
