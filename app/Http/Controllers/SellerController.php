<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SecondHandPart;
use App\Models\Order;

class SellerController extends Controller
{
    public function dashboard()
    {
        $seller = Auth::user();
        $parts = SecondHandPart::where('seller_id', $seller->id)->get();
        $orders = Order::whereHas('part', function ($query) use ($seller) {
            $query->where('seller_id', $seller->id);
        })->with(['customer', 'part'])->orderBy('order_date', 'desc')->get();
        return view('sellers.dashboard', compact('seller', 'parts', 'orders'));
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
        $part->status = 'Available';
        $part->condition = $validated['condition'];
        $part->category = $validated['category'];
        $part->seller_id = Auth::user()->id;
        $part->listing_date = now();

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

        return redirect()->route('sellers.dashboard')->with('success', 'Part listed successfully!');
    }

    public function editPart($id)
    {
        $part = SecondHandPart::findOrFail($id);
        return view('sellers.edit', compact('part'));
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

    public function updateOrderStatus(Request $request, $id)
{
    $order = Order::whereHas('part', function ($query) {
        $query->where('seller_id', Auth::id());
    })->findOrFail($id);

    // Handle checkboxes (unchecked = 0)
    $isAccepted = $request->has('is_accepted') ? 1 : 0;
    $isShipped = $request->has('is_shipped') ? 1 : 0;

    // Enforce workflow: Cannot ship if not accepted
    if ($isShipped && !$isAccepted && !$order->is_accepted) {
        return redirect()->route('sellers.dashboard')
            ->with('error', 'Order must be accepted before it can be shipped.')
            ->withInput();
    }

    // Prevent shipping if verify_product is true and not verified
    if ($isShipped && $order->verify_product && !$order->is_verified) {
        return redirect()->route('sellers.dashboard')
            ->with('error', 'Cannot ship until admin verifies the product.')
            ->withInput();
    }

    $order->update([
        'is_accepted' => $isAccepted,
        'is_shipped' => $isShipped,
    ]);

    // Update the part status if shipped
    if ($isShipped) {
        $order->part->update(['status' => 'Sold']);
    }

    return redirect()->route('sellers.dashboard')
        ->with('success', 'Order status updated successfully.');
}
}