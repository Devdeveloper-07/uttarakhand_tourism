<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'title',
        'image',
        'type',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
         'image' => 'array',
    ];

    public function getImageAttribute($value)
    {
        if (empty($value)) {
            return [];
        }

        if (is_array($value)) {
            return $value;
        }

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        return [$value];
    }

    public function getFirstImageAttribute()
    {
        return $this->image[0] ?? null;
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
