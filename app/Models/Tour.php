<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'duration',
        'price',
        'max_participants',
        'available_spots',
        'difficulty_level',
        'image_url',
        'status',
    ];

    protected $casts = [
        'available_spots' => 'integer',
        'max_participants' => 'integer',
        'price' => 'float',
        'duration' => 'integer',
    ];

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
