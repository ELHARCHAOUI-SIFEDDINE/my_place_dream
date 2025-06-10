<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController as AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\WindsurfingSessionController;
use App\Http\Controllers\API\WindsurfingBookingController;
use App\Http\Controllers\API\TourController;
use App\Http\Controllers\API\ActivityController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['api'])->group(function () {
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

// Routes publiques
Route::post('/register', [\App\Http\Controllers\API\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\API\AuthController::class, 'login']);

// Routes des produits accessibles publiquement
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/search', [ProductController::class, 'search']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/products/{product}/reviews', [ReviewController::class, 'index']);

// Tours routes
Route::get('/tours', [TourController::class, 'index']);
Route::get('/tours/{tour}', [TourController::class, 'show']);
Route::get('/tours/{tour}/activities', [TourController::class, 'activities']);

// Activities routes
Route::get('/activities', [ActivityController::class, 'index']);
Route::get('/activities/{activity}', [ActivityController::class, 'show']);

// Routes protégées
Route::middleware(['auth:sanctum'])->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Products (admin only)
    Route::middleware('admin')->group(function () {
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    });

    // Product availability and booking routes
    Route::get('/products/{product}/availability', [ProductController::class, 'checkAvailability']);
    Route::post('/bookings', [BookingController::class, 'store']);

    // Bookings
    Route::get('/bookings', [BookingController::class, 'index']);

    // Windsurfing
    Route::get('/windsurfing-sessions', [WindsurfingSessionController::class, 'index']);
    Route::get('/windsurfing-sessions/{sessionId}/availability', [WindsurfingSessionController::class, 'checkAvailability']);
    Route::post('/windsurfing/book', [WindsurfingBookingController::class, 'bookSession']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/{booking}', [BookingController::class, 'show']);
    Route::put('/bookings/{booking}', [BookingController::class, 'update']);
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy']);

    // Payments
    Route::post('/bookings/{booking}/payments', [PaymentController::class, 'store']);
    Route::get('/payments/{payment}', [PaymentController::class, 'show']);
    Route::get('/user/payments', [PaymentController::class, 'getUserPayments']);

    // Reviews
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store']);
    Route::put('/reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);
    Route::get('/user/reviews', [ReviewController::class, 'getUserReviews']);

    // User-specific tours and activities
    Route::get('/user/tours', [TourController::class, 'userTours']);
    Route::get('/user/booked-tours', [TourController::class, 'userBookedTours']);
    Route::get('/user/activities', [ActivityController::class, 'userActivities']);
    Route::get('/user/booked-activities', [ActivityController::class, 'userBookedActivities']);

    // Windsurfing Session Routes
    Route::get('/windsurfing-sessions', [WindsurfingSessionController::class, 'index']);
    Route::get('/windsurfing-sessions/{session}', [WindsurfingSessionController::class, 'show']);

    // Windsurfing Booking Routes (protected)
    Route::get('/bookings', [WindsurfingBookingController::class, 'index']);
    Route::post('/bookings', [WindsurfingBookingController::class, 'store']);
    Route::get('/bookings/{booking}', [WindsurfingBookingController::class, 'show']);
    Route::post('/bookings/{booking}/cancel', [WindsurfingBookingController::class, 'cancel']);
});

Route::get('/test', function () {
    return response()->json([
        'message' => 'Connexion réussie avec le backend Laravel !',
        'status' => 'success'
    ]);
});
