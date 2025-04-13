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

        $allUsers = User::select('id', 'first_name', 'last_name', 'email', 'role', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $quotationActions = QuotationAction::with('user')
            ->where('action', 'continue_with_build')
            ->orderBy('created_at', 'desc')
            ->get();

        $sellers = User::where('role', 'seller')->get();
        $customers = User::where('role', 'customer')->get();
        $parts = SecondHandPart::with('seller')->where('status', 'Available')->get();
        $pendingParts = SecondHandPart::with('seller')->where('status', 'pending')->get();

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $parts = SecondHandPart::whereBetween('created_at', [$startDate, $endDate])
                ->with('seller')
                ->get();
            $totalSales = SecondHandPart::where('status', 'Available')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('price');

            $allUsers = User::whereBetween('created_at', [$startDate, $endDate])
                ->select('id', 'first_name', 'last_name', 'email', 'role', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get();

            $quotationActions = QuotationAction::with('user')
                ->where('action', 'continue_with_build')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc')
                ->get();
        }

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

    public function exportQuotationActions(Request $request)
    {
        $quotationActionsQuery = QuotationAction::with('user')
            ->where('action', 'continue_with_build');

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
            $quotationActionsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $quotationActions = $quotationActionsQuery->get();

        $csvData = [];
        $csvData[] = ['User Name', 'Email', 'Build Name', 'Total Price', 'Components', 'Action Taken At'];

        foreach ($quotationActions as $action) {
            $components = "CPU: " . ($action->build_details['components']['cpu'] ?? 'N/A') . "\n" .
                         "Motherboard: " . ($action->build_details['components']['motherboard'] ?? 'N/A') . "\n" .
                         "GPU: " . ($action->build_details['components']['gpu'] ?? 'N/A') . "\n" .
                         "RAM: " . implode(', ', $action->build_details['components']['rams'] ?? []) . "\n" .
                         "Storage: " . implode(', ', $action->build_details['components']['storages'] ?? []) . "\n" .
                         "Power Supply: " . ($action->build_details['components']['power_supply'] ?? 'N/A');

            $csvData[] = [
                $action->user->first_name . ' ' . $action->user->last_name,
                $action->user->email,
                $action->build_details['name'] ?? 'N/A',
                number_format($action->build_details['total_price'] ?? 0, 2),
                $components,
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

    // ... (rest of the methods remain unchanged)
}