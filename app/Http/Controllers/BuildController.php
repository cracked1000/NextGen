<?php

namespace App\Http\Controllers;

use App\Models\Build;
use App\Models\Cpu;
use App\Models\Motherboard;
use App\Models\Gpu;
use App\Models\Ram;
use App\Models\Storage;
use App\Models\PowerSupply;
use Illuminate\Http\Request;

class BuildController extends Controller
{
    public function index()
    {
        $cpus = Cpu::with('prices.retailer')->get();
        return view('build', compact('cpus'));
    }

    public function getCompatibleMotherboards($cpuId)
    {
        $cpu = Cpu::findOrFail($cpuId);
        $motherboards = Motherboard::where('socket_type', $cpu->socket_type)
            ->with('prices.retailer')
            ->get();
        return response()->json($motherboards);
    }

    public function getCompatibleGpus($cpuId, $motherboardId)
    {
        $motherboard = Motherboard::findOrFail($motherboardId);
        $gpus = Gpu::where('pcie_version', '<=', $motherboard->pcie_version)
            ->with('prices.retailer')
            ->get();
        return response()->json($gpus);
    }

    public function getCompatibleRams(Request $request, $motherboardId)
    {
        \Log::info('Fetching compatible RAM for Motherboard ID: ' . $motherboardId);
        $motherboard = Motherboard::find($motherboardId);
        if (!$motherboard) {
            \Log::error('Motherboard not found: ' . $motherboardId);
            return response()->json(['error' => 'Motherboard not found'], 404);
        }
        \Log::info('Motherboard found: ' . $motherboard->name . ', RAM Type: ' . $motherboard->ram_type . ', RAM Speed: ' . $motherboard->ram_speed . ', RAM Slots: ' . $motherboard->ram_slots);
        
        $selectedRamId = $request->query('selected_ram_id');
        $rams = Ram::where('ram_type', $motherboard->ram_type)
            ->where('ram_speed', '<=', $motherboard->ram_speed)
            ->with('prices.retailer')
            ->get();
        \Log::info('RAMs after type and speed filter: ' . $rams->count());

        // Filter RAMs based on available slots
        $availableSlots = $motherboard->ram_slots;
        if ($selectedRamId) {
            $selectedRam = Ram::find($selectedRamId);
            if ($selectedRam) {
                $availableSlots -= $selectedRam->stick_count;
                \Log::info('Selected RAM ID: ' . $selectedRamId . ', Stick Count: ' . $selectedRam->stick_count . ', Available Slots: ' . $availableSlots);
            }
        } else {
            \Log::info('No selected RAM, Available Slots: ' . $availableSlots);
        }

        $rams = $rams->filter(function ($ram) use ($availableSlots) {
            return $ram->stick_count <= $availableSlots;
        });
        \Log::info('Compatible RAMs after slot filter: ' . $rams->count());

        return response()->json($rams);
    }

    public function getCompatibleStorages(Request $request, $ramId)
    {
        $motherboardId = $request->query('motherboard_id'); // Pass motherboard_id from frontend
        $selectedStorageIds = $request->query('selected_storage_ids', []); // Array of already selected storage IDs

        if (!$motherboardId) {
            return response()->json(['error' => 'Motherboard ID is required'], 400);
        }

        $motherboard = Motherboard::findOrFail($motherboardId);
        $storages = Storage::with('prices.retailer')->get();

        // Track used slots
        $usedSataSlots = 0;
        $usedM2Slots = 0;
        if (!empty($selectedStorageIds)) {
            $selectedStorages = Storage::whereIn('id', $selectedStorageIds)->get();
            foreach ($selectedStorages as $storage) {
                if ($storage->type === 'SATA') {
                    $usedSataSlots++;
                } elseif ($storage->type === 'M.2') {
                    $usedM2Slots++;
                }
            }
        }

        $availableSataSlots = $motherboard->sata_slots - $usedSataSlots;
        $availableM2Slots = $motherboard->m2_slots - $usedM2Slots;

        $storages = $storages->filter(function ($storage) use ($motherboard, $availableSataSlots, $availableM2Slots) {
            if ($storage->type === 'SATA' && $availableSataSlots > 0) {
                return true;
            }
            if ($storage->type === 'M.2' && $availableM2Slots > 0) {
                return !$storage->is_nvme || $motherboard->m2_nvme_support;
            }
            return false;
        });

        return response()->json($storages);
    }

    public function getCompatiblePowerSupplies(Request $request, $storageId)
    {
        $gpuId = $request->query('gpu_id');
        $cpuId = $request->query('cpu_id'); // Pass CPU ID
        $motherboardId = $request->query('motherboard_id'); // Pass Motherboard ID

        if (!$gpuId || !$cpuId || !$motherboardId) {
            return response()->json(['error' => 'GPU ID, CPU ID, and Motherboard ID are required'], 400);
        }

        $gpu = Gpu::findOrFail($gpuId);
        $cpu = Cpu::findOrFail($cpuId);
        $motherboard = Motherboard::findOrFail($motherboardId);

        // Improved power supply calculation
        $totalPower = 0;
        $totalPower += $gpu->power_requirement; // GPU power
        $totalPower += $cpu->power_requirement; // CPU power
        $totalPower += 50; // Base power for motherboard
        $totalPower += 50; // Additional power for RAM, storage, etc.
        $totalPower *= 1.3; // 30% buffer instead of static 200W

        // GPU size constraints (example limits for motherboard form factor)
        $maxGpuLength = 0;
        $maxGpuHeight = 0;
        if ($motherboard->form_factor === 'ATX') {
            $maxGpuLength = 350; // mm
            $maxGpuHeight = 150; // mm
        } elseif ($motherboard->form_factor === 'mATX') {
            $maxGpuLength = 300;
            $maxGpuHeight = 130;
        } elseif ($motherboard->form_factor === 'ITX') {
            $maxGpuLength = 250;
            $maxGpuHeight = 110;
        }

        if ($gpu->length > $maxGpuLength || $gpu->height > $maxGpuHeight) {
            return response()->json(['error' => 'GPU is too large for the selected motherboard form factor'], 400);
        }

        // Filter power supplies
        $powerSupplies = PowerSupply::where('wattage', '>=', $totalPower)
            ->where('form_factor', $motherboard->form_factor) // Form factor compatibility
            ->with('prices.retailer')
            ->get();

        return response()->json($powerSupplies);
    }

    public function saveBuild(Request $request)
{
    $validated = $request->validate([
        'cpu_id' => 'required|exists:cpus,id',
        'motherboard_id' => 'required|exists:motherboards,id',
        'gpu_id' => 'required|exists:gpus,id',
        'ram_id' => 'required|exists:rams,id',
        'storage_id' => 'required|exists:storages,id',
        'power_supply_id' => 'required|exists:power_supplies,id',
        'total_price' => 'required|numeric|min:0',
        'name' => 'nullable|string|max:255', // Optional build name
    ]);

    $build = Build::create([
        'user_id' => auth()->id(), // Get the authenticated user's ID
        'name' => $validated['name'] ? $validated['name'] : 'My Build ' . now()->format('Y-m-d H:i:s'), // Default name if none provided
        'cpu_id' => $validated['cpu_id'],
        'motherboard_id' => $validated['motherboard_id'],
        'gpu_id' => $validated['gpu_id'],
        'ram_id' => $validated['ram_id'],
        'storage_id' => $validated['storage_id'],
        'power_supply_id' => $validated['power_supply_id'],
        'total_price' => $validated['total_price'],
    ]);

    return redirect()->route('customer.profile')->with('success', 'Build saved successfully!');
}
}