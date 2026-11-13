<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'room_name',
        'room_type_id',
        'room_number',
        'capacity',
        'total_rooms',
        'available_rooms',
        'price_per_night',
        'description',
        'amenities',
        'image_path',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'price_per_night' => 'decimal:2',
        'amenities' => 'array',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function bookingRooms()
    {
        return $this->hasMany(BookingRoom::class);
    }
}
