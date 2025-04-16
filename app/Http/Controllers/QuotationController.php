<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cpu;
use App\Models\Motherboard;
use App\Models\Gpu;
use App\Models\Ram;
use App\Models\Storage;
use App\Models\PowerSupply;
use App\Models\QuotationRequest;
use App\Models\QuotationAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\BuildDetailsEmail;
use PDF;

class QuotationController extends Controller
{
    private $allocations = [
        'gaming' => [
            'cpu' => 0.25,
            'motherboard' => 0.15,
            'gpu' => 0.30,
            'ram' => 0.15,
            'storage' => 0.10,
            'power_supply' => 0.05,
        ],
        'video_editing' => [
            'cpu' => 0.30,
            'motherboard' => 0.10,
            'gpu' => 0.15,
            'ram' => 0.25,
            'storage' => 0.15,
            'power_supply' => 0.05,
        ],
        'general_use' => [
            'cpu' => 0.25,
            'motherboard' => 0.15,
            'gpu' => 0.20,
            'ram' => 0.15,
            'storage' => 0.15,
            'power_supply' => 0.10,
        ],
        'workstation' => [
            'cpu' => 0.35,
            'motherboard' => 0.10,
            'gpu' => 0.20,
            'ram' => 0.20,
            'storage' => 0.10,
            'power_supply' => 0.05,
        ],
    ];

    private $spec_levels = [
        'low' => 0.5,
        'medium' => 0.75,
        'high' => 1.0,
    ];

    private $budget_thresholds = [
        'very_low' => 200000,
        'low' => 300000,
        'medium' => 500000,
    ];

    public function __construct()
    {
        $this->middleware('auth')->only(['sendBuildEmail']);
    }

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

        // Fallback to general_use if use_case is invalid
        if (!isset($this->allocations[$use_case])) {
            $use_case = 'general_use';
            Log::warning("Invalid use case '{$request->use_case}' selected. Using 'general_use' as fallback.");
        }

        $allocation = $this->allocations[$use_case];

        // Apply custom allocation if provided and valid
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
            } else {
                Log::warning("Invalid custom allocation provided. Using default allocation for {$use_case}.");
            }
        }

        $builds = [];
        $build_errors = [];
        $budget_message = null;
        $quotations = []; // Store quotation details for each build

        $specs_to_generate = $this->getSpecLevelsForBudget($budget);

        try {
            DB::beginTransaction();

            if ($budget <= $this->budget_thresholds['very_low']) {
                try {
                    $builds['basic'] = $this->generateBuild($budget, $allocation, 0.5, $use_case);

                    // Create QuotationRequest and QuotationAction for the basic build
                    $quotationDetails = $this->storeQuotation($request, $builds['basic'], $use_case);
                    $quotations['basic'] = $quotationDetails;

                    $budget_message = "Based on your limited budget of LKR " . number_format($budget, 2) .
                                     ", we've generated a basic build that meets minimum requirements for {$use_case}.";
                } catch (\Exception $e) {
                    $build_errors['basic'] = $e->getMessage();
                    Log::error("Error generating basic build: " . $e->getMessage());
                }
            } else {
                foreach ($specs_to_generate as $spec => $factor) {
                    try {
                        $builds[$spec] = $this->generateBuild($budget, $allocation, $factor, $use_case);

                        // Create QuotationRequest and QuotationAction for each spec
                        $quotationDetails = $this->storeQuotation($request, $builds[$spec], $use_case);
                        $quotations[$spec] = $quotationDetails;
                    } catch (\Exception $e) {
                        $build_errors[$spec] = $e->getMessage();
                        Log::error("Error generating {$spec} build: " . $e->getMessage());
                    }
                }

                if ($budget <= $this->budget_thresholds['low']) {
                    $budget_message = "Your budget of LKR " . number_format($budget, 2) .
                                     " allows for a basic {$use_case} build. Consider increasing your budget for more options.";
                } elseif ($budget <= $this->budget_thresholds['medium']) {
                    $budget_message = "Your budget of LKR " . number_format($budget, 2) .
                                     " allows for low to medium range builds for {$use_case}.";
                } else {
                    $budget_message = "Your budget of LKR " . number_format($budget, 2) .
                                     " allows for a range of builds for {$use_case}.";
                }
            }

            DB::commit();

            if (empty($builds)) {
                return back()->withErrors(['error' => 'Unable to generate any builds. Please try a different budget or use case.'])
                            ->withInput();
            }

            // Provide budget overrun feedback if necessary
            foreach ($builds as $spec => $build) {
                if ($build['remaining_budget'] < 0) {
                    $budget_message .= " The {$spec} build exceeds your budget by LKR " . number_format(abs($build['remaining_budget']), 2) .
                                      ". Consider increasing your budget or adjusting your use case.";
                }
            }

            // Store builds, quotations, and other data in the session
            session()->put('quotation_data', compact('builds', 'quotations', 'build_errors', 'budget', 'use_case', 'allocation', 'budget_message'));

            return view('quotation.index', compact('builds', 'quotations', 'build_errors', 'budget', 'use_case', 'allocation', 'budget_message'));

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
        $quotations = $data['quotations'];
        $build_errors = $data['build_errors'];
        $budget = $data['budget'];
        $use_case = $data['use_case'];
        $allocation = $data['allocation'];
        $budget_message = $data['budget_message'] ?? null;

        if (!isset($builds[$spec])) {
            return redirect()->route('quotation.index')->withErrors(['error' => "No {$spec} spec build available to download."]);
        }

        $is_pdf = true;

        $pdf = PDF::loadView('quotation.index', compact('builds', 'quotations', 'build_errors', 'budget', 'use_case', 'allocation', 'budget_message', 'is_pdf'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("pc_build_quotation_{$spec}.pdf");
    }

    public function sendBuildEmail(Request $request, $spec)
    {
        $data = session('quotation_data');

        if (!$data) {
            return redirect()->route('quotation.index')->withErrors(['error' => 'No quotation data available to send. Please generate a new quotation.']);
        }

        $builds = $data['builds'];
        $quotations = $data['quotations'];
        $use_case = $data['use_case'];

        if (!isset($builds[$spec])) {
            return redirect()->route('quotation.index')->withErrors(['error' => "No {$spec} spec build available to send."]);
        }

        $build = $builds[$spec];
        $quotation = $quotations[$spec] ?? null;

        $user = $request->user();
        if (!$user || !$user->email) {
            return redirect()->route('quotation.index')->withErrors(['error' => 'User email not found. Please ensure you are logged in.']);
        }

        if (!$quotation) {
            return redirect()->route('quotation.index')->withErrors(['error' => 'Quotation details not found for this build.']);
        }

        try {
            Mail::to($user->email)->send(new BuildDetailsEmail($build, $spec, $use_case, $quotation['quotation_number'], $quotation['source']));
            return redirect()->route('quotation.index')->with('success', 'Build details have been sent to your email!');
        } catch (\Exception $e) {
            Log::error('Error sending build email: ' . $e->getMessage());
            return redirect()->route('quotation.index')->withErrors(['error' => 'Failed to send the email. Please try again later.']);
        }
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

    private function generateBuild($budget, $allocation, $factor, $use_case)
    {
        $build = [];
        $total_price = 0;
        $remaining_budget = $budget;

        // Define essential components based on use case
        $essential_components = $this->getEssentialComponents($use_case);

        // Select essential components first to ensure core functionality
        foreach ($essential_components as $component) {
            $component_budget = $factor * $allocation[$component] * $budget;
            $adjusted_budget = min($component_budget, $remaining_budget);
            if ($adjusted_budget <= 0) {
                throw new \Exception("Insufficient remaining budget to select {$component}.");
            }
            $filters = $this->getFiltersForComponent($component, $build);
            $selected_component = $this->selectComponent(
                $this->getModelClass($component),
                $adjusted_budget,
                $filters,
                $component,
                $use_case
            );
            $build[$component] = $selected_component;
            $component_price = $selected_component->price ?? 0;
            $total_price += $component_price;
            $remaining_budget -= $component_price;
        }

        // Select non-essential components with remaining budget
        $non_essential_components = array_diff(array_keys($allocation), $essential_components);
        foreach ($non_essential_components as $component) {
            $component_budget = $factor * $allocation[$component] * $budget;
            $adjusted_budget = min($component_budget, $remaining_budget);
            if ($adjusted_budget <= 0) {
                // Skip if no budget remains; assign a placeholder
                $build[$component] = $this->getCheapestComponent($this->getModelClass($component));
                continue;
            }
            $filters = $this->getFiltersForComponent($component, $build);
            $selected_component = $this->selectComponent(
                $this->getModelClass($component),
                $adjusted_budget,
                $filters,
                $component,
                $use_case
            );
            $build[$component] = $selected_component;
            $component_price = $selected_component->price ?? 0;
            $total_price += $component_price;
            $remaining_budget -= $component_price;
        }

        // Summarize the build
        $build['total_price'] = $total_price;
        $build['remaining_budget'] = $remaining_budget;
        $build['budget_used_percentage'] = $budget > 0 ? round(($total_price / $budget) * 100, 2) : 0;

        $build['price_breakdown'] = [];
        foreach ($allocation as $component => $percentage) {
            $build['price_breakdown'][$component] = $total_price > 0 ? ($build[$component]->price ?? 0) / $total_price * 100 : 0;
        }

        $build['compatibility'] = $this->checkBuildCompatibility($build, $use_case);

        return $build;
    }

    /**
     * Store the quotation details in QuotationRequest and QuotationAction.
     *
     * @param Request $request
     * @param array $build
     * @param string $use_case
     * @return array
     */
    private function storeQuotation(Request $request, array $build, string $use_case)
    {
        $userId = auth()->check() ? auth()->id() : null;

        // Create a QuotationRequest
        $quotationRequest = QuotationRequest::create([
            'user_id' => $userId,
            'components' => json_encode($build),
            'total_price' => $build['total_price'],
        ]);

        // Generate a unique quotation number
        $quotationNumber = QuotationAction::generateQuotationNumber();

        // Create a QuotationAction
        $quotationAction = QuotationAction::create([
            'user_id' => $userId,
            'action' => 'generated',
            'build_details' => [
                'use_case' => $use_case,
                'components' => [
                    'cpu' => $build['cpu'] ? $build['cpu']->toArray() : null,
                    'motherboard' => $build['motherboard'] ? $build['motherboard']->toArray() : null,
                    'gpu' => $build['gpu'] ? $build['gpu']->toArray() : null,
                    'ram' => $build['ram'] ? $build['ram']->toArray() : null,
                    'storage' => $build['storage'] ? $build['storage']->toArray() : null,
                    'power_supply' => $build['power_supply'] ? $build['power_supply']->toArray() : null,
                ],
                'total_price' => $build['total_price'],
                'remaining_budget' => $build['remaining_budget'],
                'budget_used_percentage' => $build['budget_used_percentage'],
                'price_breakdown' => $build['price_breakdown'],
                'compatibility' => $build['compatibility'],
            ],
            'quotation_number' => $quotationNumber,
            'source' => 'Quotation Generator',
            'build_id' => null,
            'quotation_request_id' => $quotationRequest->id,
        ]);

        return [
            'quotation_number' => $quotationNumber,
            'source' => $quotationAction->source,
            'quotation_action_id' => $quotationAction->id,
        ];
    }

    private function selectComponent($model_class, $max_budget, $filters = [], $component_name = 'component', $use_case = 'general_use')
    {
        $query = $model_class::query();

        // Apply compatibility filters (e.g., socket type for motherboard)
        foreach ($filters as $filter) {
            if (isset($filter['operator']) && isset($filter['field']) && isset($filter['value'])) {
                $query->where($filter['field'], $filter['operator'], $filter['value']);
            }
        }

        // Apply use-case specific sorting
        if ($component_name == 'cpu') {
            if ($use_case == 'gaming' && property_exists($model_class, 'clock_speed')) {
                $query->orderByDesc('clock_speed');
            } elseif ($use_case == 'video_editing' && property_exists($model_class, 'core_count')) {
                $query->orderByDesc('core_count');
            }
        } elseif ($component_name == 'ram') {
            if ($use_case == 'video_editing' && property_exists($model_class, 'capacity')) {
                $query->orderByDesc('capacity');
            }
        }

        // Try to find the best component within budget
        if ($max_budget > 0) {
            $budget_query = clone $query;
            $component = $budget_query->where('price', '<=', $max_budget)
                                     ->orderBy('price', 'desc') // Best value within budget
                                     ->first();
            if ($component) {
                return $component;
            }
        }

        // Fallback: Cheapest compatible component
        $component = $query->orderBy('price', 'asc')->first();
        if ($component) {
            Log::warning("No {$component_name} found within budget of LKR " . number_format($max_budget, 2) . ". Using cheapest compatible option.");
            return $component;
        }

        // Last resort: Cheapest component ignoring filters
        $any_component = $model_class::orderBy('price', 'asc')->first();
        if ($any_component) {
            Log::warning("No compatible {$component_name} found within budget or filters. Using cheapest available.");
            return $any_component;
        }

        throw new \Exception("No {$component_name} found in the database.");
    }

    private function getCheapestComponent($model_class)
    {
        return $model_class::orderBy('price', 'asc')->first();
    }

    private function getEssentialComponents($use_case)
    {
        switch ($use_case) {
            case 'gaming':
                return ['cpu', 'gpu', 'ram'];
            case 'video_editing':
                return ['cpu', 'ram', 'storage'];
            case 'general_use':
                return ['cpu', 'ram'];
            case 'workstation':
                return ['cpu', 'ram'];
            default:
                return ['cpu', 'ram'];
        }
    }

    private function getFiltersForComponent($component, $build)
    {
        $filters = [];

        if ($component === 'motherboard' && isset($build['cpu'])) {
            $filters[] = ['field' => 'socket_type', 'value' => $build['cpu']->socket_type, 'operator' => '='];
        } elseif ($component === 'ram' && isset($build['motherboard'])) {
            if (!is_null($build['motherboard']->ram_type)) {
                $filters[] = ['field' => 'ram_type', 'value' => $build['motherboard']->ram_type, 'operator' => '='];
            }
            if (!is_null($build['motherboard']->ram_speed)) {
                $filters[] = ['field' => 'ram_speed', 'value' => $build['motherboard']->ram_speed, 'operator' => '<='];
            }
            if (!is_null($build['motherboard']->ram_slots)) {
                $filters[] = ['field' => 'stick_count', 'value' => $build['motherboard']->ram_slots, 'operator' => '<='];
            }
        } elseif ($component === 'gpu' && isset($build['motherboard'])) {
            if (!is_null($build['motherboard']->pcie_version)) {
                $filters[] = ['field' => 'pcie_version', 'value' => $build['motherboard']->pcie_version, 'operator' => '<='];
            }
        } elseif ($component === 'storage' && isset($build['motherboard'])) {
            $filters[] = ['field' => 'is_nvme', 'value' => $build['motherboard']->m2_slots > 0 && $build['motherboard']->m2_nvme_support, 'operator' => '='];
        } elseif ($component === 'power_supply' && isset($build['motherboard'])) {
            if (!is_null($build['motherboard']->form_factor)) {
                $filters[] = ['field' => 'form_factor', 'value' => $build['motherboard']->form_factor, 'operator' => '='];
            }
        }

        return $filters;
    }

    private function getModelClass($component)
    {
        $model_classes = [
            'cpu' => Cpu::class,
            'motherboard' => Motherboard::class,
            'gpu' => Gpu::class,
            'ram' => Ram::class,
            'storage' => Storage::class,
            'power_supply' => PowerSupply::class,
        ];

        if (!isset($model_classes[$component])) {
            throw new \Exception("Invalid component: {$component}");
        }

        return $model_classes[$component];
    }

    private function checkBuildCompatibility($build, $use_case = 'general_use')
    {
        $compatibility = [
            'is_compatible' => true,
            'warnings' => [],
            'errors' => []
        ];

        // Socket compatibility
        if ($build['cpu']->socket_type !== $build['motherboard']->socket_type) {
            $compatibility['is_compatible'] = false;
            $compatibility['errors'][] = "CPU socket type ({$build['cpu']->socket_type}) does not match motherboard socket type ({$build['motherboard']->socket_type}).";
        }

        // RAM compatibility
        if (!is_null($build['motherboard']->ram_type) && $build['ram']->ram_type !== $build['motherboard']->ram_type) {
            $compatibility['is_compatible'] = false;
            $compatibility['errors'][] = "RAM type ({$build['ram']->ram_type}) is not compatible with motherboard RAM type ({$build['motherboard']->ram_type}).";
        }

        if (!is_null($build['motherboard']->ram_speed) && $build['ram']->ram_speed > $build['motherboard']->ram_speed) {
            $compatibility['warnings'][] = "RAM speed ({$build['ram']->ram_speed} MHz) exceeds motherboard's supported speed ({$build['motherboard']->ram_speed} MHz). RAM will be downclocked.";
        }

        if (!is_null($build['motherboard']->ram_slots) && $build['ram']->stick_count > $build['motherboard']->ram_slots) {
            $compatibility['is_compatible'] = false;
            $compatibility['errors'][] = "RAM stick count ({$build['ram']->stick_count}) exceeds available motherboard slots ({$build['motherboard']->ram_slots}).";
        }

        // GPU compatibility
        if (!is_null($build['motherboard']->pcie_version) && $build['gpu']->pcie_version > $build['motherboard']->pcie_version) {
            $compatibility['warnings'][] = "GPU PCIe version ({$build['gpu']->pcie_version}) is higher than motherboard PCIe version ({$build['motherboard']->pcie_version}). GPU will operate at reduced bandwidth.";
        }

        // Storage compatibility
        if ($build['storage']->is_nvme && (!$build['motherboard']->m2_slots || !$build['motherboard']->m2_nvme_support)) {
            $compatibility['is_compatible'] = false;
            $compatibility['errors'][] = "NVMe storage selected but motherboard doesn't support NVMe or has no M.2 slots.";
        }

        if (!$build['storage']->is_nvme && !$build['motherboard']->sata_slots) {
            $compatibility['is_compatible'] = false;
            $compatibility['errors'][] = "SATA storage selected but motherboard has no SATA slots.";
        }

        // Power supply compatibility
        $total_power_requirement = ($build['cpu']->power_requirement ?? 0) + ($build['gpu']->power_requirement ?? 0);
        $power_safety_margin = ($use_case == 'gaming' || $use_case == 'video_editing') ? 1.3 : 1.2;
        $recommended_wattage = ceil($total_power_requirement * $power_safety_margin);
        if ($build['power_supply']->wattage < $total_power_requirement) {
            $compatibility['is_compatible'] = false;
            $compatibility['errors'][] = "Power supply wattage ({$build['power_supply']->wattage}W) is insufficient for the system's power requirements ({$total_power_requirement}W).";
        } elseif ($build['power_supply']->wattage < $recommended_wattage) {
            $compatibility['warnings'][] = "Power supply wattage ({$build['power_supply']->wattage}W) is below recommended ({$recommended_wattage}W) for optimal {$use_case} performance.";
        }

        // Budget check
        if ($build['remaining_budget'] < 0) {
            $compatibility['warnings'][] = "This build exceeds your budget by LKR " . number_format(abs($build['remaining_budget']), 2) . ".";
        }

        // Performance checks based on use case
        if ($use_case === 'general_use') {
            if (property_exists($build['ram'], 'capacity') && $build['ram']->capacity < 8) {
                $compatibility['warnings'][] = "For general use, at least 8GB RAM is recommended. Current selection has {$build['ram']->capacity}GB.";
            }
            if (property_exists($build['storage'], 'capacity') && $build['storage']->capacity < 256) {
                $compatibility['warnings'][] = "For general use, at least 256GB storage is recommended. Current selection has {$build['storage']->capacity}GB.";
            }
        } elseif ($use_case === 'gaming') {
            if (property_exists($build['gpu'], 'vram') && $build['gpu']->vram < 4) {
                $compatibility['warnings'][] = "For gaming, a GPU with at least 4GB VRAM is recommended. Current selection has {$build['gpu']->vram}GB.";
            }
            if (property_exists($build['ram'], 'capacity') && $build['ram']->capacity < 16) {
                $compatibility['warnings'][] = "For gaming, at least 16GB RAM is recommended. Current selection has {$build['ram']->capacity}GB.";
            }
        } elseif ($use_case === 'video_editing') {
            if (property_exists($build['ram'], 'capacity') && $build['ram']->capacity < 16) {
                $compatibility['warnings'][] = "For video editing, at least 16GB RAM is recommended. Current selection has {$build['ram']->capacity}GB.";
            }
            if (property_exists($build['storage'], 'capacity') && $build['storage']->capacity < 512) {
                $compatibility['warnings'][] = "For video editing, at least 512GB storage is recommended. Current selection has {$build['storage']->capacity}GB.";
            }
        }

        return $compatibility;
    }
}