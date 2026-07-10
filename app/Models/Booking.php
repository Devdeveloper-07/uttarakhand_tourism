<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'booking_no',
        'check_in',
        'check_out',
        'guest',
        'total_amount',
        'payment_status',
        'booking_status',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'total_amount' => 'decimal:2',
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
}
