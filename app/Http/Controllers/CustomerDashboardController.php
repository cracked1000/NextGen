<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Build;
use App\Models\User;
use App\Models\Order;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        // Ensure only customers can access this dashboard
        $customer = Auth::user();
        if (!$customer || $customer->role !== 'customer') {
            return redirect()->route('login')->with('error', 'You must be logged in as a customer to access the dashboard.');
        }

        // Fetch the customer's builds with related components, paginated
        $builds = Build::where('user_id', $customer->id)
            ->with(['cpu', 'motherboard', 'gpu', 'rams', 'storages', 'powerSupply'])
            ->paginate(5);

        // Fetch the customer's quotations
        $quotations = Quotation::where('user_id', $customer->id)->get();

        // Fetch the customer's orders
        $orders = Order::where('customer_id', $customer->id)
            ->with('part')
            ->get();

        return view('customer.dashboard', compact('customer', 'builds', 'quotations', 'orders'));
    }

    

    public function viewBuild($buildId)
    {
        $build = Build::with([
            'cpu.prices.retailer',
            'motherboard.prices.retailer',
            'gpu.prices.retailer',
            'ram.prices.retailer',
            'storage.prices.retailer',
            'powerSupply.prices.retailer'
        ])->findOrFail($buildId);

        // Ensure the build belongs to the logged-in user
        if ($build->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('customer.build_details', compact('build'));
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
}