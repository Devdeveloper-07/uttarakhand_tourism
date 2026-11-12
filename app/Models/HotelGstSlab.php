<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelGstSlab extends Model
{
    protected $fillable = [
        'code',
        'name',
        'min_tariff',
        'max_tariff',
        'gst_percentage',
    ];

    protected $casts = [
        'min_tariff' => 'decimal:2',
        'max_tariff' => 'decimal:2',
        'gst_percentage' => 'decimal:2',
    ];
}
