<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        return $product->reviews()->with('user')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string',
        ]);

        // Vérifier si l'utilisateur a déjà réservé ce produit
        $hasBooked = Auth::user()->bookings()
            ->where('product_id', $product->id)
            ->where('status', 'confirmed')
            ->exists();

        if (!$hasBooked) {
            return response()->json([
                'message' => 'You must have a confirmed booking to review this product.'
            ], 403);
        }

        // Vérifier si l'utilisateur a déjà laissé un avis
        $existingReview = $product->reviews()
            ->where('user_id', Auth::id())
            ->exists();

        if ($existingReview) {
            return response()->json([
                'message' => 'You have already reviewed this product.'
            ], 422);
        }

        $review = $product->reviews()->create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json($review->load('user'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        $this->authorize('update', $review);

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string',
        ]);

        $review->update($request->only(['rating', 'comment']));

        return response()->json($review->load('user'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);
        $review->delete();
        return response()->json(null, 204);
    }

    public function getUserReviews()
    {
        return Auth::user()->reviews()->with('product')->get();
    }
}
