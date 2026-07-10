<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable = [
        'hotel_name',
        'slug',
        'location',
        'city',
        'state',
        'description',
        'thumbnail',
        'starting_price',
        'rating',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'rating' => 'decimal:1',
        'starting_price' => 'decimal:2',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function images()
    {
        return $this->hasMany(HotelImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(
            Amenity::class,
            'hotel_amenity'
        );
    }
}
