<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WindsurfingSession;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WindsurfingController extends Controller
{
    public function getSessions(Request $request)
    {
        $perPage = $request->query('per_page', 6);
        $page = $request->query('page', 1);
        
        $query = WindsurfingSession::with(['reservations'])
            ->when($request->query('search'), function($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->query('difficulty'), function($query, $difficulty) {
                $query->where('difficulty', $difficulty);
            })
            ->when($request->query('min_price'), function($query, $min) {
                $query->where('price', '>=', $min);
            })
            ->when($request->query('max_price'), function($query, $max) {
                $query->where('price', '<=', $max);
            });

        $sessions = $query->paginate($perPage, ['*'], 'page', $page);
        
        return response()->json([
            'success' => true,
            'data' => $sessions
        ]);
    }

    public function checkAvailability($sessionId)
    {
        $session = WindsurfingSession::with(['reservations'])->find($sessionId);
        
        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Session not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'is_available' => $session->isAvailable(),
                'available_spots' => $session->availableSpots
            ]
        ]);
    }

    public function bookSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'session_id' => 'required|integer',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $reservation = Reservation::create([
                'user_id' => $request->user_id,
                'session_id' => $request->session_id,
                'date' => $request->date
            ]);

            return response()->json([
                'success' => true,
                'data' => $reservation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create reservation'
            ], 500);
        }
    }
}
