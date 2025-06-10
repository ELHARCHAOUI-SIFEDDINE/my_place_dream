<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Activity::with(['tour'])->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Activity $activity)
    {
        return $activity->load(['tour', 'reviews.user']);
    }

    /**
     * Display user's activities.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userActivities(Request $request)
    {
        return $request->user()->activities()->with(['tour'])->get();
    }

    /**
     * Display user's booked activities.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userBookedActivities(Request $request)
    {
        return $request->user()->bookedActivities()->with(['tour'])->get();
    }
}
