<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SecondHandPart;

class SellerController extends Controller
{
    public function dashboard()
    {
        $seller = Auth::user();
        $parts = SecondHandPart::where('seller_id', $seller->id)->get();
        return view('sellers.dashboard', compact('seller', 'parts'));
    }

    public function showSellForm()
    {
        return view('sellers.sell');
    }

    public function sell(Request $request)
    {
        $validated = $request->validate([
            'part_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|string|in:New,Used,Refurbished',
            'category' => 'required|string|max:255',
            'image1' => 'nullable|image|max:2048',
            'image2' => 'nullable|image|max:2048',
            'image3' => 'nullable|image|max:2048',
        ]);

        $part = new SecondHandPart();
        $part->part_name = $validated['part_name'];
        $part->description = $validated['description'];
        $part->price = $validated['price'];
        $part->status = 'pending'; // Default status for new parts
        $part->condition = $validated['condition'];
        $part->category = $validated['category'];
        $part->seller_id = Auth::user()->id; // Set the seller_id to the logged-in user's ID
        $part->listing_date = now();

        // Handle image uploads
        if ($request->hasFile('image1')) {
            $part->image1 = $request->file('image1')->store('parts', 'public');
        }
        if ($request->hasFile('image2')) {
            $part->image2 = $request->file('image2')->store('parts', 'public');
        }
        if ($request->hasFile('image3')) {
            $part->image3 = $request->file('image3')->store('parts', 'public');
        }

        $part->save(); // This is likely line 72 where the error occurred

        return redirect()->route('sellers.dashboard')->with('success', 'Part listed successfully!');
    }

    public function editPart($id)
    {
        $part = SecondHandPart::findOrFail($id);
        return view('sellers.edit_part', compact('part'));
    }

    public function updatePart(Request $request, $id)
    {
        $part = SecondHandPart::findOrFail($id);

        $validated = $request->validate([
            'part_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|string|in:New,Used,Refurbished',
            'category' => 'required|string|max:255',
            'image1' => 'nullable|image|max:2048',
            'image2' => 'nullable|image|max:2048',
            'image3' => 'nullable|image|max:2048',
        ]);

        $part->part_name = $validated['part_name'];
        $part->description = $validated['description'];
        $part->price = $validated['price'];
        $part->condition = $validated['condition'];
        $part->category = $validated['category'];

        // Handle image uploads
        if ($request->hasFile('image1')) {
            $part->image1 = $request->file('image1')->store('parts', 'public');
        }
        if ($request->hasFile('image2')) {
            $part->image2 = $request->file('image2')->store('parts', 'public');
        }
        if ($request->hasFile('image3')) {
            $part->image3 = $request->file('image3')->store('parts', 'public');
        }

        $part->save();

        return redirect()->route('sellers.dashboard')->with('success', 'Part updated successfully!');
    }

    public function deletePart($id)
    {
        $part = SecondHandPart::findOrFail($id);
        $part->delete();
        return redirect()->route('sellers.dashboard')->with('success', 'Part deleted successfully.');
    }
}