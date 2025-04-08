<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\Build;

class CustomerController extends Controller
{
    public function profile()
    {
        $customer = auth()->user(); // Assuming the authenticated user is the customer
        if (!$customer) {
            return redirect()->route('login')->with('error', 'You must be logged in to view your profile.');
        }

        try {
            $builds = Build::where('user_id', $customer->id)
                ->with(['cpu', 'motherboard', 'gpu', 'ram', 'storage', 'powerSupply'])
                ->paginate(5); // Paginate with 5 builds per page
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error fetching builds in CustomerController@profile: ' . $e->getMessage());
            // Set $builds to an empty paginated collection to avoid undefined variable error
            $builds = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 5);
        }

        return view('customer.profile', compact('customer', 'builds'));
    }

    public function deleteBuild($id)
    {
        $build = Build::where('user_id', auth()->id())->findOrFail($id);
        $build->delete();

        return redirect()->route('customer.profile')->with('success', 'Build deleted successfully!');
    }

    public function editProfile()
    {
        $customer = Auth::user(); // Use the default guard
        return view('customer.edit-profile', compact('customer'));
    }

    public function updateProfile(Request $request)
    {
        $customer = Auth::user(); // Use the default guard

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'Zipcode' => 'nullable|string|max:10',
            'phone_number' => 'required|string|max:15',
            'optional_phone_number' => 'nullable|string|max:15',
            'email' => 'required|email|max:255|unique:users,email,' . $customer->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $customer->first_name = $validatedData['first_name'];
        $customer->last_name = $validatedData['last_name'];
        $customer->address = $validatedData['address'];
        $customer->Zipcode = $validatedData['Zipcode'];
        $customer->phone_number = $validatedData['phone_number'];
        $customer->optional_phone_number = $validatedData['optional_phone_number'];
        $customer->email = $validatedData['email'];

        if (!empty($validatedData['password'])) {
            $customer->password = Hash::make($validatedData['password']);
        }

        $customer->save();

        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully!');
    }

    public function orders()
    {
        $customer = Auth::user(); // Use the default guard
        $orders = Order::where('customer_id', $customer->id)
            ->with('part')
            ->orderBy('order_date', 'desc')
            ->get();
        return view('customer.orders', compact('orders', 'customer'));
    }
}