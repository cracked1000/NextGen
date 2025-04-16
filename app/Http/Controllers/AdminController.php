<?php

namespace App\Http\Controllers;

use App\Models\SecondHandPart;
use App\Models\User;
use App\Models\QuotationAction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $totalParts = SecondHandPart::count();
        $totalSellers = User::where('role', 'seller')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalSales = SecondHandPart::where('status', 'Available')->sum('price');

        $allUsersQuery = User::select('id', 'first_name', 'last_name', 'email', 'role', 'created_at')
            ->orderBy('created_at', 'desc');

        $quotationActionsQuery = QuotationAction::with('user')
            ->orderBy('created_at', 'desc');

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
            $quotationActionsQuery->whereBetween('created_at', [$startDate, $endDate]);
            $allUsersQuery->whereBetween('created_at', [$startDate, $endDate]);
            $parts = SecondHandPart::whereBetween('created_at', [$startDate, $endDate])
                ->with('seller')
                ->get();
            $totalSales = SecondHandPart::where('status', 'Available')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('price');
        } else {
            $parts = SecondHandPart::with('seller')->where('status', 'Available')->get();
        }

        // Apply search filter for quotations if present
        $search = $request->input('search');
        if ($search) {
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

        // Paginate the results
        $quotationActions = $quotationActionsQuery->paginate(5); // 5 quotations per page
        $allUsers = $allUsersQuery->paginate(5); // 5 users per page

        $sellers = User::where('role', 'seller')->get();
        $customers = User::where('role', 'customer')->get();
        $pendingParts = SecondHandPart::with('seller')->where('status', 'pending')->get();

        return view('admin.dashboard', compact(
            'totalParts',
            'totalSellers',
            'totalCustomers',
            'totalSales',
            'sellers',
            'customers',
            'parts',
            'pendingParts',
            'startDate',
            'endDate',
            'allUsers',
            'quotationActions'
        ));
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