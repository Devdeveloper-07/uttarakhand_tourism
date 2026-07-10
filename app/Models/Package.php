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

        'price'=>'decimal:2',

        'discount_price'=>'decimal:2'

    ];

    public function bookings()
    {
        return $this->hasMany(BookingPackage::class);
    }
}
