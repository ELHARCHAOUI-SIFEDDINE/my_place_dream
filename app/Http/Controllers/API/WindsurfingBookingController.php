<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\WindsurfingBooking;
use App\Models\WindsurfingSession;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WindsurfingBookingController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'windsurfing_session_id' => 'required|exists:windsurfing_sessions,id',
            'booking_date' => 'required|date|after:now',
            'participants_count' => 'required|integer|min:1',
            'special_requirements' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $session = WindsurfingSession::findOrFail($request->windsurfing_session_id);

        // Check if there are enough spots available
        if ($session->available_spots < $request->participants_count) {
            return response()->json([
                'message' => 'Not enough spots available for this session'
            ], 422);
        }

        // Check if the session is available
        if (!$session->is_available) {
            return response()->json([
                'message' => 'This session is not available for booking'
            ], 422);
        }

        $booking = WindsurfingBooking::create([
            'user_id' => Auth::id(),
            'windsurfing_session_id' => $session->id,
            'booking_date' => $request->booking_date,
            'participants_count' => $request->participants_count,
            'total_price' => $session->price * $request->participants_count,
            'special_requirements' => $request->special_requirements,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Booking created successfully',
            'booking' => $booking->load('session')
        ], 201);
    }

    public function index(): JsonResponse
    {
        $bookings = WindsurfingBooking::where('user_id', Auth::id())
            ->with('session')
            ->orderBy('booking_date', 'desc')
            ->get();

        return response()->json($bookings);
    }

    public function show(WindsurfingBooking $booking): JsonResponse
    {
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($booking->load('session'));
    }

    public function cancel(WindsurfingBooking $booking): JsonResponse
    {
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($booking->status === 'cancelled') {
            return response()->json(['message' => 'Booking is already cancelled'], 422);
        }

        $booking->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Booking cancelled successfully',
            'booking' => $booking->load('session')
        ]);
    }
} 