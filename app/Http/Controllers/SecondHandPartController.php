<?php

namespace App\Http\Controllers;

use App\Models\SecondHandPart;
use App\Models\Purchase;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class SecondHandPartController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'part_name' => 'required|string|max:255',
            'seller_id' => 'required|exists:sellers,id',
            'price' => 'required|numeric',
            'status' => 'required|in:Available,Sold',
            'condition' => 'nullable|in:New,Used',
            'description' => 'nullable|string',
            'image1' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'image2' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'image3' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'category' => 'nullable|string|max:255',
        ]);

        $image1Path = $request->file('image1') ? $request->file('image1')->store('secondhand_parts', 'public') : null;
        $image2Path = $request->file('image2') ? $request->file('image2')->store('secondhand_parts', 'public') : null;
        $image3Path = $request->file('image3') ? $request->file('image3')->store('secondhand_parts', 'public') : null;

        $secondHandPart = new SecondHandPart();
        $secondHandPart->part_name = $request->input('part_name');
        $secondHandPart->seller_id = $request->input('seller_id');
        $secondHandPart->price = $request->input('price');
        $secondHandPart->status = $request->input('status');
        $secondHandPart->condition = $request->input('condition', 'Used');
        $secondHandPart->description = $request->input('description');
        $secondHandPart->image1 = $image1Path;
        $secondHandPart->image2 = $image2Path;
        $secondHandPart->image3 = $image3Path;
        $secondHandPart->listing_date = now();
        $secondHandPart->category = $request->input('category');
        $secondHandPart->save();

        return redirect()->route('secondhand.index')->with('success', 'Second-hand part listed successfully!');
    }

    public function index()
    {
        $parts = SecondHandPart::where('status', 'Available')->get();
        return view('secondhand.index', compact('parts'));
    }

    public function show($id)
    {
        $part = SecondHandPart::with('seller')->findOrFail($id);
        return view('secondhand.show', compact('part'));
    }

    public function showBuyForm($id)
{
    $part = SecondHandPart::with('seller')->findOrFail($id);
    if ($part->status !== 'Available') {
        return redirect()->route('secondhand.show', $part->id)
            ->with('error', 'This part is not available for purchase.');
    }

    // Get the authenticated customer
    $customer = Auth::guard('customer')->user();

    // Log to debug
    \Log::info('showBuyForm: Authenticated customer', ['customer' => $customer]);

    return view('secondhand.buy', compact('part', 'customer'));
}

    public function buy(Request $request, $id)
{
    \Log::info('Buy method started', ['id' => $id, 'request_data' => $request->all()]);

    $startTime = microtime(true); // Start timing

    $part = SecondHandPart::findOrFail($id);
    \Log::info('Part fetched', ['part_id' => $id, 'duration' => microtime(true) - $startTime]);

    if ($part->status !== 'Available') {
        \Log::warning('Part not available', ['part_id' => $id, 'status' => $part->status]);
        return redirect()->route('secondhand.show', $id)
            ->with('error', 'This part is no longer available for purchase.');
    }

    // Validate the form data
    $validationStart = microtime(true);
    try {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'Zipcode' => 'nullable|string|max:20',
            'payment_option' => 'required|in:Credit Card,Debit Card,PayPal',
            'verify_product' => 'required|in:0,1',
            'shipping_charges' => 'required|numeric|min:0',
            'total_cost_hidden' => 'required|numeric|min:0',
            'payment_method' => 'required|string', // Stripe payment method ID
        ]);
        \Log::info('Validation passed', [
            'validated_data' => $validated,
            'duration' => microtime(true) - $validationStart
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation failed', [
            'errors' => $e->errors(),
            'duration' => microtime(true) - $validationStart
        ]);
        throw $e;
    }

    // Calculate costs
    $calcStart = microtime(true);
    $componentPrice = $part->price;
    $verifyCost = $validated['verify_product'] ? $componentPrice * 0.10 : 0;
    $shippingCharges = floatval($validated['shipping_charges']);
    $totalCost = floatval($validated['total_cost_hidden']);
    \Log::info('Costs calculated', [
        'component_price' => $componentPrice,
        'verify_cost' => $verifyCost,
        'shipping_charges' => $shippingCharges,
        'total_cost' => $totalCost,
        'duration' => microtime(true) - $calcStart
    ]);

    // Construct shipping address
    $addressStart = microtime(true);
    $shippingAddress = implode(', ', array_filter([
        $validated['district'],
        $validated['province'],
        $validated['country'],
        $validated['Zipcode'],
    ]));
    \Log::info('Shipping address constructed', [
        'shipping_address' => $shippingAddress,
        'duration' => microtime(true) - $addressStart
    ]);

    // Set up Stripe
    $stripeStart = microtime(true);
    Stripe::setApiKey(env('STRIPE_SECRET'));
    \Log::info('Stripe API key set', ['duration' => microtime(true) - $stripeStart]);

    try {
        // Create a PaymentIntent
        $paymentStart = microtime(true);
        $paymentIntent = PaymentIntent::create([
            'amount' => $totalCost * 100, // Stripe expects amount in cents
            'currency' => 'lkr', // Use LKR for Sri Lankan Rupees (ensure Stripe supports this for your account)
            'payment_method' => $validated['payment_method'],
            'confirmation_method' => 'manual',
            'confirm' => true,
            'return_url' => route('secondhand.buy.success', $part->id), // Redirect URL after payment
        ]);
        \Log::info('PaymentIntent created', [
            'payment_intent_id' => $paymentIntent->id,
            'status' => $paymentIntent->status,
            'duration' => microtime(true) - $paymentStart
        ]);

        // Check if payment requires further action (e.g., 3D Secure)
        if ($paymentIntent->status === 'requires_action') {
            \Log::info('Payment requires further action, redirecting for 3D Secure');
            return redirect($paymentIntent->next_action->redirect_to_url->url);
        }

        // Payment successful, store the order
        $orderStart = microtime(true);
        $order = Order::create([
            'customer_id' => Auth::guard('customer')->id(),
            'part_id' => $part->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'country' => $validated['country'],
            'province' => $validated['province'],
            'district' => $validated['district'],
            'zipcode' => $validated['Zipcode'],
            'payment_option' => $validated['payment_option'],
            'stripe_payment_id' => $paymentIntent->id,
            'component_price' => $componentPrice,
            'verify_product' => $validated['verify_product'],
            'verify_cost' => $verifyCost,
            'shipping_charges' => $shippingCharges,
            'total' => $totalCost,
            'status' => 'Completed',
            'shipping_address' => $shippingAddress,
            'payment_status' => 'Paid',
            'order_date' => now(),
        ]);
        \Log::info('Order created', [
            'order_id' => $order->id,
            'duration' => microtime(true) - $orderStart
        ]);

        // Update part status to "Sold"
        $partUpdateStart = microtime(true);
        $part->status = 'Sold';
        $part->save();
        \Log::info('Part status updated to Sold', [
            'part_id' => $part->id,
            'duration' => microtime(true) - $partUpdateStart
        ]);

        \Log::info('Buy method completed successfully', [
            'total_duration' => microtime(true) - $startTime
        ]);

        return redirect()->route('secondhand.show', $part->id)
            ->with('success', 'Purchase completed successfully!');

    } catch (\Exception $e) {
        \Log::error('Payment failed', [
            'error' => $e->getMessage(),
            'duration' => microtime(true) - $stripeStart
        ]);
        return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
    }
}
    public function buySuccess($id)
    {
        $part = SecondHandPart::findOrFail($id);
        return redirect()->route('secondhand.show', $part->id)
            ->with('success', 'Purchase completed successfully!');
    }

    public function showSellForm()
    {
        return view('secondhand.sell');
    }

    public function sell(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        if (!$seller) {
            return redirect('/login')->with('error', 'Please log in to sell a part.');
        }

        $validated = $request->validate([
            'part_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,Available,Sold',
            'condition' => 'required|in:New,Used',
            'category' => 'nullable|in:GPU,CPU,Motherboard,RAM,Storage,PSU',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $part = new SecondHandPart();
        $part->part_name = $validated['part_name'];
        $part->description = $validated['description'];
        $part->price = $validated['price'];
        $part->status = $validated['status'];
        $part->condition = $validated['condition'];
        $part->category = $validated['category'];
        $part->seller_id = $seller->id;
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

        return redirect()->route('sellers.dashboard')->with('success', 'Part submitted for approval successfully.');
    }

    public function create()
    {
        $sellers = \App\Models\Seller::all();
        return view('secondhand.create', compact('sellers'));
    }
}