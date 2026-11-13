<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomType;
use App\Services\RoomAvailability;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\UploadedFile;
use RuntimeException;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->validate([
            'hotel_id' => ['nullable', 'integer', 'exists:hotels,id'],
            'check_in' => ['nullable', 'required_with:check_out', 'date', 'after_or_equal:today'],
            'check_out' => ['nullable', 'required_with:check_in', 'date', 'after:check_in'],
        ]);
        $checkIn = Carbon::parse($filters['check_in'] ?? now()->toDateString());
        $checkOut = Carbon::parse($filters['check_out'] ?? now()->addDay()->toDateString());
        $hotelFilter = $filters['hotel_id'] ?? null;

        $query = Room::with('hotel', 'roomType')
            ->when($hotelFilter, fn ($query) => $query->where('hotel_id', $hotelFilter));
        $rooms = (clone $query)
            ->latest()
            ->paginate(10);
        $allRooms = (clone $query)->get();
        $availability = app(RoomAvailability::class)->forRooms($allRooms, $checkIn, $checkOut);
        $roomAvailability = collect($availability);
        $totalInventory = $allRooms->sum('total_rooms');
        $dailyTotals = [];
        foreach ($availability as $roomAvailabilityByDate) {
            foreach ($roomAvailabilityByDate['daily'] as $night) {
                $dailyTotals[$night['date']] ??= [
                    'total' => 0,
                    'booked' => 0,
                    'reserved' => 0,
                    'available' => 0,
                ];
                foreach (array_keys($dailyTotals[$night['date']]) as $key) {
                    $dailyTotals[$night['date']][$key] += $night[$key];
                }
            }
        }
        $availableInventory = $dailyTotals === []
            ? $totalInventory
            : min(array_column($dailyTotals, 'available'));
        $occupiedInventory = $dailyTotals === []
            ? 0
            : max(array_column($dailyTotals, 'booked'));
        $reservedInventory = $dailyTotals === []
            ? 0
            : max(array_column($dailyTotals, 'reserved'));
        $occupancyPercentage = $dailyTotals === []
            ? 0
            : max(array_map(
                fn ($night) => $night['total'] > 0
                    ? ($night['booked'] + $night['reserved']) / $night['total'] * 100
                    : 0,
                $dailyTotals
            ));
        $occupancyPercentage = round($occupancyPercentage, 1);
        $inventory = [
            'hotels' => $allRooms->pluck('hotel_id')->unique()->count(),
            'room_types' => $allRooms->pluck('room_type_id')->unique()->count(),
            'total' => $totalInventory,
            'occupied' => $occupiedInventory,
            'available' => $availableInventory,
            'reserved' => $reservedInventory,
            'occupancy' => $occupancyPercentage,
            'availability' => $availability,
            'check_in' => $checkIn->toDateString(),
            'check_out' => $checkOut->toDateString(),
        ];
        $hotels = Hotel::query()
            ->whereHas('rooms')
            ->orderBy('hotel_name')
            ->get(['id', 'hotel_name']);

        return view('admin.rooms.index', compact('rooms', 'inventory', 'hotelFilter', 'hotels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hotels = Hotel::where('status', 1)->get();

        $roomTypes = RoomType::where('status', 1)->get();

        return view('admin.rooms.create', compact('hotels', 'roomTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_id' => ['required', 'exists:hotels,id'],
            'room_name' => ['required', 'string', 'max:120'],
            'room_number' => ['required', 'string', 'max:80', 'unique:rooms,room_number'],
            'room_type_id' => ['required', 'exists:room_types,id'],
            'total_rooms' => ['required', 'integer', 'min:1', 'max:1000'],
            'capacity' => ['required', 'integer', 'min:1', 'max:50'],
            'price_per_night' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'description' => ['nullable', 'string', 'max:5000'],
            'amenities_text' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'max:5120'],
            'status' => ['required', 'boolean'],
        ]);
        unset($validated['image']);
        $validated['amenities'] = $this->parseAmenities($validated['amenities_text'] ?? '');
        unset($validated['amenities_text']);
        $imagePath = $request->hasFile('image')
            ? $this->storeRoomImage($request->file('image'))
            : null;

        Room::create([
            ...$validated,
            'available_rooms' => $validated['total_rooms'],
            'image_path' => $imagePath,
        ]);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Room Added Successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        $room->load('hotel', 'roomType');
        $availability = app(RoomAvailability::class)->forRooms(
            new \Illuminate\Database\Eloquent\Collection([$room]),
            now()->toDateString(),
            now()->addDay()->toDateString()
        )[$room->id];

        return view('admin.rooms.show', compact('room', 'availability'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        $hotels = Hotel::where('status', 1)->get();

        $roomTypes = RoomType::where('status', 1)->get();

        return view('admin.rooms.edit', compact(
            'room',
            'hotels',
            'roomTypes'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'hotel_id' => ['required', 'exists:hotels,id'],
            'room_name' => ['required', 'string', 'max:120'],
            'room_number' => ['required', 'string', 'max:80', Rule::unique('rooms', 'room_number')->ignore($room->id)],
            'room_type_id' => ['required', 'exists:room_types,id'],
            'total_rooms' => ['required', 'integer', 'min:1', 'max:1000'],
            'capacity' => ['required', 'integer', 'min:1', 'max:50'],
            'price_per_night' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'description' => ['nullable', 'string', 'max:5000'],
            'amenities_text' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'max:5120'],
            'status' => ['required', 'boolean'],
        ]);
        unset($validated['image']);
        $validated['amenities'] = $this->parseAmenities($validated['amenities_text'] ?? '');
        unset($validated['amenities_text']);

        $imagePath = $room->image_path;
        if ($request->hasFile('image')) {
            $newImagePath = $this->storeRoomImage($request->file('image'));
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $newImagePath;
        }

        $room->update([
            ...$validated,
            'amenities' => $validated['amenities'] ?? [],
            'available_rooms' => $validated['total_rooms'],
            'image_path' => $imagePath,
        ]);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Room Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        if ($room->bookingRooms()->exists()) {
            return back()->withErrors([
                'room' => 'This room inventory has booking history. Disable it instead of deleting it.',
            ]);
        }

        if ($room->image_path) {
            Storage::disk('public')->delete($room->image_path);
        }
        $room->delete();

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Room Deleted Successfully.');
    }

    private function parseAmenities(string $amenities): array
    {
        return collect(explode(',', $amenities))
            ->map(fn (string $amenity) => trim($amenity))
            ->filter()
            ->unique()
            ->take(20)
            ->values()
            ->all();
    }

    private function storeRoomImage(UploadedFile $image): string
    {
        $path = $image->store('rooms', 'public');
        if (! is_string($path) || $path === '') {
            throw new RuntimeException('The room image could not be saved.');
        }

        return $path;
    }
}
