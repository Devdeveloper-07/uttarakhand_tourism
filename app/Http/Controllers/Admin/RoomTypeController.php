<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::latest()->paginate(10);

        return view('admin.room-types.index', compact('roomTypes'));
    }

    public function create()
    {
        return view('admin.room-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:room_types,name',
        ]);

        RoomType::create($request->all());

        return redirect()
            ->route('admin.room-types.index')
            ->with('success', 'Room Type Added Successfully');
    }

    public function show(RoomType $roomType)
    {
        return view('admin.room-types.show', compact('roomType'));
    }

    public function edit(RoomType $roomType)
    {
        return view('admin.room-types.edit', compact('roomType'));
    }

    public function update(Request $request, RoomType $roomType)
    {
        $request->validate([
            'name' => 'required|unique:room_types,name,' . $roomType->id,
        ]);

        $roomType->update($request->all());

        return redirect()
            ->route('admin.room-types.index')
            ->with('success', 'Room Type Updated Successfully');
    }

    public function destroy(RoomType $roomType)
    {
        $roomType->delete();

        return redirect()
            ->back()
            ->with('success', 'Room Type Deleted Successfully');
    }
}
