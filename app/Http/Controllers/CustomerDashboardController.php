<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Build;
use App\Models\User;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        // Ensure only customers can access this dashboard
        if (auth()->user()->role !== 'customer') {
            abort(403, 'Unauthorized access. This dashboard is for customers only.');
        }

        // Fetch the logged-in customer
        $customer = auth()->user();

        // Fetch the customer's builds with related components, paginated
        $builds = $customer->builds()
            ->with(['cpu', 'motherboard', 'gpu', 'ram', 'storage', 'powerSupply'])
            ->paginate(5); // Paginate with 5 builds per page

        return view('customer.profile', compact('customer', 'builds'));
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