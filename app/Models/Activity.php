<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'duration_minutes',
        'difficulty_level',
        'price',
        'max_participants',
        'available_spots',
        'image_url',
        'status',
        'tour_id',
    ];

    protected $casts = [
        'available_spots' => 'integer',
        'max_participants' => 'integer',
        'price' => 'float',
        'duration_minutes' => 'integer',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
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
