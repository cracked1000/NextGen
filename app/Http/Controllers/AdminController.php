<?php

namespace App\Http\Controllers;

use App\Models\SecondHandPart;
use App\Models\User;
use App\Models\Order;
use App\Models\QuotationAction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // Statistics
        $totalParts = SecondHandPart::count();
        $totalSellers = User::where('role', 'seller')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalSales = Order::where('status', 'Completed')->sum('total');

        // Queries
        $allUsersQuery = User::select('id', 'first_name', 'last_name', 'email', 'role', 'created_at')
            ->orderBy('created_at', 'desc');
        $partsQuery = SecondHandPart::with('seller')->orderBy('listing_date', 'desc');
        $ordersQuery = Order::with(['part.seller', 'customer'])->orderBy('order_date', 'desc');
        $quotationActionsQuery = QuotationAction::with('user')->orderBy('created_at', 'desc');

        // Date filtering
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
            $allUsersQuery->whereBetween('created_at', [$startDate, $endDate]);
            $partsQuery->whereBetween('listing_date', [$startDate, $endDate]);
            $ordersQuery->whereBetween('order_date', [$startDate, $endDate]);
            $quotationActionsQuery->whereBetween('created_at', [$startDate, $endDate]);
            $totalSales = Order::where('status', 'Completed')
                ->whereBetween('order_date', [$startDate, $endDate])
                ->sum('total');
        }

        // Search functionality
        $search = $request->input('search', '');
        if ($search) {
            $allUsersQuery->where(function ($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            });
            $partsQuery->where('part_name', 'like', "%{$search}%");
            $ordersQuery->where(function ($query) use ($search) {
                $query->where('id', 'like', "%{$search}%")
                      ->orWhereHas('part', function ($q) use ($search) {
                          $q->where('part_name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('customer', function ($q) use ($search) {
                          $q->where('email', 'like', "%{$search}%");
                      });
            });
            $quotationActionsQuery->where(function ($query) use ($search) {
                $query->where('quotation_number', 'like', "%{$search}%")
                      ->orWhere('source', 'like', "%{$search}%")
                      ->orWhere('status', 'like', "%{$search}%")
                      ->orWhere('special_notes', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q) use ($search) {
                          $q->where('email', 'like', "%{$search}%");
                      });
            });
        }

        // Paginate results
        $allUsers = $allUsersQuery->paginate(5, ['*'], 'users_page');
        $parts = $partsQuery->paginate(5, ['*'], 'parts_page');
        $orders = $ordersQuery->paginate(5, ['*'], 'orders_page');
        $quotationActions = $quotationActionsQuery->paginate(5, ['*'], 'quotations_page');

        // Additional data
        $sellers = User::where('role', 'seller')->get(['id', 'first_name', 'last_name']);
        $customers = User::where('role', 'customer')->get(['id', 'first_name', 'last_name']);
        $pendingParts = SecondHandPart::where('status', 'pending')->with('seller')->get();
        $verificationRequests = Order::where('verify_product', true)
            ->where('is_verified', false)
            ->with(['part.seller', 'customer'])
            ->get();

        return view('admin.dashboard', compact(
            'totalParts',
            'totalSellers',
            'totalCustomers',
            'totalSales',
            'sellers',
            'customers',
            'parts',
            'orders',
            'pendingParts',
            'verificationRequests',
            'allUsers',
            'quotationActions',
            'startDate',
            'endDate',
            'search'
        ));
    }

    public function addSeller(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'seller',
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Seller added successfully.');
    }

    public function editSeller(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $seller = User::findOrFail($id);
        $seller->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Seller updated successfully.');
    }

    public function deleteSeller($id)
    {
        $seller = User::findOrFail($id);
        $seller->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Seller deleted successfully.');
    }

    public function addCustomer(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Customer added successfully.');
    }

    public function editCustomer(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $customer = User::findOrFail($id);
        $customer->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Customer updated successfully.');
    }

    public function deleteCustomer($id)
    {
        $customer = User::findOrFail($id);
        $customer->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Customer deleted successfully.');
    }

    public function addPart(Request $request)
    {
        $request->validate([
            'part_name' => 'required|string|max:255',
            'seller_id' => 'required|exists:users,id',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|in:New,Used',
            'category' => 'nullable|string|max:255',
            'image1' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['part_name', 'seller_id', 'price', 'condition', 'category']);
        $data['status'] = 'pending';
        $data['listing_date'] = now();

        if ($request->hasFile('image1')) {
            $data['image1'] = $request->file('image1')->store('parts', 'public');
        }

        SecondHandPart::create($data);
        return redirect()->route('admin.dashboard')->with('success', 'Part added successfully.');
    }

    public function editPart(Request $request, $id)
    {
        $request->validate([
            'part_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|in:New,Used',
            'image1' => 'nullable|image|max:2048',
        ]);

        $part = SecondHandPart::findOrFail($id);
        $data = $request->only(['part_name', 'price', 'condition']);

        if ($request->hasFile('image1')) {
            if ($part->image1) {
                Storage::disk('public')->delete($part->image1);
            }
            $data['image1'] = $request->file('image1')->store('parts', 'public');
        }

        $part->update($data);
        return redirect()->route('admin.dashboard')->with('success', 'Part updated successfully.');
    }

    public function deletePart($id)
    {
        $part = SecondHandPart::findOrFail($id);
        if ($part->image1) {
            Storage::disk('public')->delete($part->image1);
        }
        $part->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Part deleted successfully.');
    }

    public function approvePart($id)
    {
        $part = SecondHandPart::findOrFail($id);
        $part->update(['status' => 'Available']);
        return redirect()->route('admin.dashboard')->with('success', 'Part approved successfully.');
    }

    public function declinePart($id)
    {
        $part = SecondHandPart::findOrFail($id);
        $part->update(['status' => 'Declined']);
        return redirect()->route('admin.dashboard')->with('success', 'Part declined successfully.');
    }

    public function updateVerificationStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate([
            'is_verified' => 'required|boolean',
        ]);

        $order->update([
            'is_verified' => $request->is_verified,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Order verification status updated successfully.');
    }

    public function updateQuotationStatus(Request $request, $id)
    {
        $quotation = QuotationAction::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Build Pending,Build in Progress,Completed',
            'special_notes' => 'nullable|string|max:1000',
        ]);

        $quotation->update([
            'status' => $request->input('status'),
            'special_notes' => $request->input('special_notes'),
        ]);

        return redirect()->route('admin.dashboard', [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'search' => $request->input('search'),
        ])->with('success', 'Quotation status and notes updated successfully.');
    }

    public function deleteQuotation($id)
    {
        $quotation = QuotationAction::findOrFail($id);
        $quotation->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Quotation deleted successfully.');
    }

    public function exportQuotationActions(Request $request)
    {
        $quotationActionsQuery = QuotationAction::with('user');

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
            $quotationActionsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $quotationActions = $quotationActionsQuery->get();

        $csvData = [];
        $csvData[] = ['Quotation Number', 'Source', 'User Email', 'Total Price', 'Components', 'Status', 'Special Notes', 'Created At'];

        foreach ($quotationActions as $action) {
            $components = "CPU: " . ($action->build_details['components']['cpu']['name'] ?? 'N/A') . "\n" .
                         "Motherboard: " . ($action->build_details['components']['motherboard']['name'] ?? 'N/A') . "\n" .
                         "GPU: " . ($action->build_details['components']['gpu']['name'] ?? 'N/A') . "\n" .
                         "RAM: " . ($action->build_details['components']['ram']['name'] ?? 'N/A') . "\n" .
                         "Storage: " . ($action->build_details['components']['storage']['name'] ?? 'N/A') . "\n" .
                         "Power Supply: " . ($action->build_details['components']['power_supply']['name'] ?? 'N/A');

            $csvData[] = [
                $action->quotation_number,
                $action->source,
                $action->user ? $action->user->email : 'Guest',
                number_format($action->build_details['total_price'] ?? 0, 2),
                $components,
                $action->status,
                $action->special_notes ?? 'N/A',
                $action->created_at->format('Y-m-d H:i:s'),
            ];
        }

        $filename = 'quotation_actions_' . now()->format('Ymd_His') . '.csv';
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);
        exit;
    }

    public function getQuotationDetails($id)
    {
        $quotation = QuotationAction::with('user')->findOrFail($id);

        $buildDetails = $quotation->build_details ?? [];
        $components = $buildDetails['components'] ?? [];

        return response()->json([
            'quotation_number' => $quotation->quotation_number,
            'source' => $quotation->source,
            'user_email' => $quotation->user ? $quotation->user->email : 'Guest',
            'total_price' => number_format($buildDetails['total_price'] ?? 0, 2),
            'components' => [
                'cpu' => $components['cpu']['name'] ?? 'Not found',
                'motherboard' => $components['motherboard']['name'] ?? 'Not found',
                'gpu' => $components['gpu']['name'] ?? 'Not found',
                'ram' => $components['ram']['name'] ?? 'Not found',
                'storage' => $components['storage']['name'] ?? 'Not found',
                'power_supply' => $components['power_supply']['name'] ?? 'Not found',
            ],
            'status' => $quotation->status,
            'special_notes' => $quotation->special_notes ?? 'No notes',
            'created_at' => $quotation->created_at->format('Y-m-d H:i:s'),
        ]);
    }
}