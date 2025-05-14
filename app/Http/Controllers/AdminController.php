<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cpu;
use App\Models\Gpu;
use App\Models\Ram;
use App\Models\User;
use App\Models\Order;
use App\Models\Storage;
use App\Models\Technician;
use App\Models\Motherboard;
use App\Models\PowerSupply;
use Illuminate\Http\Request;
use App\Models\SecondHandPart;

use App\Models\QuotationAction;
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

        $totalCpus = Cpu::count();
        $totalMotherboards = Motherboard::count();
        $totalGpus = Gpu::count();
        $totalRams = Ram::count();
        $totalStorages = Storage::count();
        $totalPowerSupplies = PowerSupply::count();

        // Fetch technicians for the Technician Network section
        $technicians = Technician::paginate(10);

        // Queries
        $allUsersQuery = User::select('id', 'first_name', 'last_name', 'email', 'role', 'created_at')
            ->orderBy('created_at', 'desc');
        $partsQuery = SecondHandPart::with('seller')->orderBy('listing_date', 'desc');
        $ordersQuery = Order::with(['part.seller', 'customer'])->orderBy('order_date', 'desc');
        $quotationActionsQuery = QuotationAction::with('user')->orderBy('created_at', 'desc');

        $cpusQuery = Cpu::query()->orderBy('id', 'desc');
        $motherboardsQuery = Motherboard::query()->orderBy('id', 'desc');
        $gpusQuery = Gpu::query()->orderBy('id', 'desc');
        $ramsQuery = Ram::query()->orderBy('id', 'desc');
        $storagesQuery = Storage::query()->orderBy('id', 'desc');
        $powerSuppliesQuery = PowerSupply::query()->orderBy('id', 'desc');

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
                      ->orWhere('special_notes', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q) use ($search) {
                          $q->where('email', 'like', "%{$search}%");
                      });
            });

            // Search for components
            $cpusQuery->where('name', 'like', "%{$search}%");
            $motherboardsQuery->where('name', 'like', "%{$search}%");
            $gpusQuery->where('name', 'like', "%{$search}%");
            $ramsQuery->where('name', 'like', "%{$search}%");
            $storagesQuery->where('name', 'like', "%{$search}%");
            $powerSuppliesQuery->where('name', 'like', "%{$search}%");
        }

        // Paginate results
        $allUsers = $allUsersQuery->paginate(5, ['*'], 'users_page');
        $parts = $partsQuery->paginate(5, ['*'], 'parts_page');
        $orders = $ordersQuery->paginate(5, ['*'], 'orders_page');
        $quotationActions = $quotationActionsQuery->paginate(5, ['*'], 'quotations_page');

        $cpus = $cpusQuery->paginate(5, ['*'], 'cpus_page');
        $motherboards = $motherboardsQuery->paginate(5, ['*'], 'motherboards_page');
        $gpus = $gpusQuery->paginate(5, ['*'], 'gpus_page');
        $rams = $ramsQuery->paginate(5, ['*'], 'rams_page');
        $storages = $storagesQuery->paginate(5, ['*'], 'storages_page');
        $powerSupplies = $powerSuppliesQuery->paginate(5, ['*'], 'power_supplies_page');

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
            'totalCpus',
            'totalMotherboards',
            'totalGpus',
            'totalRams',
            'totalStorages',
            'totalPowerSupplies',
            'technicians',
            'sellers',
            'customers',
            'parts',
            'orders',
            'pendingParts',
            'verificationRequests',
            'allUsers',
            'quotationActions',
            'cpus',
            'motherboards',
            'gpus',
            'rams',
            'storages',
            'powerSupplies',
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

    // CPU Management
    public function addCpu(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'socket_type' => 'required|string|max:50',
            'power_requirement' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        Cpu::create($request->only(['name', 'socket_type', 'power_requirement', 'price']));
        return redirect()->route('admin.dashboard')->with('success', 'CPU added successfully.');
    }

    public function editCpu(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'socket_type' => 'required|string|max:50',
            'power_requirement' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $cpu = Cpu::findOrFail($id);
        $cpu->update($request->only(['name', 'socket_type', 'power_requirement', 'price']));
        return redirect()->route('admin.dashboard')->with('success', 'CPU updated successfully.');
    }

    public function deleteCpu($id)
    {
        $cpu = Cpu::findOrFail($id);
        $cpu->delete();
        return redirect()->route('admin.dashboard')->with('success', 'CPU deleted successfully.');
    }

    // Motherboard Management
    public function addMotherboard(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'socket_type' => 'required|string|max:50',
            'ram_type' => 'required|string|in:DDR4,DDR5',
            'ram_speed' => 'required|numeric|min:0',
            'form_factor' => 'required|string|in:ATX,Micro ATX,Mini ITX',
            'ram_slots' => 'required|integer|min:1',
            'sata_slots' => 'required|integer|min:0',
            'm2_slots' => 'required|integer|min:0',
            'm2_nvme_support' => 'required|boolean',
            'pcie_version' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        Motherboard::create($request->all());
        return redirect()->route('admin.dashboard')->with('success', 'Motherboard added successfully.');
    }

    public function editMotherboard(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'socket_type' => 'required|string|max:50',
            'ram_type' => 'required|string|in:DDR4,DDR5',
            'ram_speed' => 'required|numeric|min:0',
            'form_factor' => 'required|string|in:ATX,Micro ATX,Mini ITX',
            'ram_slots' => 'required|integer|min:1',
            'sata_slots' => 'required|integer|min:0',
            'm2_slots' => 'required|integer|min:0',
            'm2_nvme_support' => 'required|boolean',
            'pcie_version' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $motherboard = Motherboard::findOrFail($id);
        $motherboard->update($request->all());
        return redirect()->route('admin.dashboard')->with('success', 'Motherboard updated successfully.');
    }

    public function deleteMotherboard($id)
    {
        $motherboard = Motherboard::findOrFail($id);
        $motherboard->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Motherboard deleted successfully.');
    }

    // GPU Management
    public function addGpu(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pcie_version' => 'required|numeric|min:0',
            'power_requirement' => 'required|numeric|min:0',
            'length' => 'required|numeric|min:0',
            'height' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        Gpu::create($request->all());
        return redirect()->route('admin.dashboard')->with('success', 'GPU added successfully.');
    }

    public function editGpu(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pcie_version' => 'required|numeric|min:0',
            'power_requirement' => 'required|numeric|min:0',
            'length' => 'required|numeric|min:0',
            'height' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $gpu = Gpu::findOrFail($id);
        $gpu->update($request->all());
        return redirect()->route('admin.dashboard')->with('success', 'GPU updated successfully.');
    }

    public function deleteGpu($id)
    {
        $gpu = Gpu::findOrFail($id);
        $gpu->delete();
        return redirect()->route('admin.dashboard')->with('success', 'GPU deleted successfully.');
    }

    // RAM Management
    public function addRam(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'ram_type' => 'required|string|in:DDR4,DDR5',
            'ram_speed' => 'required|numeric|min:0',
            'capacity' => 'required|numeric|min:0',
            'stick_count' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        Ram::create($request->all());
        return redirect()->route('admin.dashboard')->with('success', 'RAM added successfully.');
    }

    public function editRam(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'ram_type' => 'required|string|in:DDR4,DDR5',
            'ram_speed' => 'required|numeric|min:0',
            'capacity' => 'required|numeric|min:0',
            'stick_count' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $ram = Ram::findOrFail($id);
        $ram->update($request->all());
        return redirect()->route('admin.dashboard')->with('success', 'RAM updated successfully.');
    }

    public function deleteRam($id)
    {
        $ram = Ram::findOrFail($id);
        $ram->delete();
        return redirect()->route('admin.dashboard')->with('success', 'RAM deleted successfully.');
    }

    // Storage Management
    public function addStorage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:M.2,SATA',
            'is_nvme' => 'required|boolean',
            'capacity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        Storage::create($request->all());
        return redirect()->route('admin.dashboard')->with('success', 'Storage added successfully.');
    }

    public function editStorage(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:M.2,SATA',
            'is_nvme' => 'required|boolean',
            'capacity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $storage = Storage::findOrFail($id);
        $storage->update($request->all());
        return redirect()->route('admin.dashboard')->with('success', 'Storage updated successfully.');
    }

    public function deleteStorage($id)
    {
        $storage = Storage::findOrFail($id);
        $storage->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Storage deleted successfully.');
    }

    // Power Supply Management
    public function addPowerSupply(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'wattage' => 'required|numeric|min:0',
            'form_factor' => 'required|string|in:ATX,SFX',
            'price' => 'required|numeric|min:0',
        ]);

        PowerSupply::create($request->all());
        return redirect()->route('admin.dashboard')->with('success', 'Power Supply added successfully.');
    }

    public function editPowerSupply(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'wattage' => 'required|numeric|min:0',
            'form_factor' => 'required|string|in:ATX,SFX',
            'price' => 'required|numeric|min:0',
        ]);

        $powerSupply = PowerSupply::findOrFail($id);
        $powerSupply->update($request->all());
        return redirect()->route('admin.dashboard')->with('success', 'Power Supply updated successfully.');
    }

    public function deletePowerSupply($id)
    {
        $powerSupply = PowerSupply::findOrFail($id);
        $powerSupply->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Power Supply deleted successfully.');
    }

    public function storeTechnician(Request $request)
    {
        $request->validate([
            'district' => 'required|string|max:255',
            'town' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
        ]);

        Technician::create([
            'district' => $request->district,
            'town' => $request->town,
            'name' => $request->name,
            'contact_number' => $request->contact_number,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Technician added successfully.');
    }

    public function updateTechnician(Request $request, $id)
    {
        $request->validate([
            'district' => 'required|string|max:255',
            'town' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
        ]);

        $technician = Technician::findOrFail($id);
        $technician->update([
            'district' => $request->district,
            'town' => $request->town,
            'name' => $request->name,
            'contact_number' => $request->contact_number,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Technician updated successfully.');
    }

    public function destroyTechnician($id)
    {
        $technician = Technician::findOrFail($id);
        $technician->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Technician deleted successfully.');
    }

}