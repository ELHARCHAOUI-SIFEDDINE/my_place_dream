<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WindsurfingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'max_participants',
        'duration_minutes',
        'difficulty_level',
        'is_available'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'max_participants' => 'integer',
        'duration_minutes' => 'integer'
    ];

    public function bookings()
    {
        return $this->hasMany(WindsurfingBooking::class);
    }

    public function getAvailableSpotsAttribute()
    {
        $bookedSpots = $this->bookings()
            ->where('status', '!=', 'cancelled')
            ->sum('participants_count');
        return $this->max_participants - $bookedSpots;
    }
} 