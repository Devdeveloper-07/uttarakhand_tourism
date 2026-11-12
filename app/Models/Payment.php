<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'user_id',
        'user_email',
        'transaction_id',
        'gateway_name',
        'gateway_order_id',
        'gateway_payment_id',
        'gateway_signature',
        'payment_method',
        'currency',
        'base_amount',
        'gst_percentage',
        'gst_amount',
        'gst_category',
        'amount',
        'payment_status',
        'payment_date',
        'meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'base_amount' => 'decimal:2',
        'gst_percentage' => 'decimal:2',
        'gst_amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'meta' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
