<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\Build;
use App\Models\QuotationAction;

class CustomerController extends Controller
{
    public function profile()
    {
        $customer = auth()->user();
        if (!$customer) {
            \Log::error('No authenticated user found in CustomerController@profile');
            return redirect()->route('login')->with('error', 'You must be logged in to view your profile.');
        }

        \Log::info('Fetching builds for user ID: ' . $customer->id);

        try {
            $builds = Build::where('user_id', $customer->id)
                ->with([
                    'cpu' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'motherboard' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'gpu' => function ($query) {
                        $query->select('id', 'name');
                    },
                    'rams' => function ($query) {
                        $query->select('rams.id', 'rams.name');
                    },
                    'storages' => function ($query) {
                        $query->select('storages.id', 'storages.name');
                    },
                    'powerSupply' => function ($query) {
                        $query->select('id', 'name');
                    },
                ])
                ->paginate(5);

            \Log::info('Builds retrieved: ' . $builds->count());
        } catch (\Exception $e) {
            \Log::error('Error fetching builds in CustomerController@profile: ' . $e->getMessage());
            $builds = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 5);
        }

        try {
            $quotations = QuotationAction::where('user_id', $customer->id)
                ->orderBy('created_at', 'desc')
                ->get();

            \Log::info('Quotations retrieved for user ID ' . $customer->id . ': ' . $quotations->count());
        } catch (\Exception $e) {
            \Log::error('Error fetching quotations in CustomerController@profile: ' . $e->getMessage());
            $quotations = collect();
        }

        return view('customer.profile', compact('customer', 'builds', 'quotations'));
    }

    public function deleteBuild($id)
    {
        $build = Build::where('user_id', auth()->id())->findOrFail($id);
        $build->delete();

        return redirect()->route('customer.profile')->with('success', 'Build deleted successfully!');
    }

    public function editProfile()
    {
        $customer = Auth::user();
        return view('customer.edit-profile', compact('customer'));
    }

    public function updateProfile(Request $request)
    {
        $customer = Auth::user();

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'zipcode' => 'nullable|string|max:10',
            'phone_number' => 'required|string|max:15',
            'optional_phone_number' => 'nullable|string|max:15',
            'email' => 'required|email|max:255|unique:users,email,' . $customer->id,
            'status' => 'required|in:active,inactive',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($customer->profile_photo) {
                Storage::disk('public')->delete($customer->profile_photo);
            }
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $validatedData['profile_photo'] = $path;
        }

        $customer->update([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'address' => $validatedData['address'],
            'zipcode' => $validatedData['zipcode'],
            'phone_number' => $validatedData['phone_number'],
            'optional_phone_number' => $validatedData['optional_phone_number'],
            'email' => $validatedData['email'],
            'status' => $validatedData['status'],
            'profile_photo' => $validatedData['profile_photo'] ?? $customer->profile_photo,
            'password' => !empty($validatedData['password']) ? Hash::make($validatedData['password']) : $customer->password,
        ]);

        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully!');
    }

    public function orders()
    {
        $customer = Auth::user();
        $orders = Order::where('customer_id', $customer->id)
            ->with(['part.seller'])
            ->orderBy('order_date', 'desc')
            ->get();
        return view('customer.orders', compact('orders', 'customer'));
    }

    public function markAsReceived(Request $request, $id)
    {
        $order = Order::where('customer_id', Auth::id())->findOrFail($id);

        // Only allow marking as received if the order is shipped
        if (!$order->is_shipped) {
            return redirect()->route('customer.orders')->with('error', 'Order cannot be marked as received until it is shipped.');
        }

        $order->update(['is_received' => true]);

        return redirect()->route('customer.orders')->with('success', 'Order marked as received successfully.');
    }
}