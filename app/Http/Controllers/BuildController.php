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
use Illuminate\Support\Facades\Log;

class BuildController extends Controller
{
    public function index()
    {
        // Fetch CPUs without the prices.retailer relationship
        $cpus = Cpu::all();
        return view('build', compact('cpus'));
    }

    public function getCompatibleMotherboards($cpuId)
    {
        try {
            $cpu = Cpu::findOrFail($cpuId);
            $motherboards = Motherboard::where('socket_type', $cpu->socket_type)
                ->get();
            return response()->json($motherboards);
        } catch (\Exception $e) {
            Log::error("Error fetching compatible motherboards for CPU ID {$cpuId}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch compatible motherboards'], 500);
        }
    }

    public function getCompatibleGpus($cpuId, $motherboardId)
    {
        try {
            $motherboard = Motherboard::findOrFail($motherboardId);
            $gpus = Gpu::where('pcie_version', '<=', $motherboard->pcie_version)
                ->get();
            return response()->json($gpus);
        } catch (\Exception $e) {
            Log::error("Error fetching compatible GPUs for Motherboard ID {$motherboardId}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch compatible GPUs'], 500);
        }
    }

    public function getCompatibleRams(Request $request, $motherboardId)
    {
        try {
            Log::info('Fetching compatible RAM for Motherboard ID: ' . $motherboardId);
            $motherboard = Motherboard::find($motherboardId);
            if (!$motherboard) {
                Log::error('Motherboard not found: ' . $motherboardId);
                return response()->json(['error' => 'Motherboard not found'], 404);
            }

            Log::info('Motherboard found: ' . $motherboard->name . ', RAM Type: ' . $motherboard->ram_type . ', RAM Speed: ' . $motherboard->ram_speed . ', RAM Slots: ' . $motherboard->ram_slots);

            $selectedRamIds = $request->query('selected_ram_ids', []); // Array of selected RAM IDs
            if (!is_array($selectedRamIds)) {
                $selectedRamIds = $selectedRamIds ? [$selectedRamIds] : [];
            }

            $rams = Ram::where('ram_type', $motherboard->ram_type)
                ->where('ram_speed', '<=', $motherboard->ram_speed)
                ->get();
            Log::info('RAMs after type and speed filter: ' . $rams->count());

            // Calculate available slots
            $availableSlots = $motherboard->ram_slots;
            if (!empty($selectedRamIds)) {
                $selectedRams = Ram::whereIn('id', $selectedRamIds)->get();
                foreach ($selectedRams as $selectedRam) {
                    $availableSlots -= $selectedRam->stick_count;
                    Log::info('Selected RAM ID: ' . $selectedRam->id . ', Stick Count: ' . $selectedRam->stick_count . ', Available Slots: ' . $availableSlots);
                }
            } else {
                Log::info('No selected RAM, Available Slots: ' . $availableSlots);
            }

            $rams = $rams->filter(function ($ram) use ($availableSlots) {
                return $ram->stick_count <= $availableSlots;
            });
            Log::info('Compatible RAMs after slot filter: ' . $rams->count());

            return response()->json($rams);
        } catch (\Exception $e) {
            Log::error("Error fetching compatible RAMs for Motherboard ID {$motherboardId}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch compatible RAMs'], 500);
        }
    }

    public function getCompatibleStorages(Request $request, $ramId)
    {
        try {
            $motherboardId = $request->query('motherboard_id');
            $selectedStorageIds = $request->query('selected_storage_ids', []);

            if (!$motherboardId) {
                return response()->json(['error' => 'Motherboard ID is required'], 400);
            }

            if (!is_array($selectedStorageIds)) {
                $selectedStorageIds = $selectedStorageIds ? [$selectedStorageIds] : [];
            }

            $motherboard = Motherboard::findOrFail($motherboardId);
            $storages = Storage::all();

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
        } catch (\Exception $e) {
            Log::error("Error fetching compatible storages: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch compatible storages'], 500);
        }
    }

    public function getCompatiblePowerSupplies(Request $request, $storageId)
    {
        try {
            $gpuId = $request->query('gpu_id');
            $cpuId = $request->query('cpu_id');
            $motherboardId = $request->query('motherboard_id');

            if (!$gpuId || !$cpuId || !$motherboardId) {
                return response()->json(['error' => 'GPU ID, CPU ID, and Motherboard ID are required'], 400);
            }

            $gpu = Gpu::findOrFail($gpuId);
            $cpu = Cpu::findOrFail($cpuId);
            $motherboard = Motherboard::findOrFail($motherboardId);

            // Calculate total power requirement
            $totalPower = 0;
            $totalPower += $gpu->power_requirement ?? 0;
            $totalPower += $cpu->power_requirement ?? 0;
            $totalPower += 50; // Base power for motherboard
            $totalPower += 50; // Additional power for RAM, storage, etc.
            $totalPower *= 1.3; // 30% buffer

            // GPU size constraints based on motherboard form factor
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
                ->where('form_factor', $motherboard->form_factor)
                ->get();

            return response()->json($powerSupplies);
        } catch (\Exception $e) {
            Log::error("Error fetching compatible power supplies: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch compatible power supplies'], 500);
        }
    }

    public function saveBuild(Request $request)
    {
        try {
            $validated = $request->validate([
                'cpu_id' => 'required|exists:cpus,id',
                'motherboard_id' => 'required|exists:motherboards,id',
                'gpu_id' => 'required|exists:gpus,id',
                'ram_ids' => 'required|array', // Array of RAM IDs
                'ram_ids.*' => 'exists:rams,id',
                'storage_ids' => 'required|array', // Array of Storage IDs
                'storage_ids.*' => 'exists:storages,id',
                'power_supply_id' => 'required|exists:power_supplies,id',
                'total_price' => 'required|numeric|min:0',
                'name' => 'nullable|string|max:255',
            ]);

            // Calculate total price to verify
            $cpu = Cpu::findOrFail($validated['cpu_id']);
            $motherboard = Motherboard::findOrFail($validated['motherboard_id']);
            $gpu = Gpu::findOrFail($validated['gpu_id']);
            $rams = Ram::whereIn('id', $validated['ram_ids'])->get();
            $storages = Storage::whereIn('id', $validated['storage_ids'])->get();
            $powerSupply = PowerSupply::findOrFail($validated['power_supply_id']);

            $calculatedTotalPrice = $cpu->price +
                                   $motherboard->price +
                                   $gpu->price +
                                   $rams->sum('price') +
                                   $storages->sum('price') +
                                   $powerSupply->price;

            if (abs($calculatedTotalPrice - $validated['total_price']) > 0.01) {
                return redirect()->back()->withErrors(['total_price' => 'The provided total price does not match the calculated total.']);
            }

            // Save the build
            $build = Build::create([
                'user_id' => auth()->id(),
                'name' => $validated['name'] ?? 'My Build ' . now()->format('Y-m-d H:i:s'),
                'cpu_id' => $validated['cpu_id'],
                'motherboard_id' => $validated['motherboard_id'],
                'gpu_id' => $validated['gpu_id'],
                'power_supply_id' => $validated['power_supply_id'],
                'total_price' => $validated['total_price'],
            ]);

            // Attach RAMs and Storages (assuming pivot tables exist)
            $build->rams()->sync($validated['ram_ids']);
            $build->storages()->sync($validated['storage_ids']);

            return redirect()->route('customer.profile')->with('success', 'Build saved successfully!');
        } catch (\Exception $e) {
            Log::error("Error saving build: " . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to save build: ' . $e->getMessage()]);
        }
    }
}