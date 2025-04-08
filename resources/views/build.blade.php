<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NextGen Computing</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Orbitron', sans-serif;
            background-color: #121212;
        }
        .icon-circle.bg-green-500 {
            background-color: #22c55e !important;
        }
    </style>
</head>
<body class="bg-gray-900 text-white">
    @include('include.header')

    <!-- Component Icons Row -->
    <div class="component-icons flex justify-around py-4 bg-gray-800">
        <div class="component-icon text-center" data-component="cpu">
            <div class="icon-circle w-16 h-16 bg-black rounded-full flex items-center justify-center transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                </svg>
            </div>
            <span class="block mt-2 text-sm font-bold uppercase">CPU</span>
        </div>
        <div class="component-icon text-center" data-component="motherboard">
            <div class="icon-circle w-16 h-16 bg-black rounded-full flex items-center justify-center transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="block mt-2 text-sm font-bold uppercase">MOTHERBOARD</span>
        </div>
        <div class="component-icon text-center" data-component="gpu">
            <div class="icon-circle w-16 h-16 bg-black rounded-full flex items-center justify-center transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
            <span class="block mt-2 text-sm font-bold uppercase">GPU</span>
        </div>
        <div class="component-icon text-center" data-component="ram">
            <div class="icon-circle w-16 h-16 bg-black rounded-full flex items-center justify-center transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 11h16M4 15h16M4 19h16" />
                </svg>
            </div>
            <span class="block mt-2 text-sm font-bold uppercase">RAM</span>
        </div>
        <div class="component-icon text-center" data-component="storage">
            <div class="icon-circle w-16 h-16 bg-black rounded-full flex items-center justify-center transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 12h14M5 16h14" />
                </svg>
            </div>
            <span class="block mt-2 text-sm font-bold uppercase">STORAGE</span>
        </div>
        <div class="component-icon text-center" data-component="power-supply">
            <div class="icon-circle w-16 h-16 bg-black rounded-full flex items-center justify-center transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span class="block mt-2 text-sm font-bold uppercase">POWER SUPPLY</span>
        </div>
    </div>

    <!-- Component Categories -->
    <div class="container mx-auto mt-6">
        <!-- CPU Category -->
        <div class="category-header bg-black text-white p-2 font-bold text-xl uppercase border-t-2 border-red-500 mb-4">CPU CATEGORY</div>
        <select id="cpu-select" class="w-full p-2 bg-gray-800 text-white rounded">
            <option value="">Select CPU</option>
            @foreach ($cpus as $cpu)
                <option value="{{ $cpu->id }}" data-prices='@json($cpu->prices)'>{{ $cpu->name }}</option>
            @endforeach
        </select>
        <div id="cpu-prices" class="mt-2"></div>

        <!-- Motherboard Category -->
        <div class="category-header bg-black text-white p-2 font-bold text-xl uppercase border-t-2 border-red-500 mb-4 mt-6">MOTHERBOARD CATEGORY</div>
        <select id="motherboard-select" class="w-full p-2 bg-gray-800 text-white rounded" disabled>
            <option value="">Select Motherboard</option>
        </select>
        <div id="motherboard-prices" class="mt-2"></div>

        <!-- GPU Category -->
        <div class="category-header bg-black text-white p-2 font-bold text-xl uppercase border-t-2 border-red-500 mb-4 mt-6">GPU CATEGORY</div>
        <select id="gpu-select" class="w-full p-2 bg-gray-800 text-white rounded" disabled>
            <option value="">Select GPU</option>
        </select>
        <div id="gpu-prices" class="mt-2"></div>

        <!-- RAM Category -->
        <div class="category-header bg-black text-white p-2 font-bold text-xl uppercase border-t-2 border-red-500 mb-4 mt-6">RAM CATEGORY</div>
        <select id="ram-select" class="w-full p-2 bg-gray-800 text-white rounded" disabled>
            <option value="">Select RAM</option>
        </select>
        <div id="ram-prices" class="mt-2"></div>

        <!-- Storage Category -->
        <div class="category-header bg-black text-white p-2 font-bold text-xl uppercase border-t-2 border-red-500 mb-4 mt-6">STORAGE CATEGORY</div>
        <select id="storage-select" class="w-full p-2 bg-gray-800 text-white rounded" disabled>
            <option value="">Select Storage</option>
        </select>
        <div id="storage-prices" class="mt-2"></div>

        <!-- Power Supply Category -->
        <div class="category-header bg-black text-white p-2 font-bold text-xl uppercase border-t-2 border-red-500 mb-4 mt-6">POWER SUPPLY CATEGORY</div>
        <select id="power-supply-select" class="w-full p-2 bg-gray-800 text-white rounded" disabled>
            <option value="">Select Power Supply</option>
        </select>
        <div id="power-supply-prices" class="mt-2"></div>

        <!-- Show Build Button -->
        <div class="mt-6 text-center">
            <button id="show-build-btn" class="bg-red-600 text-white font-bold uppercase py-2 px-6 rounded hover:bg-red-700 transition-colors duration-300">Show Build</button>
        </div>

        <!-- Build Summary Section -->
        <div id="build-summary" class="mt-6 bg-gray-800 p-4 rounded hidden">
            <h2 class="text-2xl font-bold uppercase border-b-2 border-red-500 pb-2 mb-4">Your PC Build</h2>
            <div id="build-details" class="text-gray-300"></div>
            <div class="mt-4 text-center">
                <form id="save-build-form" action="{{ route('build.save') }}" method="POST">
                    @csrf
                    <input type="hidden" name="cpu_id" id="cpu_id">
                    <input type="hidden" name="motherboard_id" id="motherboard_id">
                    <input type="hidden" name="gpu_id" id="gpu_id">
                    <input type="hidden" name="ram_id" id="ram_id">
                    <input type="hidden" name="storage_id" id="storage_id">
                    <input type="hidden" name="power_supply_id" id="power_supply_id">
                    <input type="hidden" name="total_price" id="total_price">
                    <input type="hidden" name="name" id="build_name">
                    <button type="submit" id="save-build-btn" class="bg-blue-600 text-white font-bold uppercase py-2 px-6 rounded hover:bg-blue-700 transition-colors duration-300 disabled:bg-gray-500 disabled:cursor-not-allowed" disabled>Save Build</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Object to store the selected build
        const selectedBuild = {
            cpu: null,
            motherboard: null,
            gpu: null,
            ram: null,
            storage: null,
            powerSupply: null
        };

        // Highlight selected component icon
        function selectComponent(component) {
            const icon = document.querySelector(`.component-icon[data-component="${component}"]`);
            if (icon) {
                icon.querySelector('.icon-circle').classList.add('bg-green-500');
            }
        }

        // Remove highlight from component icon
        function deselectComponent(component) {
            const icon = document.querySelector(`.component-icon[data-component="${component}"]`);
            if (icon) {
                icon.querySelector('.icon-circle').classList.remove('bg-green-500');
            }
        }

        // Display prices for a selected component
        function displayPrices(componentId, prices) {
            const priceDiv = document.getElementById(`${componentId}-prices`);
            priceDiv.innerHTML = '';
            if (prices && Array.isArray(prices) && prices.length > 0) {
                const ul = document.createElement('ul');
                ul.className = 'list-disc pl-5 text-gray-300';
                prices.forEach(price => {
                    if (price.retailer && price.retailer.name) {
                        const li = document.createElement('li');
                        li.innerHTML = `${price.retailer.name} - ${price.price} LKR <a href="${price.purchase_url}" target="_blank" class="text-blue-500 hover:underline">Buy</a>`;
                        ul.appendChild(li);
                    }
                });
                priceDiv.appendChild(ul);
            } else {
                priceDiv.innerHTML = '<p class="text-yellow-500">No prices available for this component.</p>';
            }
        }

        // Reset subsequent dropdowns when a selection changes
        function resetSelections(startingFrom) {
            const selects = ['motherboard-select', 'gpu-select', 'ram-select', 'storage-select', 'power-supply-select'];
            const components = ['motherboard', 'gpu', 'ram', 'storage', 'power-supply'];
            const priceDivs = ['motherboard-prices', 'gpu-prices', 'ram-prices', 'storage-prices', 'power-supply-prices'];
            const startIndex = selects.indexOf(startingFrom);
            for (let i = startIndex; i < selects.length; i++) {
                const select = document.getElementById(selects[i]);
                select.innerHTML = `<option value="">Select ${selects[i].replace('-select', '').replace('power-supply', 'Power Supply').toUpperCase()}</option>`;
                select.disabled = true;
                deselectComponent(components[i]);
                document.getElementById(priceDivs[i]).innerHTML = '';
                const componentKey = components[i].replace('power-supply', 'powerSupply');
                selectedBuild[componentKey] = null;
            }
        }

        // Function to display the selected build
        function showBuild() {
            const buildDetails = document.getElementById('build-details');
            const buildSummary = document.getElementById('build-summary');
            const saveBuildBtn = document.getElementById('save-build-btn');
            let totalPrice = 0;
            let buildHtml = '';

            // Helper function to format component details
            const formatComponent = (componentName, component) => {
                if (!component) return `<p><span class="font-bold">${componentName}:</span> Not selected</p>`;
                const cheapestPrice = component.prices && component.prices.length > 0
                    ? Math.min(...component.prices.map(p => p.price))
                    : 0;
                totalPrice += cheapestPrice;
                return `<p><span class="font-bold">${componentName}:</span> ${component.name} (${cheapestPrice} LKR)</p>`;
            };

            // Check if all components are selected
            const allSelected = Object.values(selectedBuild).every(component => component !== null);
            if (!allSelected) {
                buildHtml = '<p class="text-red-500">Please select all components before showing the build.</p>';
                buildDetails.innerHTML = buildHtml;
                buildSummary.classList.remove('hidden');
                saveBuildBtn.disabled = true; // Disable the Save Build button
                return;
            }

            // Build the HTML for the summary
            buildHtml += formatComponent('CPU', selectedBuild.cpu);
            buildHtml += formatComponent('Motherboard', selectedBuild.motherboard);
            buildHtml += formatComponent('GPU', selectedBuild.gpu);
            buildHtml += formatComponent('RAM', selectedBuild.ram);
            buildHtml += formatComponent('Storage', selectedBuild.storage);
            buildHtml += formatComponent('Power Supply', selectedBuild.powerSupply);

            // Add total price
            buildHtml += `<p class="mt-2 border-t border-gray-600 pt-2"><span class="font-bold">Total Price:</span> ${totalPrice} LKR</p>`;

            // Display the summary
            buildDetails.innerHTML = buildHtml;
            buildSummary.classList.remove('hidden');
            saveBuildBtn.disabled = false; // Enable the Save Build button

            // Populate the hidden form fields
            document.getElementById('cpu_id').value = selectedBuild.cpu.id;
            document.getElementById('motherboard_id').value = selectedBuild.motherboard.id;
            document.getElementById('gpu_id').value = selectedBuild.gpu.id;
            document.getElementById('ram_id').value = selectedBuild.ram.id;
            document.getElementById('storage_id').value = selectedBuild.storage.id;
            document.getElementById('power_supply_id').value = selectedBuild.powerSupply.id;
            document.getElementById('total_price').value = totalPrice;

            // Prompt for build name
            const buildName = prompt('Enter a name for your build (optional):');
            document.getElementById('build_name').value = buildName || '';
        }

        // CPU Selection
        document.getElementById('cpu-select').addEventListener('change', function() {
            const cpuId = this.value;
            console.log('Selected CPU ID:', cpuId);
            resetSelections('motherboard-select');
            if (cpuId) {
                selectComponent('cpu');
                const selectedOption = this.options[this.selectedIndex];
                const prices = JSON.parse(selectedOption.getAttribute('data-prices') || '[]');
                selectedBuild.cpu = {
                    id: cpuId,
                    name: selectedOption.textContent,
                    prices: prices
                };
                displayPrices('cpu', prices);

                fetch(`/api/compatible-motherboards/${cpuId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                    console.log('Motherboards:', data);
                    const motherboardSelect = document.getElementById('motherboard-select');
                    motherboardSelect.innerHTML = '<option value="">Select Motherboard</option>';
                    if (data && Array.isArray(data) && data.length > 0) {
                        data.forEach(motherboard => {
                            const option = document.createElement('option');
                            option.value = motherboard.id;
                            option.textContent = motherboard.name;
                            option.setAttribute('data-prices', JSON.stringify(motherboard.prices || []));
                            motherboardSelect.appendChild(option);
                        });
                        motherboardSelect.disabled = false;
                    } else {
                        motherboardSelect.innerHTML = '<option value="">No compatible motherboards found</option>';
                        motherboardSelect.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error fetching motherboards:', error);
                    const motherboardSelect = document.getElementById('motherboard-select');
                    motherboardSelect.innerHTML = `<option value="">Error: ${error.message}</option>`;
                    motherboardSelect.disabled = true;
                });
            } else {
                deselectComponent('cpu');
                selectedBuild.cpu = null;
                document.getElementById('cpu-prices').innerHTML = '';
            }
        });

        // Motherboard Selection
        document.getElementById('motherboard-select').addEventListener('change', function() {
            const motherboardId = this.value;
            const cpuId = document.getElementById('cpu-select').value;
            console.log('Motherboard selected:', motherboardId, 'CPU ID:', cpuId);
            resetSelections('gpu-select');

            if (!cpuId || !motherboardId) {
                console.error('Missing CPU ID or Motherboard ID:', { cpuId, motherboardId });
                const gpuSelect = document.getElementById('gpu-select');
                gpuSelect.innerHTML = '<option value="">Select CPU and Motherboard first</option>';
                return;
            }

            selectComponent('motherboard');
            const selectedOption = this.options[this.selectedIndex];
            const prices = JSON.parse(selectedOption.getAttribute('data-prices') || '[]');
            selectedBuild.motherboard = {
                id: motherboardId,
                name: selectedOption.textContent,
                prices: prices
            };
            displayPrices('motherboard', prices);

            // Fetch GPUs
            const gpuUrl = `/api/compatible-gpus/${cpuId}/${motherboardId}`;
            console.log('Fetching GPUs from:', gpuUrl);
            fetch(gpuUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                console.log('GPU data received:', data);
                const gpuSelect = document.getElementById('gpu-select');
                gpuSelect.innerHTML = '<option value="">Select GPU</option>';
                if (data && Array.isArray(data) && data.length > 0) {
                    data.forEach(gpu => {
                        const option = document.createElement('option');
                        option.value = gpu.id;
                        option.textContent = gpu.name;
                        option.setAttribute('data-prices', JSON.stringify(gpu.prices || []));
                        gpuSelect.appendChild(option);
                    });
                    gpuSelect.disabled = false;
                } else {
                    gpuSelect.innerHTML = '<option value="">No compatible GPUs found</option>';
                    gpuSelect.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error fetching GPUs:', error);
                const gpuSelect = document.getElementById('gpu-select');
                gpuSelect.innerHTML = `<option value="">Error: ${error.message}</option>`;
                gpuSelect.disabled = true;
            });

            // Fetch RAM
            const ramUrl = `/api/compatible-rams/${motherboardId}`;
            console.log('Fetching RAM from:', ramUrl);
            fetch(ramUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'include'
            })
            .then(response => {
                console.log('RAM response status:', response.status);
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`HTTP error! Status: ${response.status} - ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('RAM data received:', data);
                const ramSelect = document.getElementById('ram-select');
                if (!ramSelect) {
                    console.error('RAM select element not found!');
                    return;
                }
                ramSelect.innerHTML = '<option value="">Select RAM</option>';
                if (data && Array.isArray(data) && data.length > 0) {
                    data.forEach(ram => {
                        const option = document.createElement('option');
                        option.value = ram.id;
                        option.textContent = ram.name;
                        option.setAttribute('data-prices', JSON.stringify(ram.prices || []));
                        ramSelect.appendChild(option);
                    });
                    ramSelect.disabled = false;
                    console.log('RAM dropdown populated:', ramSelect.innerHTML);
                } else {
                    ramSelect.innerHTML = '<option value="">No compatible RAM found</option>';
                    ramSelect.disabled = true;
                    console.log('No compatible RAM found');
                }
            })
            .catch(error => {
                console.error('Error fetching RAM:', error);
                const ramSelect = document.getElementById('ram-select');
                if (ramSelect) {
                    ramSelect.innerHTML = `<option value="">Error: ${error.message}</option>`;
                    ramSelect.disabled = true;
                } else {
                    console.error('RAM select element not found during error handling!');
                }
            });
        });

        // GPU Selection
        document.getElementById('gpu-select').addEventListener('change', function() {
            const gpuId = this.value;
            const motherboardId = document.getElementById('motherboard-select').value;
            console.log('Selected GPU ID:', gpuId, 'Motherboard ID:', motherboardId);
            resetSelections('ram-select');
            if (gpuId && motherboardId) {
                selectComponent('gpu');
                const selectedOption = this.options[this.selectedIndex];
                const prices = JSON.parse(selectedOption.getAttribute('data-prices') || '[]');
                selectedBuild.gpu = {
                    id: gpuId,
                    name: selectedOption.textContent,
                    prices: prices
                };
                displayPrices('gpu', prices);

                fetch(`/api/compatible-rams/${motherboardId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                    console.log('RAMs:', data);
                    const ramSelect = document.getElementById('ram-select');
                    ramSelect.innerHTML = '<option value="">Select RAM</option>';
                    if (data && Array.isArray(data) && data.length > 0) {
                        data.forEach(ram => {
                            const option = document.createElement('option');
                            option.value = ram.id;
                            option.textContent = ram.name;
                            option.setAttribute('data-prices', JSON.stringify(ram.prices || []));
                            ramSelect.appendChild(option);
                        });
                        ramSelect.disabled = false;
                    } else {
                        ramSelect.innerHTML = '<option value="">No compatible RAMs found</option>';
                        ramSelect.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error fetching RAMs:', error);
                    const ramSelect = document.getElementById('ram-select');
                    ramSelect.innerHTML = `<option value="">Error: ${error.message}</option>`;
                    ramSelect.disabled = true;
                });
            } else {
                deselectComponent('gpu');
                selectedBuild.gpu = null;
                document.getElementById('gpu-prices').innerHTML = '';
            }
        });

        // RAM Selection
        document.getElementById('ram-select').addEventListener('change', function() {
            const ramId = this.value;
            const motherboardId = document.getElementById('motherboard-select').value;
            console.log('Selected RAM ID:', ramId, 'Motherboard ID:', motherboardId);
            resetSelections('storage-select');
            if (ramId) {
                selectComponent('ram');
                const selectedOption = this.options[this.selectedIndex];
                const prices = JSON.parse(selectedOption.getAttribute('data-prices') || '[]');
                selectedBuild.ram = {
                    id: ramId,
                    name: selectedOption.textContent,
                    prices: prices
                };
                displayPrices('ram', prices);

                fetch(`/api/compatible-storages/${ramId}?motherboard_id=${motherboardId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                    console.log('Storages:', data);
                    const storageSelect = document.getElementById('storage-select');
                    storageSelect.innerHTML = '<option value="">Select Storage</option>';
                    if (data && Array.isArray(data) && data.length > 0) {
                        data.forEach(storage => {
                            const option = document.createElement('option');
                            option.value = storage.id;
                            option.textContent = storage.name;
                            option.setAttribute('data-prices', JSON.stringify(storage.prices || []));
                            storageSelect.appendChild(option);
                        });
                        storageSelect.disabled = false;
                    } else {
                        storageSelect.innerHTML = '<option value="">No compatible storages found</option>';
                        storageSelect.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error fetching storages:', error);
                    const storageSelect = document.getElementById('storage-select');
                    storageSelect.innerHTML = `<option value="">Error: ${error.message}</option>`;
                    storageSelect.disabled = true;
                });
            } else {
                deselectComponent('ram');
                selectedBuild.ram = null;
                document.getElementById('ram-prices').innerHTML = '';
            }
        });

        // Storage Selection
        document.getElementById('storage-select').addEventListener('change', function() {
            const storageId = this.value;
            const gpuId = document.getElementById('gpu-select').value;
            const cpuId = document.getElementById('cpu-select').value;
            const motherboardId = document.getElementById('motherboard-select').value;
            console.log('Selected Storage ID:', storageId, 'GPU ID:', gpuId, 'CPU ID:', cpuId, 'Motherboard ID:', motherboardId);
            resetSelections('power-supply-select');
            if (storageId) {
                selectComponent('storage');
                const selectedOption = this.options[this.selectedIndex];
                const prices = JSON.parse(selectedOption.getAttribute('data-prices') || '[]');
                selectedBuild.storage = {
                    id: storageId,
                    name: selectedOption.textContent,
                    prices: prices
                };
                displayPrices('storage', prices);

                fetch(`/api/compatible-power-supplies/${storageId}?gpu_id=${gpuId}&cpu_id=${cpuId}&motherboard_id=${motherboardId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                    console.log('Power Supplies:', data);
                    const psuSelect = document.getElementById('power-supply-select');
                    psuSelect.innerHTML = '<option value="">Select Power Supply</option>';
                    if (data && Array.isArray(data) && data.length > 0) {
                        data.forEach(psu => {
                            const option = document.createElement('option');
                            option.value = psu.id;
                            option.textContent = psu.name;
                            option.setAttribute('data-prices', JSON.stringify(psu.prices || []));
                            psuSelect.appendChild(option);
                        });
                        psuSelect.disabled = false;
                    } else {
                        psuSelect.innerHTML = '<option value="">No compatible power supplies found</option>';
                        psuSelect.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error fetching power supplies:', error);
                    const psuSelect = document.getElementById('power-supply-select');
                    psuSelect.innerHTML = `<option value="">Error: ${error.message}</option>`;
                    psuSelect.disabled = true;
                });
            } else {
                deselectComponent('storage');
                selectedBuild.storage = null;
                document.getElementById('storage-prices').innerHTML = '';
            }
        });

        // Power Supply Selection
        document.getElementById('power-supply-select').addEventListener('change', function() {
            const powerSupplyId = this.value;
            console.log('Selected Power Supply ID:', powerSupplyId);
            if (powerSupplyId) {
                selectComponent('power-supply');
                const selectedOption = this.options[this.selectedIndex];
                const prices = JSON.parse(selectedOption.getAttribute('data-prices') || '[]');
                selectedBuild.powerSupply = {
                    id: powerSupplyId,
                    name: selectedOption.textContent,
                    prices: prices
                };
                displayPrices('power-supply', prices);
            } else {
                deselectComponent('power-supply');
                selectedBuild.powerSupply = null;
                document.getElementById('power-supply-prices').innerHTML = '';
            }
        });

        // Show Build Button Click Handler
        document.getElementById('show-build-btn').addEventListener('click', function() {
            showBuild();
        });
    </script>
</body>
</html>