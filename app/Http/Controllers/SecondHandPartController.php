<?php

namespace App\Http\Controllers;

use App\Models\SecondHandPart;
use App\Models\Order;
use App\Mail\PurchaseConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class SecondHandPartController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'part_name' => 'required|string|max:255',
            'seller_id' => 'required|exists:users,id,role,seller',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:Available,Sold',
            'condition' => 'nullable|in:New,Used',
            'description' => 'nullable|string',
            'image1' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'image2' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'image3' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'category' => 'nullable|string|max:255',
        ]);

        try {
            $image1Path = $request->file('image1') ? $request->file('image1')->store('secondhand_parts', 'public') : null;
            $image2Path = $request->file('image2') ? $request->file('image2')->store('secondhand_parts', 'public') : null;
            $image3Path = $request->file('image3') ? $request->file('image3')->store('secondhand_parts', 'public') : null;

            SecondHandPart::create([
                'part_name' => $validated['part_name'],
                'seller_id' => $validated['seller_id'],
                'price' => $validated['price'],
                'status' => $validated['status'],
                'condition' => $validated['condition'] ?? 'Used',
                'description' => $validated['description'],
                'image1' => $image1Path,
                'image2' => $image2Path,
                'image3' => $image3Path,
                'listing_date' => now(),
                'category' => $validated['category'],
            ]);

            return redirect()->route('secondhand.index')->with('success', 'Second-hand part listed successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to store second-hand part', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to list part. Please try again.')->withInput();
        }
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

        $user = Auth::user();
        if (!$user || $user->role !== 'customer') {
            return redirect()->route('secondhand.show', $part->id)
                ->with('error', 'You must be logged in as a customer to buy a part.');
        }

        return view('secondhand.buy', ['part' => $part, 'customer' => $user]);
    }

    public function buy(Request $request, $id)
    {
        Log::info('Buy method started', ['part_id' => $id, 'user_id' => Auth::id()]);

        $startTime = microtime(true);
        $user = Auth::user();

        if (!$user || $user->role !== 'customer') {
            Log::warning('Unauthorized buy attempt', ['user_id' => $user?->id, 'role' => $user?->role]);
            return redirect()->route('secondhand.show', $id)
                ->with('error', 'You must be logged in as a customer to buy a part.');
        }

        // Use a transaction to prevent race conditions
        return DB::transaction(function () use ($request, $id, $user, $startTime) {
            $part = SecondHandPart::lockForUpdate()->findOrFail($id);

            if ($part->status !== 'Available') {
                Log::warning('Part not available', ['part_id' => $id, 'status' => $part->status]);
                return redirect()->route('secondhand.show', $id)
                    ->with('error', 'This part is no longer available for purchase.');
            }

            // Validate input
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone_number' => 'nullable|string|max:20|regex:/^[\+]?[0-9\s\-]*$/',
                'country' => 'required|string|max:100',
                'province' => 'required|string|max:100',
                'district' => 'required|string|max:100',
                'Zipcode' => 'nullable|string|max:20',
                'payment_option' => 'required|in:Credit Card,Debit Card',
                'verify_product' => 'required|in:0,1',
                'shipping_charges' => 'required|numeric|min:0',
                'total_cost_hidden' => 'required|numeric|min:0',
                'payment_method' => 'required|string',
            ]);

            Log::info('Validation passed', ['validated' => $validated]);

            // Verify total cost
            $componentPrice = $part->price;
            $verifyCost = $validated['verify_product'] ? $componentPrice * 0.10 : 0;
            $shippingCharges = floatval($validated['shipping_charges']);
            $expectedTotal = $componentPrice + $verifyCost + $shippingCharges;

            if (abs($expectedTotal - floatval($validated['total_cost_hidden'])) > 0.01) {
                Log::warning('Total cost mismatch', [
                    'expected' => $expectedTotal,
                    'received' => $validated['total_cost_hidden']
                ]);
                return redirect()->back()->with('error', 'Invalid total cost. Please try again.')->withInput();
            }

            // Construct shipping address
            $shippingAddress = implode(', ', array_filter([
                $validated['district'],
                $validated['province'],
                $validated['country'],
                $validated['Zipcode'],
            ]));

            // Process Stripe payment
            Stripe::setApiKey(env('STRIPE_SECRET', ''));

            try {
                $paymentIntent = PaymentIntent::create([
                    'amount' => round($expectedTotal * 100), // Convert to cents
                    'currency' => 'lkr',
                    'payment_method' => $validated['payment_method'],
                    'confirmation_method' => 'manual',
                    'confirm' => true,
                    'return_url' => route('secondhand.buy.success', $part->id),
                    'metadata' => [
                        'part_id' => $part->id,
                        'customer_id' => $user->id,
                        'order_total' => $expectedTotal
                    ]
                ]);

                Log::info('PaymentIntent created', ['payment_intent_id' => $paymentIntent->id, 'status' => $paymentIntent->status]);

                if ($paymentIntent->status === 'requires_action') {
                    Log::info('Redirecting for 3D Secure authentication');
                    return response()->json(['redirect_url' => $paymentIntent->next_action->redirect_to_url->url]);
                }

                // Create order
                $order = Order::create([
                    'customer_id' => $user->id,
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
                    'total' => $expectedTotal,
                    'status' => 'Completed',
                    'shipping_address' => $shippingAddress,
                    'payment_status' => 'Paid',
                    'order_date' => now(),
                ]);

                // Mark part as sold
                $part->status = 'Sold';
                $part->save();

                // Send confirmation email
                try {
                    Mail::to($user->email)->queue(new PurchaseConfirmation($order, $part, $user));
                    Log::info('Purchase confirmation email queued', ['email' => $user->email]);
                } catch (\Exception $e) {
                    Log::error('Failed to queue confirmation email', ['error' => $e->getMessage()]);
                }

                Log::info('Purchase completed', ['order_id' => $order->id, 'duration' => microtime(true) - $startTime]);

                return redirect()->route('secondhand.buy.success', $part->id)
                    ->with('success', 'Purchase completed successfully! A confirmation email has been sent.');
            } catch (\Stripe\Exception\ApiErrorException $e) {
                Log::error('Stripe payment failed', ['error' => $e->getMessage()]);
                return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage())->withInput();
            } catch (\Exception $e) {
                Log::error('Purchase failed', ['error' => $e->getMessage()]);
                return redirect()->back()->with('error', 'An error occurred. Please try again.')->withInput();
            }
        });
    }

    public function buyPayPal(Request $request, $id)
    {
        Log::info('PayPal buy initiated', ['part_id' => $id, 'user_id' => Auth::id()]);

        $user = Auth::user();
        if (!$user || $user->role !== 'customer') {
            return redirect()->route('secondhand.show', $id)
                ->with('error', 'You must be logged in as a customer to buy a part.');
        }

        $part = SecondHandPart::findOrFail($id);
        if ($part->status !== 'Available') {
            return redirect()->route('secondhand.show', $id)
                ->with('error', 'This part is no longer available for purchase.');
        }

        // Validate input (minimal for PayPal redirection)
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'shipping_charges' => 'required|numeric|min:0',
            'total_cost_hidden' => 'required|numeric|min:0',
        ]);

        // Note: Implement PayPal SDK or redirect logic here
        // For demonstration, return an error as PayPal isn't fully implemented
        Log::warning('PayPal payment not implemented');
        return redirect()->back()->with('error', 'PayPal payments are not currently supported. Please use a card.')->withInput();
    }

    public function buySuccess($id)
    {
        $part = SecondHandPart::findOrFail($id);
        return redirect()->route('secondhand.show', $part->id)
            ->with('success', 'Purchase completed successfully!');
    }

    public function showSellForm()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'seller') {
            return redirect()->route('login')->with('error', 'Please log in as a seller to sell a part.');
        }
        return view('secondhand.sell');
    }

    public function sell(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'seller') {
            return redirect()->route('login')->with('error', 'Please log in as a seller to sell a part.');
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

        try {
            $part = new SecondHandPart();
            $part->part_name = $validated['part_name'];
            $part->description = $validated['description'];
            $part->price = $validated['price'];
            $part->status = $validated['status'];
            $part->condition = $validated['condition'];
            $part->category = $validated['category'];
            $part->seller_id = $user->id;
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

            return redirect()->route('sellers.dashboard')->with('success', 'Part submitted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to sell part', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to submit part. Please try again.')->withInput();
        }
    }

    public function create()
    {
        $sellers = \App\Models\User::where('role', 'seller')->get();
        return view('secondhand.create', compact('sellers'));
    }
}