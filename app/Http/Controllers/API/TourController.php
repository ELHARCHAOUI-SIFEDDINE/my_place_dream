<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\Request;

class TourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Tour::with(['activities'])->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Tour $tour)
    {
        return $tour->load(['activities', 'reviews.user']);
    }

    /**
     * Display user's tours.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userTours(Request $request)
    {
        return $request->user()->tours()->with(['activities'])->get();
    }

    /**
     * Display user's booked tours.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userBookedTours(Request $request)
    {
        return $request->user()->bookedTours()->with(['activities'])->get();
    }
}
