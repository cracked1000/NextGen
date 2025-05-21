<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\QuotationAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function index()
    {
        $reviews = Review::with(['user', 'quotationAction'])->latest()->get();
        return view('reviews.index', compact('reviews'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            Log::error('User not authenticated when submitting review.');
            return redirect()->route('login')->with('error', 'You must be logged in to submit a review.');
        }

        Log::info('Form data received:', $request->all());

        $request->validate([
            'quotation_number' => 'required|string|exists:quotation_actions,quotation_number',
            'comment' => 'nullable|string',
            'rating' => 'required|numeric|min:1|max:5'
        ]);

        try {
            $currentUserId = Auth::id();
            Log::info("Authenticated user ID: {$currentUserId}");

            $quotationAction = QuotationAction::where('quotation_number', $request->quotation_number)
                ->where('user_id', $currentUserId)
                ->firstOrFail();

            Log::info('Matching quotation found:', $quotationAction->toArray());

            $review = Review::create([
                'user_id' => $currentUserId,
                'quotation_action_id' => $quotationAction->id,
                'comment' => $request->comment,
                'rating' => $request->rating
            ]);

            Log::info('Review created successfully:', $review->toArray());
        } catch (\Exception $e) {
            Log::error('Failed to create review: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to submit review. Please ensure the quotation number belongs to you.');
        }

        return redirect()->route('reviews.index')->with('success', 'Review submitted successfully!');
    }
}