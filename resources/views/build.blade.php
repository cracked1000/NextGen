
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NextGen Computing</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            min-height: 100vh;
        }
        .orbitron {
            font-family: 'Orbitron', sans-serif;
        }
        .container {
            position: relative;
        }
        .nav-glow {
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.5);
        }
        .component-card {
            background: rgba(15, 23, 42, 0.7);
            border-radius: 12px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 59, 59, 0.2);
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .component-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(255, 0, 0, 0.2);
            border-color: rgba(255, 59, 59, 0.5);
        }
        .component-icon {
            transition: all 0.3s ease;
        }
        .icon-circle {
            transition: all 0.3s ease;
            background: rgba(15, 23, 42, 0.9);
            border: 2px solid rgba(255, 59, 59, 0.5);
        }
        .icon-circle.selected {
            background: rgba(34, 197, 94, 0.2);
            border-color: #22c55e;
            box-shadow: 0 0 15px rgba(34, 197, 94, 0.5);
        }
        .category-header {
            background: linear-gradient(90deg, rgba(15, 23, 42, 0.8) 0%, rgba(30, 41, 59, 0.8) 100%);
            border-left: 4px solid #ff3b3b;
            text-shadow: 0 0 10px rgba(255, 59, 59, 0.3);
        }
        .custom-select {
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(255, 59, 59, 0.3);
            color: #ffffff;
            transition: all 0.3s ease;
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }
        .custom-select:focus {
            outline: none;
            border-color: #22c55e;
            box-shadow: 0 0 10px rgba(34, 197, 94, 0.5);
        }
        .custom-select:disabled {
            background: rgba(15, 23, 42, 0.3);
            color: rgba(255, 255, 255, 0.5);
            border-color: rgba(255, 59, 59, 0.1);
        }
        .price-display {
            background: rgba(15, 23, 42, 0.5);
            border-left: 3px solid #ff3b3b;
            border-radius: 0 8px 8px 0;
        }
        .build-summary {
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(255, 59, 59, 0.3);
            border-radius: 12px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .checkbox-container {
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(255, 59, 59, 0.3);
            border-radius: 8px;
            backdrop-filter: blur(10px);
            max-height: 200px;
            overflow-y: auto;
        }
        .checkbox-container::-webkit-scrollbar {
            width: 8px;
        }
        .checkbox-container::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.3);
            border-radius: 8px;
        }
        .checkbox-container::-webkit-scrollbar-thumb {
            background: rgba(255, 59, 59, 0.5);
            border-radius: 8px;
        }
        .checkbox-container::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 59, 59, 0.7);
        }
        .progress-bar {
            height: 5px;
            border-radius: 5px;
            background: linear-gradient(90deg, #f43f5e 0%, #ec4899 100%);
        }
        .component-indicator {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #22c55e;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 10px rgba(34, 197, 94, 0.5);
            opacity: 0;
            transition: all 0.3s ease;
        }
        .component-card.completed .component-indicator {
            opacity: 1;
        }
        .custom-checkbox {
            appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 1px solid rgba(255, 59, 59, 0.5);
            background: rgba(15, 23, 42, 0.7);
            position: relative;
            margin-right: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .custom-checkbox:checked {
            background: #22c55e;
            border-color: #22c55e;
        }
        .custom-checkbox:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
        }
        .glowing-btn {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .glowing-btn::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            border-radius: 12px;
            z-index: -1;
            background: linear-gradient(45deg, #ff3b3b, #ff3b3b, #22c55e, #ff3b3b, #ff3b3b);
            background-size: 400%;
            animation: glowing 20s linear infinite;
            filter: blur(10px);
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        .glowing-btn:hover::before {
            opacity: 1;
        }
        @keyframes glowing {
            0% { background-position: 0 0; }
            50% { background-position: 400% 0; }
            100% { background-position: 0 0; }
        }
        .tooltip {
            position: relative;
            display: inline-block;
        }
        .tooltip .tooltiptext {
            visibility: hidden;
            width: 120px;
            background-color: rgba(15, 23, 42, 0.9);
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 12px;
            border: 1px solid rgba(255, 59, 59, 0.3);
        }
        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>
<body class="text-white">
    <!-- Header will be included server-side -->
    @include('include.header')
    
    <!-- Hero Banner -->
    <div class="relative overflow-hidden bg-gradient-to-r from-gray-900 to-black py-12 mb-8">
        <div class="absolute inset-0 opacity-30">
            <div class="absolute inset-0 bg-gradient-to-r from-pink-500 via-red-500 to-yellow-500 opacity-10"></div>
            <svg class="absolute left-0 top-0 h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0,0 L100,0 L100,100 L0,100 Z" fill="url(#grid)" />
            </svg>
            <defs>
                <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                    <path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/>
                </pattern>
            </defs>
        </div>
        <div class="container mx-auto px-4 py-8 relative z-10">
            <div class="text-center">
                <h1 class="orbitron text-4xl md:text-6xl font-bold mb-2 text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-pink-600">
                    BUILD YOUR DREAM PC
                </h1>
                <p class="text-xl text-gray-300 mb-6">Customize your perfect rig with our interactive builder</p>
                <div class="h-1 w-24 mx-auto bg-gradient-to-r from-red-500 to-pink-600 rounded-full"></div>
            </div>
        </div>
    </div>

    <!-- Build Progress Bar -->
    <div class="container mx-auto px-4 mb-8">
        <div class="bg-gray-800 rounded-full h-5 mb-1">
            <div id="progress-bar" class="progress-bar w-0 transition-all duration-500"></div>
        </div>
        <div class="flex justify-between text-xs text-gray-400">
            <span>Start</span>
            <span>CPU</span>
            <span>Motherboard</span>
            <span>GPU</span>
            <span>RAM</span>
            <span>Storage</span>
            <span>Power</span>
            <span>Complete</span>
        </div>
    </div>

    <!-- Component Grid -->
    <div class="container mx-auto px-4 mb-8">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <!-- CPU Card -->
            <div class="component-card p-4 text-center relative" data-component="cpu">
                <div class="component-indicator">
                    <i class="fas fa-check text-xs"></i>
                </div>
                <div class="icon-circle w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-microchip text-2xl text-red-500"></i>
                </div>
                <h3 class="orbitron font-bold uppercase mb-1">CPU</h3>
                <p class="text-xs text-gray-400">Processor</p>
                <span id="cpu-status" class="text-xs block mt-2 text-gray-400">Select</span>
            </div>
            
            <!-- Motherboard Card -->
            <div class="component-card p-4 text-center relative" data-component="motherboard">
                <div class="component-indicator">
                    <i class="fas fa-check text-xs"></i>
                </div>
                <div class="icon-circle w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-memory text-2xl text-red-500"></i>
                </div>
                <h3 class="orbitron font-bold uppercase mb-1">Motherboard</h3>
                <p class="text-xs text-gray-400">Mainboard</p>
                <span id="motherboard-status" class="text-xs block mt-2 text-gray-400">Select CPU first</span>
            </div>
            
            <!-- GPU Card -->
            <div class="component-card p-4 text-center relative" data-component="gpu">
                <div class="component-indicator">
                    <i class="fas fa-check text-xs"></i>
                </div>
                <div class="icon-circle w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-tv text-2xl text-red-500"></i>
                </div>
                <h3 class="orbitron font-bold uppercase mb-1">GPU</h3>
                <p class="text-xs text-gray-400">Graphics Card</p>
                <span id="gpu-status" class="text-xs block mt-2 text-gray-400">Select Motherboard first</span>
            </div>
            
            <!-- RAM Card -->
            <div class="component-card p-4 text-center relative" data-component="ram">
                <div class="component-indicator">
                    <i class="fas fa-check text-xs"></i>
                </div>
                <div class="icon-circle w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-memory text-2xl text-red-500"></i>
                </div>
                <h3 class="orbitron font-bold uppercase mb-1">RAM</h3>
                <p class="text-xs text-gray-400">Memory</p>
                <span id="ram-status" class="text-xs block mt-2 text-gray-400">Select GPU first</span>
            </div>
            
            <!-- Storage Card -->
            <div class="component-card p-4 text-center relative" data-component="storage">
                <div class="component-indicator">
                    <i class="fas fa-check text-xs"></i>
                </div>
                <div class="icon-circle w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-hdd text-2xl text-red-500"></i>
                </div>
                <h3 class="orbitron font-bold uppercase mb-1">Storage</h3>
                <p class="text-xs text-gray-400">SSD/HDD</p>
                <span id="storage-status" class="text-xs block mt-2 text-gray-400">Select RAM first</span>
            </div>
            
            <!-- Power Supply Card -->
            <div class="component-card p-4 text-center relative" data-component="power-supply">
                <div class="component-indicator">
                    <i class="fas fa-check text-xs"></i>
                </div>
                <div class="icon-circle w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-bolt text-2xl text-red-500"></i>
                </div>
                <h3 class="orbitron font-bold uppercase mb-1">Power</h3>
                <p class="text-xs text-gray-400">Power Supply</p>
                <span id="power-supply-status" class="text-xs block mt-2 text-gray-400">Select Storage first</span>
            </div>
        </div>
    </div>

    <!-- Configuration Panel -->
    <div class="container mx-auto px-4 mb-8">
        <div class="bg-gray-900 rounded-lg p-6 shadow-lg">
            <!-- Current Component Selection Title -->
            <div id="current-component-header" class="category-header text-white p-3 font-bold text-xl uppercase mb-6 rounded-lg">
                <i class="fas fa-microchip mr-2"></i> SELECT YOUR CPU
            </div>
            
            <!-- Component Selection Area -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Selection Controls -->
                <div class="lg:col-span-2">
                    <!-- CPU Select -->
                    <div id="cpu-select-container" class="mb-4">
                        <div class="flex flex-col md:flex-row mb-2">
                            <label class="block text-gray-400 mb-2 md:mb-0 md:w-1/4">CPU Model:</label>
                            <select id="cpu-select" class="custom-select w-full md:w-3/4 p-3">
                                <option value="">Select CPU</option>
                                @foreach ($cpus as $cpu)
                                    <option value="{{ $cpu->id }}" data-price="{{ $cpu->price }}">{{ $cpu->name }} (LKR {{ $cpu->price ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="cpu-prices" class="price-display p-3 rounded-lg text-sm ml-0 md:ml-1/4 mt-2"></div>
                    </div>

                    <!-- Motherboard Select -->
                    <div id="motherboard-select-container" class="mb-4 hidden">
                        <div class="flex flex-col md:flex-row mb-2">
                            <label class="block text-gray-400 mb-2 md:mb-0 md:w-1/4">Motherboard:</label>
                            <select id="motherboard-select" class="custom-select w-full md:w-3/4 p-3" disabled>
                                <option value="">Select Motherboard</option>
                            </select>
                        </div>
                        <div id="motherboard-prices" class="price-display p-3 rounded-lg text-sm ml-0 md:ml-1/4 mt-2"></div>
                    </div>

                    <!-- GPU Select -->
                    <div id="gpu-select-container" class="mb-4 hidden">
                        <div class="flex flex-col md:flex-row mb-2">
                            <label class="block text-gray-400 mb-2 md:mb-0 md:w-1/4">Graphics Card:</label>
                            <select id="gpu-select" class="custom-select w-full md:w-3/4 p-3" disabled>
                                <option value="">Select GPU</option>
                            </select>
                        </div>
                        <div id="gpu-prices" class="price-display p-3 rounded-lg text-sm ml-0 md:ml-1/4 mt-2"></div>
                    </div>

                    <!-- RAM Checkboxes -->
                    <div id="ram-select-container" class="mb-4 hidden">
                        <div class="flex flex-col md:flex-row mb-2">
                            <label class="block text-gray-400 mb-2 md:mb-0 md:w-1/4">Memory:</label>
                            <div class="w-full md:w-3/4">
                                <div id="ram-checkboxes" class="checkbox-container p-3" style="display: none;">
                                    <!-- Checkboxes will be populated dynamically -->
                                </div>
                            </div>
                        </div>
                        <div id="ram-prices" class="price-display p-3 rounded-lg text-sm ml-0 md:ml-1/4 mt-2"></div>
                    </div>

                    <!-- Storage Checkboxes -->
                    <div id="storage-select-container" class="mb-4 hidden">
                        <div class="flex flex-col md:flex-row mb-2">
                            <label class="block text-gray-400 mb-2 md:mb-0 md:w-1/4">Storage:</label>
                            <div class="w-full md:w-3/4">
                                <div id="storage-checkboxes" class="checkbox-container p-3" style="display: none;">
                                    <!-- Checkboxes will be populated dynamically -->
                                </div>
                            </div>
                        </div>
                        <div id="storage-prices" class="price-display p-3 rounded-lg text-sm ml-0 md:ml-1/4 mt-2"></div>
                    </div>

                    <!-- Power Supply Select -->
                    <div id="power-supply-select-container" class="mb-4 hidden">
                        <div class="flex flex-col md:flex-row mb-2">
                            <label class="block text-gray-400 mb-2 md:mb-0 md:w-1/4">Power Supply:</label>
                            <select id="power-supply-select" class="custom-select w-full md:w-3/4 p-3" disabled>
                                <option value="">Select Power Supply</option>
                            </select>
                        </div>
                        <div id="power-supply-prices" class="price-display p-3 rounded-lg text-sm ml-0 md:ml-1/4 mt-2"></div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-6">
                        <button id="prev-step-btn" class="bg-gray-700 text-white font-bold uppercase py-2 px-6 rounded hover:bg-gray-600 transition-colors duration-300 hidden">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button id="next-step-btn" class="bg-red-600 text-white font-bold uppercase py-2 px-6 rounded hover:bg-red-700 transition-colors duration-300">
                            Next <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Running Total and Component Info -->
                <div class="bg-gray-800 rounded-lg p-4 shadow-inner">
                    <h3 class="orbitron text-xl font-bold mb-4 text-center border-b border-red-500 pb-2">RUNNING TOTAL</h3>
                    <div id="running-total-details" class="mb-4">
                        <p class="flex justify-between py-1 border-b border-gray-700">
                            <span class="text-gray-400">CPU:</span>
                            <span id="total-cpu" class="font-mono">-</span>
                        </p>
                        <p class="flex justify-between py-1 border-b border-gray-700">
                            <span class="text-gray-400">Motherboard:</span>
                            <span id="total-motherboard" class="font-mono">-</span>
                        </p>
                        <p class="flex justify-between py-1 border-b border-gray-700">
                            <span class="text-gray-400">GPU:</span>
                            <span id="total-gpu" class="font-mono">-</span>
                        </p>
                        <p class="flex justify-between py-1 border-b border-gray-700">
                            <span class="text-gray-400">RAM:</span>
                            <span id="total-ram" class="font-mono">-</span>
                        </p>
                        <p class="flex justify-between py-1 border-b border-gray-700">
                            <span class="text-gray-400">Storage:</span>
                            <span id="total-storage" class="font-mono">-</span>
                        </p>
                        <p class="flex justify-between py-1 border-b border-gray-700">
                            <span class="text-gray-400">Power Supply:</span>
                            <span id="total-power-supply" class="font-mono">-</span>
                        </p>
                    </div>
                    <div class="p-3 bg-gray-900 rounded-lg">
                        <p class="flex justify-between items-center">
                            <span class="text-lg font-bold text-white">TOTAL:</span>
                            <span id="final-total" class="text-xl font-bold font-mono text-red-500">LKR 0.00</span>
                        </p>
                    </div>
                    <div class="mt-6 text-center">
                        <button id="show-build-btn" class="glowing-btn bg-gradient-to-r from-red-600 to-pink-600 text-white font-bold uppercase py-3 px-6 rounded-lg hover:from-red-700 hover:to-pink-700 transition-colors duration-300 w-full shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:from-gray-700 disabled:to-gray-700" disabled>
                            <i class="fas fa-desktop mr-2"></i> FINALIZE BUILD
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Build Summary Section -->
    <div id="build-summary" class="container mx-auto px-4 mb-8 hidden">
        <div class="build-summary p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-4">
                <h2 class="orbitron text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-pink-600">YOUR CUSTOM PC BUILD</h2>
                <button id="close-summary" class="text-gray-400 hover:text-white transition-colors duration-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="h-1 w-full bg-gradient-to-r from-red-500 to-pink-600 rounded-full mb-6"></div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <div id="build-details" class="text-gray-300 space-y-4"></div>
                </div>
                <div class="bg-gray-800 p-4 rounded-lg">
                    <h3 class="orbitron text-xl font-bold mb-4 text-center">BUILD SUMMARY</h3>
                    <div id="build-summary-specs" class="space-y-2 mb-4">
                        <!-- Generated dynamically -->
                    </div>
                    <div class="border-t border-gray-700 pt-4 mt-4">
                        <p class="flex justify-between items-center text-xl">
                            <span class="font-bold">TOTAL:</span>
                            <span id="summary-total" class="font-bold font-mono text-red-500"></span>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row justify-center space-y-4 md:space-y-0 md:space-x-4">
                <div class="flex-1">
                    <form id="save-build-form" action="{{ route('build.save') }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="cpu_id" id="cpu_id">
                        <input type="hidden" name="motherboard_id" id="motherboard_id">
                        <input type="hidden" name="gpu_id" id="gpu_id">
                        <input type="hidden" name="ram_ids[]" id="ram_ids">
                        <input type="hidden" name="storage_ids[]" id="storage_ids">
                        <input type="hidden" name="power_supply_id" id="power_supply_id">
                        <input type="hidden" name="total_price" id="total_price">
                        
                        <div class="mb-4">
                            <label for="build_name" class="block text-gray-400 mb-2">Build Name:</label>
                            <input type="text" id="build_name" name="name" class="custom-select w-full p-3" placeholder="Enter a name for your build">
                        </div>
                        <button type="submit" id="save-build-btn" class="glowing-btn bg-gradient-to-r from-blue-600 to-blue-400 text-white font-bold uppercase py-3 px-6 rounded-lg hover:from-blue-700 hover:to-blue-500 transition-colors duration-300 w-full shadow-lg">
                            <i class="fas fa-save mr-2"></i> SAVE BUILD
                        </button>
                    </form>
                </div>
                <div class="flex-1">
                    <button id="share-build-btn" class="bg-gradient-to-r from-purple-600 to-purple-400 text-white font-bold uppercase py-3 px-6 rounded-lg hover:from-purple-700 hover:to-purple-500 transition-colors duration-300 w-full shadow-lg">
                        <i class="fas fa-share-alt mr-2"></i> SHARE BUILD
                    </button>
                </div>
                <div class="flex-1">
                    <button id="new-build-btn" class="bg-gradient-to-r from-gray-700 to-gray-600 text-white font-bold uppercase py-3 px-6 rounded-lg hover:from-gray-800 hover:to-gray-700 transition-colors duration-300 w-full shadow-lg">
                        <i class="fas fa-plus mr-2"></i> NEW BUILD
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        
// Object to store the selected build
const selectedBuild = {
    cpu: null,
    motherboard: null,
    gpu: null,
    rams: [],
    storages: [],
    powerSupply: null
};

// Current step in the selection process
let currentStep = 0;
const steps = ['cpu', 'motherboard', 'gpu', 'ram', 'storage', 'power-supply'];
const stepContainers = steps.map(step => document.getElementById(`${step}-select-container`));
const stepHeaders = [
    '<i class="fas fa-microchip mr-2"></i> SELECT YOUR CPU',
    '<i class="fas fa-memory mr-2"></i> SELECT YOUR MOTHERBOARD',
    '<i class="fas fa-tv mr-2"></i> SELECT YOUR GPU',
    '<i class="fas fa-memory mr-2"></i> SELECT YOUR RAM',
    '<i class="fas fa-hdd mr-2"></i> SELECT YOUR STORAGE',
    '<i class="fas fa-bolt mr-2"></i> SELECT YOUR POWER SUPPLY'
];

// DOM Elements
const progressBar = document.getElementById('progress-bar');
const prevStepBtn = document.getElementById('prev-step-btn');
const nextStepBtn = document.getElementById('next-step-btn');
const showBuildBtn = document.getElementById('show-build-btn');
const currentComponentHeader = document.getElementById('current-component-header');
const buildSummary = document.getElementById('build-summary');
const closeSummaryBtn = document.getElementById('close-summary');
const newBuildBtn = document.getElementById('new-build-btn');
const shareBuildBtn = document.getElementById('share-build-btn');

// Update progress bar based on selected components
function updateProgressBar() {
    const totalSteps = steps.length;
    let completedSteps = 0;
    if (selectedBuild.cpu) completedSteps++;
    if (selectedBuild.motherboard) completedSteps++;
    if (selectedBuild.gpu) completedSteps++;
    if (selectedBuild.rams.length > 0) completedSteps++;
    if (selectedBuild.storages.length > 0) completedSteps++;
    if (selectedBuild.powerSupply) completedSteps++;
    const progress = (completedSteps / totalSteps) * 100;
    progressBar.style.width = `${progress}%`;
}

// Update component card status
function updateComponentStatus(component, statusText, isSelected) {
    const statusElement = document.getElementById(`${component}-status`);
    const card = document.querySelector(`.component-card[data-component="${component}"]`);
    const iconCircle = card.querySelector('.icon-circle');
    statusElement.textContent = statusText;
    if (isSelected) {
        statusElement.classList.remove('text-gray-400');
        statusElement.classList.add('text-green-500');
        card.classList.add('completed');
        iconCircle.classList.add('selected');
    } else {
        statusElement.classList.remove('text-green-500');
        statusElement.classList.add('text-gray-400');
        card.classList.remove('completed');
        iconCircle.classList.remove('selected');
    }
}

// Display price for a single selected component
function displayPrices(componentId, price, name) {
    const priceDiv = document.getElementById(`${componentId}-prices`);
    priceDiv.innerHTML = '';
    if (price !== null && price !== undefined) {
        priceDiv.innerHTML = `<p class="text-gray-300">${name} - LKR ${parseFloat(price).toFixed(2)}</p>`;
    } else {
        priceDiv.innerHTML = '<p class="text-yellow-500">No price available for this component.</p>';
    }
    updateRunningTotal();
}

// Display prices for multiple components (e.g., RAMs, Storages)
function displayMultiplePrices(componentId, components) {
    const priceDiv = document.getElementById(`${componentId}-prices`);
    priceDiv.innerHTML = '';
    if (components && components.length > 0) {
        const ul = document.createElement('ul');
        ul.className = 'list-disc pl-5 text-gray-300';
        let totalPrice = 0;
        components.forEach(component => {
            const price = parseFloat(component.price) || 0;
            totalPrice += price;
            const li = document.createElement('li');
            li.textContent = `${component.name} - LKR ${price.toFixed(2)}`;
            ul.appendChild(li);
        });
        priceDiv.appendChild(ul);
        priceDiv.innerHTML += `<p class="mt-2 font-bold">Total: LKR ${totalPrice.toFixed(2)}</p>`;
    } else {
        priceDiv.innerHTML = '<p class="text-yellow-500">No components selected.</p>';
    }
    updateRunningTotal();
}

// Update running total
function updateRunningTotal() {
    let total = 0;
    const updateTotalField = (component, fieldId, isMultiple = false) => {
        const field = document.getElementById(fieldId);
        if (isMultiple) {
            if (component && component.length > 0) {
                const componentTotal = component.reduce((sum, item) => sum + (parseFloat(item.price) || 0), 0);
                field.textContent = `LKR ${componentTotal.toFixed(2)}`;
                total += componentTotal;
            } else {
                field.textContent = '-';
            }
        } else {
            if (component) {
                const price = parseFloat(component.price) || 0;
                field.textContent = `LKR ${price.toFixed(2)}`;
                total += price;
            } else {
                field.textContent = '-';
            }
        }
    };

    updateTotalField(selectedBuild.cpu, 'total-cpu');
    updateTotalField(selectedBuild.motherboard, 'total-motherboard');
    updateTotalField(selectedBuild.gpu, 'total-gpu');
    updateTotalField(selectedBuild.rams, 'total-ram', true);
    updateTotalField(selectedBuild.storages, 'total-storage', true);
    updateTotalField(selectedBuild.powerSupply, 'total-power-supply');

    document.getElementById('final-total').textContent = `LKR ${total.toFixed(2)}`;
    showBuildBtn.disabled = !steps.every(step => {
        const key = step === 'power-supply' ? 'powerSupply' : step;
        return selectedBuild[key] && (Array.isArray(selectedBuild[key]) ? selectedBuild[key].length > 0 : true);
    });
}

// Reset subsequent sections when a selection changes
function resetSelections(startingFrom) {
    const startIndex = steps.indexOf(startingFrom);
    for (let i = startIndex; i < steps.length; i++) {
        const section = steps[i];
        const container = stepContainers[i];
        container.classList.add('hidden');
        if (section === 'ram') {
            const ramCheckboxes = document.getElementById('ram-checkboxes');
            ramCheckboxes.style.display = 'none';
            ramCheckboxes.innerHTML = '';
            selectedBuild.rams = [];
            displayMultiplePrices('ram', selectedBuild.rams);
            updateComponentStatus('ram', 'Select GPU first', false);
        } else if (section === 'storage') {
            const storageCheckboxes = document.getElementById('storage-checkboxes');
            storageCheckboxes.style.display = 'none';
            storageCheckboxes.innerHTML = '';
            selectedBuild.storages = [];
            displayMultiplePrices('storage', selectedBuild.storages);
            updateComponentStatus('storage', 'Select RAM first', false);
        } else {
            const select = document.getElementById(`${section}-select`);
            select.innerHTML = `<option value="">Select ${section.replace('-', ' ').toUpperCase()}</option>`;
            select.disabled = true;
            document.getElementById(`${section}-prices`).innerHTML = '';
            const componentKey = section.replace('power-supply', 'powerSupply');
            selectedBuild[componentKey] = null;
            updateComponentStatus(section, `Select ${steps[i-1] ? steps[i-1].replace('-', ' ').toUpperCase() : 'CPU'} first`, false);
        }
    }
    updateProgressBar();
    updateRunningTotal();
}

// Show specific step
function showStep(stepIndex) {
    currentStep = stepIndex;
    stepContainers.forEach((container, index) => {
        container.classList.toggle('hidden', index !== stepIndex);
    });
    currentComponentHeader.innerHTML = stepHeaders[stepIndex];
    prevStepBtn.classList.toggle('hidden', stepIndex === 0);
    nextStepBtn.innerHTML = stepIndex === steps.length - 1 ? 'Finish <i class="fas fa-check ml-2"></i>' : 'Next <i class="fas fa-arrow-right ml-2"></i>';

    // Update Next button state based on current step's selection
    updateNextButtonState();
}

// Update the Next button's disabled state based on the current step's selection
function updateNextButtonState() {
    const currentComponent = steps[currentStep];
    let isSelected = false;

    if (currentComponent === 'ram') {
        isSelected = selectedBuild.rams.length > 0;
    } else if (currentComponent === 'storage') {
        isSelected = selectedBuild.storages.length > 0;
    } else {
        const componentKey = currentComponent === 'power-supply' ? 'powerSupply' : currentComponent;
        isSelected = !!selectedBuild[componentKey];
    }

    console.log(`updateNextButtonState - Step: ${currentComponent}, Is Selected: ${isSelected}, Selected Value:`, 
        currentComponent === 'ram' ? selectedBuild.rams : 
        currentComponent === 'storage' ? selectedBuild.storages : 
        selectedBuild[currentComponent === 'power-supply' ? 'powerSupply' : currentComponent]);

    nextStepBtn.disabled = !isSelected;
}

// Function to display the selected build
function showBuild() {
    const buildDetails = document.getElementById('build-details');
    const buildSummarySpecs = document.getElementById('build-summary-specs');
    let totalPrice = 0;
    let buildHtml = '';
    let summaryHtml = '';

    const formatComponent = (componentName, component) => {
        if (!component) return `<p><span class="font-bold">${componentName}:</span> Not selected</p>`;
        const price = parseFloat(component.price) || 0;
        totalPrice += price;
        summaryHtml += `<p class="flex justify-between py-1"><span class="text-gray-400">${componentName}:</span><span class="font-mono">LKR ${price.toFixed(2)}</span></p>`;
        return `<p><span class="font-bold">${componentName}:</span> ${component.name} (LKR ${price.toFixed(2)})</p>`;
    };

    const formatMultipleComponents = (componentName, components) => {
        if (!components || components.length === 0) return `<p><span class="font-bold">${componentName}:</span> Not selected</p>`;
        let html = `<p><span class="font-bold">${componentName}:</span></p><ul class="list-disc pl-5">`;
        let componentTotal = 0;
        components.forEach(component => {
            const price = parseFloat(component.price) || 0;
            componentTotal += price;
            html += `<li>${component.name} (LKR ${price.toFixed(2)})</li>`;
        });
        html += `</ul><p class="font-bold">Total ${componentName}: LKR ${componentTotal.toFixed(2)}</p>`;
        totalPrice += componentTotal;
        summaryHtml += `<p class="flex justify-between py-1"><span class="text-gray-400">${componentName}:</span><span class="font-mono">LKR ${componentTotal.toFixed(2)}</span></p>`;
        return html;
    };

    const allSelected = selectedBuild.cpu && selectedBuild.motherboard && selectedBuild.gpu && selectedBuild.rams.length > 0 && selectedBuild.storages.length > 0 && selectedBuild.powerSupply;
    if (!allSelected) {
        buildHtml = '<p class="text-red-500">Please select all components before finalizing the build.</p>';
        buildDetails.innerHTML = buildHtml;
        buildSummary.classList.remove('hidden');
        return;
    }

    buildHtml += formatComponent('CPU', selectedBuild.cpu);
    buildHtml += formatComponent('Motherboard', selectedBuild.motherboard);
    buildHtml += formatComponent('GPU', selectedBuild.gpu);
    buildHtml += formatMultipleComponents('RAM', selectedBuild.rams);
    buildHtml += formatMultipleComponents('Storage', selectedBuild.storages);
    buildHtml += formatComponent('Power Supply', selectedBuild.powerSupply);

    buildDetails.innerHTML = buildHtml;
    buildSummarySpecs.innerHTML = summaryHtml;
    document.getElementById('summary-total').textContent = `LKR ${totalPrice.toFixed(2)}`;
    buildSummary.classList.remove('hidden');

    // Populate form fields for saving
    document.getElementById('cpu_id').value = selectedBuild.cpu.id;
    document.getElementById('motherboard_id').value = selectedBuild.motherboard.id;
    document.getElementById('gpu_id').value = selectedBuild.gpu.id;
    const ramIdsInput = document.getElementById('ram_ids');
    const storageIdsInput = document.getElementById('storage_ids');
    ramIdsInput.removeAttribute('name');
    storageIdsInput.removeAttribute('name');
    selectedBuild.rams.forEach((ram, index) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ram_ids[]';
        input.value = ram.id;
        ramIdsInput.parentNode.appendChild(input);
    });
    selectedBuild.storages.forEach((storage, index) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'storage_ids[]';
        input.value = storage.id;
        storageIdsInput.parentNode.appendChild(input);
    });
    document.getElementById('power_supply_id').value = selectedBuild.powerSupply.id;
    document.getElementById('total_price').value = totalPrice;
}

// CPU Selection
document.getElementById('cpu-select').addEventListener('change', function() {
    const cpuId = this.value;
    resetSelections('motherboard');
    if (cpuId) {
        const selectedOption = this.options[this.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price') || 0);
        selectedBuild.cpu = {
            id: cpuId,
            name: selectedOption.textContent.split(' (')[0],
            price: price
        };
        displayPrices('cpu', price, selectedBuild.cpu.name);
        updateComponentStatus('cpu', selectedBuild.cpu.name, true);

        fetch(`/build/motherboards/${cpuId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            },
            credentials: 'include'
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP error! Status: ${response.status} - ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            const motherboardSelect = document.getElementById('motherboard-select');
            motherboardSelect.innerHTML = '<option value="">Select Motherboard</option>';
            if (data && Array.isArray(data) && data.length > 0) {
                data.forEach(motherboard => {
                    const option = document.createElement('option');
                    option.value = motherboard.id;
                    option.textContent = `${motherboard.name} (LKR ${motherboard.price ?? 'N/A'})`;
                    option.setAttribute('data-price', motherboard.price || 0);
                    motherboardSelect.appendChild(option);
                });
                motherboardSelect.disabled = false;
                updateComponentStatus('motherboard', 'Select', false);
            } else {
                motherboardSelect.innerHTML = '<option value="">No compatible motherboards found</option>';
                motherboardSelect.disabled = true;
                updateComponentStatus('motherboard', 'No compatible options', false);
            }
        })
        .catch(error => {
            console.error('Error fetching motherboards:', error);
            const motherboardSelect = document.getElementById('motherboard-select');
            motherboardSelect.innerHTML = `<option value="">Error: ${error.message}</option>`;
            motherboardSelect.disabled = true;
            updateComponentStatus('motherboard', 'Error', false);
        });
    } else {
        selectedBuild.cpu = null;
        document.getElementById('cpu-prices').innerHTML = '';
        updateComponentStatus('cpu', 'Select', false);
    }
    updateProgressBar();
    updateNextButtonState();
});

// Motherboard Selection
document.getElementById('motherboard-select').addEventListener('change', function() {
    const motherboardId = this.value;
    const cpuId = document.getElementById('cpu-select').value;
    resetSelections('gpu');
    if (motherboardId) {
        const selectedOption = this.options[this.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price') || 0);
        selectedBuild.motherboard = {
            id: motherboardId,
            name: selectedOption.textContent.split(' (')[0],
            price: price
        };
        displayPrices('motherboard', price, selectedBuild.motherboard.name);
        updateComponentStatus('motherboard', selectedBuild.motherboard.name, true);

        fetch(`/build/gpus/${cpuId}/${motherboardId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            },
            credentials: 'include'
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP error! Status: ${response.status} - ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            const gpuSelect = document.getElementById('gpu-select');
            gpuSelect.innerHTML = '<option value="">Select GPU</option>';
            if (data && Array.isArray(data) && data.length > 0) {
                data.forEach(gpu => {
                    const option = document.createElement('option');
                    option.value = gpu.id;
                    option.textContent = `${gpu.name} (LKR ${gpu.price ?? 'N/A'})`;
                    option.setAttribute('data-price', gpu.price || 0);
                    gpuSelect.appendChild(option);
                });
                gpuSelect.disabled = false;
                updateComponentStatus('gpu', 'Select', false);
            } else {
                gpuSelect.innerHTML = '<option value="">No compatible GPUs found</option>';
                gpuSelect.disabled = true;
                updateComponentStatus('gpu', 'No compatible options', false);
            }
        })
        .catch(error => {
            console.error('Error fetching GPUs:', error);
            const gpuSelect = document.getElementById('gpu-select');
            gpuSelect.innerHTML = `<option value="">Error: ${error.message}</option>`;
            gpuSelect.disabled = true;
            updateComponentStatus('gpu', 'Error', false);
        });
    } else {
        selectedBuild.motherboard = null;
        document.getElementById('motherboard-prices').innerHTML = '';
        updateComponentStatus('motherboard', 'Select', false);
    }
    updateProgressBar();
    updateNextButtonState();
});

// GPU Selection
document.getElementById('gpu-select').addEventListener('change', function() {
    const gpuId = this.value;
    const motherboardId = document.getElementById('motherboard-select').value;
    resetSelections('ram');
    if (gpuId) {
        const selectedOption = this.options[this.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price') || 0);
        selectedBuild.gpu = {
            id: gpuId,
            name: selectedOption.textContent.split(' (')[0],
            price: price
        };
        displayPrices('gpu', price, selectedBuild.gpu.name);
        updateComponentStatus('gpu', selectedBuild.gpu.name, true);

        fetch(`/build/rams/${motherboardId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            },
            credentials: 'include'
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP error! Status: ${response.status} - ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            const ramCheckboxes = document.getElementById('ram-checkboxes');
            ramCheckboxes.innerHTML = '';
            if (data && Array.isArray(data) && data.length > 0) {
                data.forEach(ram => {
                    const div = document.createElement('div');
                    div.className = 'mb-2';
                    div.innerHTML = `
                        <label class="flex items-center">
                            <input type="checkbox" class="custom-checkbox ram-checkbox" value="${ram.id}" data-price="${ram.price || 0}">
                            <span class="text-gray-300">${ram.name} (LKR ${ram.price ?? 'N/A'})</span>
                        </label>
                    `;
                    ramCheckboxes.appendChild(div);
                });
                ramCheckboxes.style.display = 'block';
                updateComponentStatus('ram', 'Select at least one', false);

                // Add event listeners to RAM checkboxes
                document.querySelectorAll('.ram-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const selectedRams = Array.from(document.querySelectorAll('.ram-checkbox:checked')).map(cb => ({
                            id: cb.value,
                            name: cb.nextElementSibling.textContent.split(' (')[0],
                            price: parseFloat(cb.getAttribute('data-price')) || 0
                        }));
                        selectedBuild.rams = selectedRams;
                        console.log('RAM selected:', selectedBuild.rams);
                        displayMultiplePrices('ram', selectedBuild.rams);
                        updateComponentStatus('ram', selectedRams.length > 0 ? `${selectedRams.length} selected` : 'Select at least one', selectedRams.length > 0);
                        updateNextButtonState();

                        // Fetch storage options if RAMs are selected
                        if (selectedRams.length > 0) {
                            const ramId = selectedRams[0].id || 0;
                            fetch(`/build/storages/${ramId}?motherboard_id=${motherboardId}`, {
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/json'
                                },
                                credentials: 'include'
                            })
                            .then(response => {
                                if (!response.ok) {
                                    return response.text().then(text => {
                                        throw new Error(`HTTP error! Status: ${response.status} - ${text}`);
                                    });
                                }
                                return response.json();
                            })
                            .then(data => {
                                const storageCheckboxes = document.getElementById('storage-checkboxes');
                                storageCheckboxes.innerHTML = '';
                                if (data && Array.isArray(data) && data.length > 0) {
                                    data.forEach(storage => {
                                        const div = document.createElement('div');
                                        div.className = 'mb-2';
                                        div.innerHTML = `
                                            <label class="flex items-center">
                                                <input type="checkbox" class="custom-checkbox storage-checkbox" value="${storage.id}" data-price="${storage.price || 0}">
                                                <span class="text-gray-300">${storage.name} (LKR ${storage.price ?? 'N/A'})</span>
                                            </label>
                                        `;
                                        storageCheckboxes.appendChild(div);
                                    });
                                    storageCheckboxes.style.display = 'block';
                                    updateComponentStatus('storage', 'Select at least one', false);

                                    // Add event listeners to Storage checkboxes
                                    document.querySelectorAll('.storage-checkbox').forEach(checkbox => {
                                        checkbox.addEventListener('change', function() {
                                            const selectedStorages = Array.from(document.querySelectorAll('.storage-checkbox:checked')).map(cb => ({
                                                id: cb.value,
                                                name: cb.nextElementSibling.textContent.split(' (')[0],
                                                price: parseFloat(cb.getAttribute('data-price')) || 0
                                            }));
                                            selectedBuild.storages = selectedStorages;
                                            console.log('Storage selected:', selectedBuild.storages);
                                            displayMultiplePrices('storage', selectedBuild.storages);
                                            updateComponentStatus('storage', selectedStorages.length > 0 ? `${selectedStorages.length} selected` : 'Select at least one', selectedStorages.length > 0);
                                            updateNextButtonState();

                                            if (selectedStorages.length > 0) {
                                                const storageId = selectedStorages[0].id || 0;
                                                const cpuId = document.getElementById('cpu-select').value;
                                                fetch(`/build/power-supplies/${storageId}?gpu_id=${gpuId}&cpu_id=${cpuId}&motherboard_id=${motherboardId}`, {
                                                    method: 'GET',
                                                    headers: {
                                                        'Accept': 'application/json'
                                                    },
                                                    credentials: 'include'
                                                })
                                                .then(response => {
                                                    if (!response.ok) {
                                                        return response.text().then(text => {
                                                            throw new Error(`HTTP error! Status: ${response.status} - ${text}`);
                                                        });
                                                    }
                                                    return response.json();
                                                })
                                                .then(data => {
                                                    const psuSelect = document.getElementById('power-supply-select');
                                                    psuSelect.innerHTML = '<option value="">Select Power Supply</option>';
                                                    if (data && Array.isArray(data) && data.length > 0) {
                                                        data.forEach(psu => {
                                                            const option = document.createElement('option');
                                                            option.value = psu.id;
                                                            option.textContent = `${psu.name} (LKR ${psu.price ?? 'N/A'})`;
                                                            option.setAttribute('data-price', psu.price || 0);
                                                            psuSelect.appendChild(option);
                                                        });
                                                        psuSelect.disabled = false;
                                                        updateComponentStatus('power-supply', 'Select', false);
                                                    } else {
                                                        psuSelect.innerHTML = '<option value="">No compatible power supplies found</option>';
                                                        psuSelect.disabled = true;
                                                        updateComponentStatus('power-supply', 'No compatible options', false);
                                                    }
                                                })
                                                .catch(error => {
                                                    console.error('Error fetching power supplies:', error);
                                                    const psuSelect = document.getElementById('power-supply-select');
                                                    psuSelect.innerHTML = `<option value="">Error: ${error.message}</option>`;
                                                    psuSelect.disabled = true;
                                                    updateComponentStatus('power-supply', 'Error', false);
                                                });
                                            } else {
                                                resetSelections('power-supply');
                                            }
                                            updateProgressBar();
                                        });
                                    });
                                } else {
                                    storageCheckboxes.innerHTML = '<p class="text-yellow-500 p-2">No compatible storages found</p>';
                                    storageCheckboxes.style.display = 'block';
                                    updateComponentStatus('storage', 'No compatible options', false);
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching storages:', error);
                                const storageCheckboxes = document.getElementById('storage-checkboxes');
                                storageCheckboxes.innerHTML = `<p class="text-red-500 p-2">Error: ${error.message}</p>`;
                                storageCheckboxes.style.display = 'block';
                                updateComponentStatus('storage', 'Error', false);
                            });
                        } else {
                            resetSelections('storage');
                        }
                        updateProgressBar();
                    });
                });
            } else {
                ramCheckboxes.innerHTML = '<p class="text-yellow-500 p-2">No compatible RAMs found</p>';
                ramCheckboxes.style.display = 'block';
                updateComponentStatus('ram', 'No compatible options', false);
            }
        })
        .catch(error => {
            console.error('Error fetching RAMs:', error);
            const ramCheckboxes = document.getElementById('ram-checkboxes');
            ramCheckboxes.innerHTML = `<p class="text-red-500 p-2">Error: ${error.message}</p>`;
            ramCheckboxes.style.display = 'block';
            updateComponentStatus('ram', 'Error', false);
        });
    } else {
        selectedBuild.gpu = null;
        document.getElementById('gpu-prices').innerHTML = '';
        updateComponentStatus('gpu', 'Select', false);
    }
    updateProgressBar();
    updateNextButtonState();
});

// Power Supply Selection
document.getElementById('power-supply-select').addEventListener('change', function() {
    const powerSupplyId = this.value;
    if (powerSupplyId) {
        const selectedOption = this.options[this.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price') || 0);
        selectedBuild.powerSupply = {
            id: powerSupplyId,
            name: selectedOption.textContent.split(' (')[0],
            price: price
        };
        displayPrices('power-supply', price, selectedBuild.powerSupply.name);
        updateComponentStatus('power-supply', selectedBuild.powerSupply.name, true);
    } else {
        selectedBuild.powerSupply = null;
        document.getElementById('power-supply-prices').innerHTML = '';
        updateComponentStatus('power-supply', 'Select', false);
    }
    updateProgressBar();
    updateNextButtonState();
});

// Navigation Buttons
prevStepBtn.addEventListener('click', () => {
    if (currentStep > 0) {
        showStep(currentStep - 1);
    }
});

nextStepBtn.addEventListener('click', () => {
    console.log(`Next button clicked. Current Step: ${currentStep}, Total Steps: ${steps.length}`);
    if (currentStep < steps.length - 1) {
        const currentComponent = steps[currentStep];
        let isSelected = false;

        if (currentComponent === 'ram') {
            isSelected = selectedBuild.rams.length > 0;
        } else if (currentComponent === 'storage') {
            isSelected = selectedBuild.storages.length > 0;
        } else {
            const componentKey = currentComponent === 'power-supply' ? 'powerSupply' : currentComponent;
            isSelected = !!selectedBuild[componentKey];
        }

        console.log(`nextStepBtn - Is Selected: ${isSelected}, Component: ${currentComponent}, Selected Value:`, 
            currentComponent === 'ram' ? selectedBuild.rams : 
            currentComponent === 'storage' ? selectedBuild.storages : 
            selectedBuild[currentComponent === 'power-supply' ? 'powerSupply' : currentComponent]);

        if (isSelected) {
            showStep(currentStep + 1);
        } else {
            console.log('Cannot proceed: Current step selection is incomplete.');
        }
    } else {
        showBuild();
    }
});

// Show Build Button
showBuildBtn.addEventListener('click', showBuild);

// Close Summary
closeSummaryBtn.addEventListener('click', () => {
    buildSummary.classList.add('hidden');
});

// New Build Button
newBuildBtn.addEventListener('click', () => {
    location.reload(); // Reload the page to start a new build
});

// Share Build Button (Placeholder)
shareBuildBtn.addEventListener('click', () => {
    alert('Share functionality coming soon! For now, you can copy the build details manually.');
});

// Initialize the first step
showStep(0);
updateProgressBar();


    </script>
          
