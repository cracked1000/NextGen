<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cpu;
use App\Models\Motherboard;
use App\Models\Gpu;
use App\Models\Ram;
use App\Models\Storage;
use App\Models\PowerSupply;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDF;

class QuotationController extends Controller
{
    private $allocations = [
        'gaming' => [
            'cpu' => 0.2,
            'motherboard' => 0.1,
            'gpu' => 0.4,
            'ram' => 0.1,
            'storage' => 0.1,
            'power_supply' => 0.1,
        ],
        'video_editing' => [
            'cpu' => 0.3,
            'motherboard' => 0.1,
            'gpu' => 0.15,
            'ram' => 0.25,
            'storage' => 0.15,
            'power_supply' => 0.05,
        ],
        'general_use' => [
            'cpu' => 0.25,
            'motherboard' => 0.2,
            'gpu' => 0.1,
            'ram' => 0.15,
            'storage' => 0.15,
            'power_supply' => 0.15,
        ],
        'workstation' => [
            'cpu' => 0.35,
            'motherboard' => 0.1,
            'gpu' => 0.2,
            'ram' => 0.2,
            'storage' => 0.1,
            'power_supply' => 0.05,
        ],
    ];

    private $spec_levels = [
        'low' => 0.5,
        'medium' => 0.75,
        'high' => 1.0,
    ];

    private $budget_thresholds = [
        'very_low' => 100000,
        'low' => 900,
        'medium' => 1500
    ];

    public function index()
    {
        $use_cases = array_keys($this->allocations);
        return view('quotation.index', compact('use_cases'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'budget' => 'required|numeric|min:200000',
            'use_case' => 'required|string',
            'custom_allocation' => 'sometimes|boolean',
            'cpu_allocation' => 'sometimes|numeric|min:0.05|max:0.5',
            'motherboard_allocation' => 'sometimes|numeric|min:0.05|max:0.5',
            'gpu_allocation' => 'sometimes|numeric|min:0.05|max:0.5',
            'ram_allocation' => 'sometimes|numeric|min:0.05|max:0.5',
            'storage_allocation' => 'sometimes|numeric|min:0.05|max:0.5',
            'power_supply_allocation' => 'sometimes|numeric|min:0.05|max:0.5',
        ]);

        $budget = $request->budget;
        $use_case = $request->use_case;

        if (!isset($this->allocations[$use_case])) {
            $use_case = 'general_use';
            Log::warning("Invalid use case '{$request->use_case}' selected. Using 'general_use' as fallback.");
        }

        $allocation = $this->allocations[$use_case];

        if ($request->has('custom_allocation') && $request->custom_allocation) {
            $custom_allocation = [
                'cpu' => $request->cpu_allocation,
                'motherboard' => $request->motherboard_allocation,
                'gpu' => $request->gpu_allocation,
                'ram' => $request->ram_allocation,
                'storage' => $request->storage_allocation,
                'power_supply' => $request->power_supply_allocation,
            ];

            $has_all_components = !in_array(null, $custom_allocation, true);
            $sum = array_sum($custom_allocation);
            $is_valid_sum = abs($sum - 1.0) < 0.05;

            if ($has_all_components && $is_valid_sum) {
                $allocation = $custom_allocation;
            }
        }

        $builds = [];
        $build_errors = [];
        $budget_message = null;

        $specs_to_generate = $this->getSpecLevelsForBudget($budget);

        try {
            DB::beginTransaction();

            if ($budget <= $this->budget_thresholds['very_low']) {
                try {
                    $builds['basic'] = $this->generateBuild($budget, $allocation, 0.5);
                    $budget_message = "Based on your limited budget of $" . number_format($budget, 2) .
                                     ", we've generated a basic build that meets minimum requirements for {$use_case}.";
                } catch (\Exception $e) {
                    $build_errors['basic'] = $e->getMessage();
                    Log::error("Error generating basic build: " . $e->getMessage());
                }
            } else {
                foreach ($specs_to_generate as $spec => $factor) {
                    try {
                        $builds[$spec] = $this->generateBuild($budget, $allocation, $factor);
                    } catch (\Exception $e) {
                        $build_errors[$spec] = $e->getMessage();
                        Log::error("Error generating {$spec} build: " . $e->getMessage());
                    }
                }

                if ($budget <= $this->budget_thresholds['low']) {
                    $budget_message = "Your budget of $" . number_format($budget, 2) .
                                     " allows for a basic {$use_case} build. Consider increasing your budget for more options.";
                } elseif ($budget <= $this->budget_thresholds['medium']) {
                    $budget_message = "Your budget of $" . number_format($budget, 2) .
                                     " allows for low to medium range builds for {$use_case}.";
                }
            }

            DB::commit();

            if (count($builds) === 0) {
                return back()->withErrors(['error' => 'Unable to generate any builds. Please try a different budget or use case.'])
                            ->withInput();
            }

            session()->put('quotation_data', compact('builds', 'build_errors', 'budget', 'use_case', 'allocation', 'budget_message'));

            return view('quotation.index', compact('builds', 'build_errors', 'budget', 'use_case', 'allocation', 'budget_message'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in build generation process: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An unexpected error occurred: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    public function download(Request $request, $spec)
    {
        $data = session('quotation_data');

        if (!$data) {
            return redirect()->route('quotation.index')->withErrors(['error' => 'No quotation data available to download. Please generate a new quotation.']);
        }

        $builds = $data['builds'];
        $build_errors = $data['build_errors'];
        $budget = $data['budget'];
        $use_case = $data['use_case'];
        $allocation = $data['allocation'];
        $budget_message = $data['budget_message'] ?? null;

        if (!isset($builds[$spec])) {
            return redirect()->route('quotation.index')->withErrors(['error' => "No {$spec} spec build available to download."]);
        }

        // Create a new builds array with only the selected spec
        $selected_build = [$spec => $builds[$spec]];

        // Pass a flag to indicate this is for PDF generation
        $is_pdf = true;

        $pdf = PDF::loadView('quotation.index', compact('builds', 'build_errors', 'budget', 'use_case', 'allocation', 'budget_message', 'is_pdf'));

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("pc_build_quotation_{$spec}.pdf");
    }

    private function getSpecLevelsForBudget($budget)
    {
        if ($budget <= $this->budget_thresholds['very_low']) {
            return ['basic' => 0.5];
        } elseif ($budget <= $this->budget_thresholds['low']) {
            return ['low' => $this->spec_levels['low']];
        } elseif ($budget <= $this->budget_thresholds['medium']) {
            return [
                'low' => $this->spec_levels['low'],
                'medium' => $this->spec_levels['medium']
            ];
        } else {
            return $this->spec_levels;
        }
    }

    private function generateBuild($budget, $allocation, $factor)
    {
        $build = [];
        $total_price = 0;
        $remaining_budget = $budget;

        $cpu_budget = $factor * $allocation['cpu'] * $budget;
        $cpu = $this->selectComponent(Cpu::class, $cpu_budget, [], 'CPU');
        $build['cpu'] = $cpu;
        $component_price = $cpu->price ?? 0;
        $total_price += $component_price;
        $remaining_budget -= $component_price;

        $motherboard_budget = $factor * $allocation['motherboard'] * $budget;
        $motherboard_filters = [
            ['field' => 'socket_type', 'value' => $cpu->socket_type, 'operator' => '=']
        ];
        $motherboard = $this->selectComponent(Motherboard::class, $motherboard_budget, $motherboard_filters, 'motherboard');
        $build['motherboard'] = $motherboard;
        $component_price = $motherboard->price ?? 0;
        $total_price += $component_price;
        $remaining_budget -= $component_price;

        $ram_budget = $factor * $allocation['ram'] * $budget;
        $ram_filters = [];

        if (!is_null($motherboard->ram_type)) {
            $ram_filters[] = ['field' => 'ram_type', 'value' => $motherboard->ram_type, 'operator' => '='];
        }
        if (!is_null($motherboard->ram_speed)) {
            $ram_filters[] = ['field' => 'ram_speed', 'value' => $motherboard->ram_speed, 'operator' => '<='];
        }
        if (!is_null($motherboard->ram_slots)) {
            $ram_filters[] = ['field' => 'stick_count', 'value' => $motherboard->ram_slots, 'operator' => '<='];
        }

        $ram = $this->selectComponent(Ram::class, $ram_budget, $ram_filters, 'RAM');
        $build['ram'] = $ram;
        $component_price = $ram->price ?? 0;
        $total_price += $component_price;
        $remaining_budget -= $component_price;

        $gpu_budget = $factor * $allocation['gpu'] * $budget;
        $gpu_filters = [];

        if (!is_null($motherboard->pcie_version)) {
            $gpu_filters[] = ['field' => 'pcie_version', 'value' => $motherboard->pcie_version, 'operator' => '<='];
        }

        $gpu = $this->selectComponent(Gpu::class, $gpu_budget, $gpu_filters, 'GPU');
        $build['gpu'] = $gpu;
        $component_price = $gpu->price ?? 0;
        $total_price += $component_price;
        $remaining_budget -= $component_price;

        $storage_budget = $factor * $allocation['storage'] * $budget;
        $storage_filters = [];

        if (!is_null($motherboard->m2_slots) && !is_null($motherboard->m2_nvme_support) && !is_null($motherboard->sata_slots)) {
            $has_storage_compatibility_constraints = true;
        } else {
            $has_storage_compatibility_constraints = false;
        }

        $storage = $this->selectStorageComponent($storage_budget, $motherboard, $has_storage_compatibility_constraints);
        $build['storage'] = $storage;
        $component_price = $storage->price ?? 0;
        $total_price += $component_price;
        $remaining_budget -= $component_price;

        $power_supply_budget = $factor * $allocation['power_supply'] * $budget;
        $power_supply_filters = [];

        if (!is_null($motherboard->form_factor)) {
            $power_supply_filters[] = ['field' => 'form_factor', 'value' => $motherboard->form_factor, 'operator' => '='];
        }

        $total_power_requirement = ($cpu->power_requirement ?? 0) + ($gpu->power_requirement ?? 0);
        if ($total_power_requirement > 0) {
            $power_supply_filters[] = ['field' => 'wattage', 'value' => ceil($total_power_requirement * 1.2), 'operator' => '>='];
        }

        $power_supply = $this->selectComponent(PowerSupply::class, $power_supply_budget, $power_supply_filters, 'power supply');
        $build['power_supply'] = $power_supply;
        $component_price = $power_supply->price ?? 0;
        $total_price += $component_price;
        $remaining_budget -= $component_price;

        $build['total_price'] = $total_price;
        $build['remaining_budget'] = $remaining_budget;
        $build['budget_used_percentage'] = round(($total_price / $budget) * 100, 2);

        $build['price_breakdown'] = [
            'cpu' => ($cpu->price ?? 0) / $total_price * 100,
            'motherboard' => ($motherboard->price ?? 0) / $total_price * 100,
            'ram' => ($ram->price ?? 0) / $total_price * 100,
            'gpu' => ($gpu->price ?? 0) / $total_price * 100,
            'storage' => ($storage->price ?? 0) / $total_price * 100,
            'power_supply' => ($power_supply->price ?? 0) / $total_price * 100,
        ];

        $build['compatibility'] = $this->checkBuildCompatibility($build);

        return $build;
    }

    private function selectComponent($model_class, $max_budget, $filters = [], $component_name = 'component')
    {
        $query = $model_class::query();

        foreach ($filters as $filter) {
            if (isset($filter['operator']) && isset($filter['field']) && isset($filter['value'])) {
                $query->where($filter['field'], $filter['operator'], $filter['value']);
            }
        }

        if ($max_budget > 0) {
            $budget_query = clone $query;
            $budget_query->where('price', '<=', $max_budget);
            $component = $budget_query->orderByDesc('price')->first();

            if ($component) {
                return $component;
            }
        }

        $component = $query->orderBy('price', 'asc')->first();

        if ($component) {
            return $component;
        }

        if (count($filters) > 0) {
            for ($i = count($filters) - 1; $i >= 0; $i--) {
                $reduced_filters = array_slice($filters, 0, $i);
                try {
                    return $this->selectComponent($model_class, $max_budget, $reduced_filters, $component_name);
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        $any_component = $model_class::first();

        if ($any_component) {
            Log::warning("No compatible {$component_name} found with given filters. Using first available instead.");
            return $any_component;
        }

        throw new \Exception("No {$component_name} found in the database.");
    }

    private function selectStorageComponent($max_budget, $motherboard, $has_compatibility_constraints)
    {
        $query = Storage::query();

        if ($max_budget > 0) {
            $budget_query = clone $query;
            $budget_query->where('price', '<=', $max_budget);

            if ($has_compatibility_constraints) {
                $budget_query->where(function ($q) use ($motherboard) {
                    if ($motherboard->m2_slots > 0 && $motherboard->m2_nvme_support) {
                        $q->orWhere('is_nvme', true);
                    }
                    if ($motherboard->sata_slots > 0) {
                        $q->orWhere('is_nvme', false);
                    }
                });
            }

            $storage = $budget_query->orderByDesc('price')->first();

            if ($storage) {
                return $storage;
            }
        }

        if ($has_compatibility_constraints) {
            $query->where(function ($q) use ($motherboard) {
                if ($motherboard->m2_slots > 0 && $motherboard->m2_nvme_support) {
                    $q->orWhere('is_nvme', true);
                }
                if ($motherboard->sata_slots > 0) {
                    $q->orWhere('is_nvme', false);
                }
            });
        }

        $storage = $query->orderBy('price', 'asc')->first();

        if ($storage) {
            return $storage;
        }

        $any_storage = Storage::first();

        if ($any_storage) {
            Log::warning("No compatible storage found. Using first available instead.");
            return $any_storage;
        }

        throw new \Exception("No storage devices found in the database.");
    }

    private function checkBuildCompatibility($build)
    {
        $compatibility = [
            'is_compatible' => true,
            'warnings' => [],
            'errors' => []
        ];

        if ($build['cpu']->socket_type !== $build['motherboard']->socket_type) {
            $compatibility['is_compatible'] = false;
            $compatibility['errors'][] = "CPU socket type ({$build['cpu']->socket_type}) does not match motherboard socket type ({$build['motherboard']->socket_type}).";
        }

        if (!is_null($build['motherboard']->ram_type) && $build['ram']->ram_type !== $build['motherboard']->ram_type) {
            $compatibility['is_compatible'] = false;
            $compatibility['errors'][] = "RAM type ({$build['ram']->ram_type}) is not compatible with motherboard RAM type ({$build['motherboard']->ram_type}).";
        }

        if (!is_null($build['motherboard']->ram_speed) && $build['ram']->ram_speed > $build['motherboard']->ram_speed) {
            $compatibility['warnings'][] = "RAM speed ({$build['ram']->ram_speed}) exceeds motherboard's supported speed ({$build['motherboard']->ram_speed}). RAM will be downclocked.";
        }

        if (!is_null($build['motherboard']->ram_slots) && $build['ram']->stick_count > $build['motherboard']->ram_slots) {
            $compatibility['is_compatible'] = false;
            $compatibility['errors'][] = "RAM stick count ({$build['ram']->stick_count}) exceeds available motherboard slots ({$build['motherboard']->ram_slots}).";
        }

        if (!is_null($build['motherboard']->pcie_version) && $build['gpu']->pcie_version > $build['motherboard']->pcie_version) {
            $compatibility['warnings'][] = "GPU PCIe version ({$build['gpu']->pcie_version}) is higher than motherboard PCIe version ({$build['motherboard']->pcie_version}). GPU will operate at reduced bandwidth.";
        }

        if ($build['storage']->is_nvme && (!$build['motherboard']->m2_slots || !$build['motherboard']->m2_nvme_support)) {
            $compatibility['is_compatible'] = false;
            $compatibility['errors'][] = "NVMe storage selected but motherboard doesn't support NVMe or has no M.2 slots.";
        }

        if (!$build['storage']->is_nvme && !$build['motherboard']->sata_slots) {
            $compatibility['is_compatible'] = false;
            $compatibility['errors'][] = "SATA storage selected but motherboard has no SATA slots.";
        }

        $total_power_requirement = ($build['cpu']->power_requirement ?? 0) + ($build['gpu']->power_requirement ?? 0);
        if ($build['power_supply']->wattage < $total_power_requirement) {
            $compatibility['is_compatible'] = false;
            $compatibility['errors'][] = "Power supply wattage ({$build['power_supply']->wattage}W) is insufficient for the system's power requirements ({$total_power_requirement}W).";
        }

        $recommended_wattage = ceil($total_power_requirement * 1.2);
        if ($build['power_supply']->wattage < $recommended_wattage) {
            $compatibility['warnings'][] = "Power supply wattage ({$build['power_supply']->wattage}W) is below recommended ({$recommended_wattage}W) for optimal operation.";
        }

        return $compatibility;
    }
}