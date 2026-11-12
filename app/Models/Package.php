<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [

        'package_name',

        'destination',

        'duration',

        'price',

        'discount_price',
        'gst_category',
        'gst_percentage',

        'max_person',

        'thumbnail',

        'description',

        'includes',

        'excludes',

        'itinerary',

        'featured',

        'status'

    ];

    protected $casts = [

        'featured'=>'boolean',

        'status'=>'boolean',

        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'gst_percentage' => 'decimal:2',

    ];

    public function bookings()
    {
        return $this->hasMany(BookingPackage::class);
    }

    public function getBookingPriceAttribute(): float
    {
        $price = (float) $this->price;
        $discountPrice = (float) $this->discount_price;

        return $discountPrice > 0 && $discountPrice < $price
            ? $discountPrice
            : $price;
    }
}
