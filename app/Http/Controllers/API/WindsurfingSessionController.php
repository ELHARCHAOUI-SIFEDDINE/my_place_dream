<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\WindsurfingSession;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WindsurfingSessionController extends Controller
{
    public function index(): JsonResponse
    {
        $sessions = WindsurfingSession::where('is_available', true)
            ->with(['bookings' => function ($query) {
                $query->where('status', '!=', 'cancelled');
            }])
            ->get()
            ->map(function ($session) {
                return [
                    'id' => $session->id,
                    'title' => $session->title,
                    'description' => $session->description,
                    'price' => $session->price,
                    'max_participants' => $session->max_participants,
                    'available_spots' => $session->available_spots,
                    'duration_minutes' => $session->duration_minutes,
                    'difficulty_level' => $session->difficulty_level,
                ];
            });

        return response()->json($sessions);
    }

    public function show(WindsurfingSession $session): JsonResponse
    {
        $session->load(['bookings' => function ($query) {
            $query->where('status', '!=', 'cancelled');
        }]);

        return response()->json([
            'id' => $session->id,
            'title' => $session->title,
            'description' => $session->description,
            'price' => $session->price,
            'max_participants' => $session->max_participants,
            'available_spots' => $session->available_spots,
            'duration_minutes' => $session->duration_minutes,
            'difficulty_level' => $session->difficulty_level,
            'is_available' => $session->is_available,
        ]);
    }
} 