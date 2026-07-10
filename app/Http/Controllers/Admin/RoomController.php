<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::with('hotel', 'roomType')
            ->latest()
            ->paginate(10);

        return view('admin.rooms.index', compact('rooms'));
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
        $request->validate([
            'hotel_id' => 'required',
            'room_name' => 'required',
            'room_number' => 'required|unique:rooms,room_number',
            'room_type_id' => 'required',
            'total_rooms' => 'required|integer',
            'available_rooms' => 'required|integer',
            'capacity' => 'required|integer',
            'price_per_night' => 'required|numeric',
            'description' => 'required',
            'status' => 'required',
        ]);

        Room::create([
            'hotel_id' => $request->hotel_id,
            'room_name' => $request->room_name,
            'room_number' => $request->room_number,
            'room_type_id' => $request->room_type_id,
            'total_rooms' => $request->total_rooms,
            'available_rooms' => $request->available_rooms,
            'capacity' => $request->capacity,
            'price_per_night' => $request->price_per_night,
            'description' => $request->description,
            'status' => $request->status,
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

        return view('admin.rooms.show', compact('room'));
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
        $request->validate([
            'hotel_id' => 'required',
            'room_name' => 'required',
            'room_number' => 'required|unique:rooms,room_number,' . $room->id,
            'room_type_id' => 'required',
            'total_rooms' => 'required|integer',
            'available_rooms' => 'required|integer',
            'capacity' => 'required|integer',
            'price_per_night' => 'required|numeric',
            'description' => 'required',
            'status' => 'required',
        ]);

        $room->update([
            'hotel_id' => $request->hotel_id,
            'room_name' => $request->room_name,
            'room_number' => $request->room_number,
            'room_type_id' => $request->room_type_id,
            'total_rooms' => $request->total_rooms,
            'available_rooms' => $request->available_rooms,
            'capacity' => $request->capacity,
            'price_per_night' => $request->price_per_night,
            'description' => $request->description,
            'status' => $request->status,
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
        $room->delete();

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Room Deleted Successfully.');
    }
}
