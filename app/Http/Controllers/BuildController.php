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
use Illuminate\Support\Facades\Mail;
use App\Mail\BuildPurchased;
use App\Models\QuotationAction;

class BuildController extends Controller
{
    public function index()
    {
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

            $selectedRamIds = $request->query('selected_ram_ids', []);
            if (!is_array($selectedRamIds)) {
                $selectedRamIds = $selectedRamIds ? [$selectedRamIds] : [];
            }

            $rams = Ram::where('ram_type', $motherboard->ram_type)
                ->where('ram_speed', '<=', $motherboard->ram_speed)
                ->get();
            Log::info('RAMs after type and speed filter: ' . $rams->count());

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

            $totalPower = 0;
            $totalPower += $gpu->power_requirement ?? 0;
            $totalPower += $cpu->power_requirement ?? 0;
            $totalPower += 50;
            $totalPower += 50;
            $totalPower *= 1.3;

            $maxGpuLength = 0;
            $maxGpuHeight = 0;
            if ($motherboard->form_factor === 'ATX') {
                $maxGpuLength = 350;
                $maxGpuHeight = 150;
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
        \Log::info('saveBuild method called with data:', $request->all());

        try {
            $validated = $request->validate([
                'cpu_id' => 'required|exists:cpus,id',
                'motherboard_id' => 'required|exists:motherboards,id',
                'gpu_id' => 'required|exists:gpus,id',
                'ram_ids' => 'required|array|min:1',
                'ram_ids.*' => 'exists:rams,id',
                'storage_ids' => 'required|array|min:1',
                'storage_ids.*' => 'exists:storages,id',
                'power_supply_id' => 'required|exists:power_supplies,id',
                'total_price' => 'required|numeric|min:0',
                'name' => 'nullable|string|max:255',
            ]);

            \Log::info('Validated data:', $validated);

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

            \Log::info('Calculated total price: ' . $calculatedTotalPrice . ', Provided total price: ' . $validated['total_price']);

            if (abs($calculatedTotalPrice - $validated['total_price']) > 0.01) {
                \Log::error('Total price mismatch. Calculated: ' . $calculatedTotalPrice . ', Provided: ' . $validated['total_price']);
                return redirect()->back()->withErrors(['total_price' => 'The provided total price does not match the calculated total.']);
            }

            $build = Build::create([
                'user_id' => auth()->id(),
                'name' => $validated['name'] ?? 'My Build ' . now()->format('Y-m-d H:i:s'),
                'cpu_id' => $validated['cpu_id'],
                'motherboard_id' => $validated['motherboard_id'],
                'gpu_id' => $validated['gpu_id'],
                'power_supply_id' => $validated['power_supply_id'],
                'total_price' => $validated['total_price'],
            ]);

            \Log::info('Build created with ID: ' . $build->id . ' for user ID: ' . auth()->id());

            $build->rams()->sync($validated['ram_ids']);
            $build->storages()->sync($validated['storage_ids']);

            \Log::info('RAMs synced for build ID: ' . $build->id, $validated['ram_ids']);
            \Log::info('Storages synced for build ID: ' . $build->id, $validated['storage_ids']);

            return redirect()->route('customer.profile')->with('success', 'Build saved successfully!');
        } catch (\Exception $e) {
            \Log::error('Error saving build: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to save build: ' . $e->getMessage());
        }
    }

    public function purchase($buildId)
    {
        \Log::info('Purchase route accessed - Build ID Parameter: ' . $buildId . ', Auth ID: ' . (auth()->id() ?? 'null'));

        if (!is_numeric($buildId) || $buildId <= 0) {
            \Log::error('Invalid Build ID: ' . $buildId);
            return redirect()->route('customer.profile')->with('error', 'Invalid build ID.');
        }

        if (!auth()->check()) {
            \Log::warning('Purchase attempt failed - No authenticated user');
            return redirect()->route('login')->with('error', 'Please log in to purchase a build.');
        }

        \Log::info('Attempting to find build with ID: ' . $buildId);
        $build = Build::with([
            'user',
            'cpu',
            'motherboard',
            'gpu',
            'rams',
            'storages',
            'powerSupply'
        ])->find($buildId);

        if (!$build) {
            \Log::error('Build not found for ID: ' . $buildId);
            return redirect()->route('customer.profile')->with('error', 'Build not found.');
        }

        if ($build instanceof \Illuminate\Database\Eloquent\Collection) {
            \Log::error('Unexpected collection returned for Build ID: ' . $buildId);
            return redirect()->route('customer.profile')->with('error', 'An error occurred while retrieving the build.');
        }

        \Log::info('Purchase attempt - Auth ID: ' . auth()->id() . ', Build ID: ' . $build->id . ', Build User ID: ' . $build->user_id . ', Build Name: ' . ($build->name ?? 'Build #' . $build->id));

        if ($build->user_id !== auth()->id()) {
            \Log::warning('Authorization failed - Auth ID: ' . auth()->id() . ', Build User ID: ' . $build->user_id);
            return redirect()->route('customer.profile')->with('error', 'You are not authorized to purchase this build.');
        }

        try {
            $quotationNumber = QuotationAction::generateQuotationNumber();

            $quotationAction = QuotationAction::create([
                'user_id' => auth()->id(),
                'action' => 'created',
                'build_details' => [
                    'build_id' => $build->id,
                    'name' => $build->name ?? 'Build #' . $build->id,
                    'total_price' => $build->total_price,
                    'components' => [
                        'cpu' => $build->cpu ? $build->cpu->name : 'Not selected',
                        'motherboard' => $build->motherboard ? $build->motherboard->name : 'Not selected',
                        'gpu' => $build->gpu ? $build->gpu->name : 'Not selected',
                        'ram' => $build->rams->isEmpty() ? 'Not selected' : $build->rams->pluck('name')->toArray(),
                        'storage' => $build->storages->isEmpty() ? 'Not selected' : $build->storages->pluck('name')->toArray(),
                        'power_supply' => $build->powerSupply ? $build->powerSupply->name : 'Not selected',
                    ],
                ],
                'quotation_number' => $quotationNumber,
                'source' => 'Build PC',
                'build_id' => $build->id,
                'quotation_request_id' => null,
            ]);

            Mail::to($build->user->email)->send(new BuildPurchased($build, $quotationNumber, $quotationAction->source));
            return redirect()->route('customer.profile')->with('success', 'Build purchased successfully! A confirmation email has been sent to your email address.');
        } catch (\Exception $e) {
            \Log::error('Error sending build purchase email: ' . $e->getMessage());
            return redirect()->route('customer.profile')->with('error', 'There was an error processing your purchase. Please try again later.');
        }
    }
}