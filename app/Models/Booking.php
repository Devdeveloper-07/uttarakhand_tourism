<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'booking_no',
        'item_type',
        'item_id',
        'check_in',
        'check_out',
        'travel_date',
        'guest',
        'room_count',
        'contact_name',
        'contact_phone',
        'contact_email',
        'total_amount',
        'taxable_amount',
        'gst_percentage',
        'gst_amount',
        'gst_category',
        'gst_breakdown',
        'checkout_token',
        'grand_total',
        'currency',
        'payment_status',
        'booking_status',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'travel_date' => 'date',
        'total_amount' => 'decimal:2',
        'taxable_amount' => 'decimal:2',
        'gst_percentage' => 'decimal:2',
        'gst_amount' => 'decimal:2',
        'gst_breakdown' => 'array',
        'grand_total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rooms()
    {
        return $this->hasMany(BookingRoom::class);
    }

    public function packages()
    {
        return $this->hasMany(BookingPackage::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function item(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'item_type', 'item_id');
    }
}
