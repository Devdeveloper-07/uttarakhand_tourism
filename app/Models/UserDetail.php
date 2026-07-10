<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $fillable = [

        'user_id',

        'mobile',

        'image',

        'gender',

        'dob',

        'address',

        'city',

        'state',

        'country',

        'pincode',

        'status'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
