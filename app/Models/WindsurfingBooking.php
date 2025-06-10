<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WindsurfingBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'windsurfing_session_id',
        'booking_date',
        'participants_count',
        'total_price',
        'status',
        'special_requirements'
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'total_price' => 'decimal:2',
        'participants_count' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function session()
    {
        return $this->belongsTo(WindsurfingSession::class, 'windsurfing_session_id');
    }
} 