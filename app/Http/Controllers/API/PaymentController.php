<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Booking $booking)
    {
        $this->authorize('create', [Payment::class, $booking]);

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:card,paypal,bank_transfer',
        ]);

        if ($booking->payment()->exists()) {
            return response()->json([
                'message' => 'Payment already exists for this booking.'
            ], 422);
        }

        $payment = $booking->payment()->create([
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'status' => 'success',
        ]);

        $booking->update(['status' => 'confirmed']);

        return response()->json($payment, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);
        return $payment->load('booking.product');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getUserPayments()
    {
        return Payment::whereHas('booking', function ($query) {
            $query->where('user_id', Auth::id());
        })->with('booking.product')->get();
    }
}
