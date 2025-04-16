<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - NextGen Computing</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Include Axios for AJAX requests -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="bg-indigo-900 text-white w-64 min-h-screen hidden md:block">
            <div class="p-4 border-b border-indigo-800">
                <h2 class="text-xl font-bold">NextGen Computing</h2>
                <p class="text-xs text-indigo-200">Admin Dashboard</p>
            </div>
            <nav class="mt-6">
                <a href="#dashboard" class="flex items-center py-3 px-4 bg-indigo-800 text-white">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#quotations" class="flex items-center py-3 px-4 hover:bg-indigo-800 transition duration-200">
                    <i class="fas fa-file-invoice-dollar mr-3"></i>
                    <span>Quotations</span>
                </a>
                <a href="#users" class="flex items-center py-3 px-4 hover:bg-indigo-800 transition duration-200">
                    <i class="fas fa-users mr-3"></i>
                    <span>Users</span>
                </a>
                <a href="#settings" class="flex items-center py-3 px-4 hover:bg-indigo-800 transition duration-200">
                    <i class="fas fa-cog mr-3"></i>
                    <span>Settings</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Mobile Header -->
            <div class="md:hidden bg-indigo-900 text-white p-4 flex items-center justify-between">
                <h2 class="text-xl font-bold">NextGen Computing</h2>
                <button id="menuToggle" class="text-white focus:outline-none">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <!-- Mobile Menu (hidden by default) -->
            <div id="mobileMenu" class="md:hidden bg-indigo-800 text-white hidden">
                <nav>
                    <a href="#dashboard" class="block py-3 px-4 hover:bg-indigo-700">Dashboard</a>
                    <a href="#quotations" class="block py-3 px-4 hover:bg-indigo-700">Quotations</a>
                    <a href="#users" class="block py-3 px-4 hover:bg-indigo-700">Users</a>
                    <a href="#settings" class="block py-3 px-4 hover:bg-indigo-700">Settings</a>
                </nav>
            </div>

            <div class="container mx-auto px-4 py-8">
                <!-- Header -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <h1 class="text-2xl font-bold" id="dashboard">Admin Dashboard</h1>
                    <div class="mt-3 md:mt-0">
                        <span class="text-sm text-gray-500 mr-2">Welcome, Admin</span>
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Online</span>
                    </div>
                </div>

                <!-- Summary Statistics -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6 flex items-center">
                        <div class="rounded-full bg-green-100 p-3 mr-4">
                            <i class="fas fa-store text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Total Sellers</h3>
                            <p class="text-2xl font-bold" id="totalSellers">{{ $totalSellers }}</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 flex items-center">
                        <div class="rounded-full bg-purple-100 p-3 mr-4">
                            <i class="fas fa-users text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Total Customers</h3>
                            <p class="text-2xl font-bold" id="totalCustomers">{{ $totalCustomers }}</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 flex items-center">
                        <div class="rounded-full bg-yellow-100 p-3 mr-4">
                            <i class="fas fa-money-bill-wave text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Total Sales (LKR)</h3>
                            <p class="text-2xl font-bold" id="totalSales">{{ number_format($totalSales, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Date Filter Form -->
                <div class="bg-white rounded-lg shadow p-6 mb-8">
                    <h2 class="text-lg font-bold mb-4">Filter by Date</h2>
                    <form id="dateFilterForm" action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="flex-1">
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ $startDate ?? '' }}" class="w-full border-gray-300 rounded-md shadow-sm px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="flex-1">
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" name="end_date" id="end_date" value="{{ $endDate ?? '' }}" class="w-full border-gray-300 rounded-md shadow-sm px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md transition duration-200 flex items-center">
                                <i class="fas fa-filter mr-2"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Quotations Section -->
                <div class="bg-white rounded-lg shadow p-6 mb-8" id="quotations">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <h2 class="text-xl font-bold">All Quotations</h2>
                        <div class="mt-3 sm:mt-0 flex items-center space-x-3">
                            <!-- Search Form -->
                            <form action="{{ route('admin.dashboard') }}" method="GET" class="relative">
                                <input type="text" name="search" id="quotationSearch" placeholder="Search quotations..." value="{{ request('search') }}" class="w-full sm:w-64 border-gray-300 rounded-md shadow-sm px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fas fa-search text-gray-400"></i>
                                </span>
                                @if($startDate && $endDate)
                                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                                @endif
                            </form>
                            <form action="{{ route('admin.exportQuotationActions') }}" method="GET">
                                @if($startDate && $endDate)
                                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                                @endif
                                <button id="exportBtn" type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition duration-200 flex items-center">
                                    <i class="fas fa-file-export mr-2"></i> Export to CSV
                                </button>
                            </form>
                        </div>
                    </div>

                    @if (session('success'))
                        <div id="successAlert" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                            {{ session('success') }}
                        </div>
                    @else
                        <div id="successAlert" class="hidden bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                            Operation completed successfully!
                        </div>
                    @endif

                    @if ($quotationActions->isEmpty())
                        <div id="noQuotationsMessage" class="text-center text-gray-600 py-8">
                            No quotations have been generated yet.
                        </div>
                        <div id="quotationsTableContainer" class="hidden overflow-x-auto">
                    @else
                        <div id="noQuotationsMessage" class="hidden text-center text-gray-600 py-8">
                            No quotations have been generated yet.
                        </div>
                        <div id="quotationsTableContainer" class="overflow-x-auto">
                    @endif
                        <table class="min-w-full border">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="py-3 px-4 border text-left">Quotation Number</th>
                                    <th class="py-3 px-4 border text-left">Source</th>
                                    <th class="py-3 px-4 border text-left">User Email</th>
                                    <th class="py-3 px-4 border text-left">Status</th>
                                    <th class="py-3 px-4 border text-left">Special Notes</th>
                                    <th class="py-3 px-4 border text-left">Created At</th>
                                    <th class="py-3 px-4 border text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="quotationsTableBody">
                                @foreach ($quotationActions as $quotation)
                                    <tr>
                                        <td class="py-3 px-4 border">{{ $quotation->quotation_number }}</td>
                                        <td class="py-3 px-4 border">{{ $quotation->source }}</td>
                                        <td class="py-3 px-4 border">{{ $quotation->user ? $quotation->user->email : 'Guest' }}</td>
                                        <td class="py-3 px-4 border">
                                            <form action="{{ route('admin.updateQuotationStatus', $quotation->id) }}" method="POST" class="status-form">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="start_date" value="{{ $startDate ?? '' }}">
                                                <input type="hidden" name="end_date" value="{{ $endDate ?? '' }}">
                                                <input type="hidden" name="search" value="{{ request('search') }}">
                                                <select name="status" class="w-full border-gray-300 rounded-md shadow-sm" onchange="this.form.submit()">
                                                    <option value="Build Pending" {{ $quotation->status === 'Build Pending' ? 'selected' : '' }}>Build Pending</option>
                                                    <option value="Build in Progress" {{ $quotation->status === 'Build in Progress' ? 'selected' : '' }}>Build in Progress</option>
                                                    <option value="Completed" {{ $quotation->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                                </select>
                                                <textarea name="special_notes" class="mt-2 w-full border-gray-300 rounded-md shadow-sm" placeholder="Add special notes...">{{ $quotation->special_notes ?? '' }}</textarea>
                                                <button type="submit" class="save-btn mt-2 bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded transition duration-200">Save</button>
                                            </form>
                                        </td>
                                        <td class="py-3 px-4 border">{{ $quotation->special_notes ?? 'No notes' }}</td>
                                        <td class="py-3 px-4 border">{{ $quotation->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td class="py-3 px-4 border">
                                            <button class="view-btn bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded mr-1 transition duration-200" data-id="{{ $quotation->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <form action="{{ route('admin.deleteQuotation', $quotation->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="start_date" value="{{ $startDate ?? '' }}">
                                                <input type="hidden" name="end_date" value="{{ $endDate ?? '' }}">
                                                <input type="hidden" name="search" value="{{ request('search') }}">
                                                <button type="submit" class="delete-btn bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded transition duration-200" onclick="return confirm('Are you sure you want to delete this quotation?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination Links -->
                        <div class="mt-6">
                            {{ $quotationActions->links('vendor.pagination.tailwind') }}
                        </div>
                    </div>
                </div>

                <!-- Users Section -->
                <div class="bg-white rounded-lg shadow p-6 mb-8" id="users">
                    <h2 class="text-xl font-bold mb-4">All Users</h2>
                    
                    @if ($allUsers->isEmpty())
                        <div id="noUsersMessage" class="text-center text-gray-600 py-8">
                            No users found.
                        </div>
                        <div id="usersTableContainer" class="hidden overflow-x-auto">
                    @else
                        <div id="noUsersMessage" class="hidden text-center text-gray-600 py-8">
                            No users found.
                        </div>
                        <div id="usersTableContainer" class="overflow-x-auto">
                    @endif
                        <table class="min-w-full border">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="py-3 px-4 border text-left">Name</th>
                                    <th class="py-3 px-4 border text-left">Email</th>
                                    <th class="py-3 px-4 border text-left">Role</th>
                                    <th class="py-3 px-4 border text-left">Created At</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                @foreach ($allUsers as $user)
                                    <tr>
                                        <td class="py-3 px-4 border">{{ $user->first_name }} {{ $user->last_name }}</td>
                                        <td class="py-3 px-4 border">{{ $user->email }}</td>
                                        <td class="py-3 px-4 border">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $user->role === 'Administrator' ? 'bg-purple-100 text-purple-800' : ($user->role === 'seller' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                                {{ $user->role }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 border">{{ $user->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination Links for Users -->
                        <div class="mt-6">
                            {{ $allUsers->links('vendor.pagination.tailwind') }}
                        </div>
                    </div>
                </div>

                <!-- Quotation Details Modal -->
                <div id="quotationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold">Quotation Details</h3>
                            <button id="closeModalBtn" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="space-y-2">
                            <p><strong>Quotation Number:</strong> <span id="modalQuotationNumber"></span></p>
                            <p><strong>Source:</strong> <span id="modalSource"></span></p>
                            <p><strong>User Email:</strong> <span id="modalUserEmail"></span></p>
                            <p><strong>Total Price (LKR):</strong> <span id="modalTotalPrice"></span></p>
                            <p><strong>Components:</strong></p>
                            <ul class="list-disc pl-5">
                                <li>CPU: <span id="modalCpu"></span></li>
                                <li>Motherboard: <span id="modalMotherboard"></span></li>
                                <li>GPU: <span id="modalGpu"></span></li>
                                <li>RAM: <span id="modalRam"></span></li>
                                <li>Storage: <span id="modalStorage"></span></li>
                                <li>Power Supply: <span id="modalPowerSupply"></span></li>
                            </ul>
                            <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                            <p><strong>Special Notes:</strong> <span id="modalSpecialNotes"></span></p>
                            <p><strong>Created At:</strong> <span id="modalCreatedAt"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Toggle mobile menu
        document.getElementById('menuToggle').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
        });

        // Show success alert if present
        @if (session('success'))
            document.getElementById('successAlert').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('successAlert').classList.add('hidden');
            }, 3000);
        @endif

        // Add event listener to save buttons
        document.querySelectorAll('.save-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('form').submit();
            });
        });

        // Debounce function to limit the rate of form submissions
        function debounce(func, wait) {
            let timeout;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        // Auto-submit the search form on input change (debounced)
        const searchForm = document.querySelector('form[action="' + '{{ route('admin.dashboard') }}' + '"]');
        const searchInput = document.getElementById('quotationSearch');

        if (searchForm && searchInput) {
            searchInput.addEventListener('input', debounce(function() {
                searchForm.submit();
            }, 500));
        }

        // Modal functionality for viewing quotation details
        const modal = document.getElementById('quotationModal');
        const closeModalBtn = document.getElementById('closeModalBtn');

        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const quotationId = this.getAttribute('data-id');

                // Make AJAX request to fetch quotation details
                axios.get(`/admin/quotations/${quotationId}`)
                    .then(response => {
                        const quotation = response.data;

                        // Populate modal with quotation details
                        document.getElementById('modalQuotationNumber').textContent = quotation.quotation_number;
                        document.getElementById('modalSource').textContent = quotation.source;
                        document.getElementById('modalUserEmail').textContent = quotation.user_email;
                        document.getElementById('modalTotalPrice').textContent = quotation.total_price;
                        document.getElementById('modalCpu').textContent = quotation.components.cpu;
                        document.getElementById('modalMotherboard').textContent = quotation.components.motherboard;
                        document.getElementById('modalGpu').textContent = quotation.components.gpu;
                        document.getElementById('modalRam').textContent = quotation.components.ram;
                        document.getElementById('modalStorage').textContent = quotation.components.storage;
                        document.getElementById('modalPowerSupply').textContent = quotation.components.power_supply;
                        document.getElementById('modalStatus').textContent = quotation.status;
                        document.getElementById('modalSpecialNotes').textContent = quotation.special_notes;
                        document.getElementById('modalCreatedAt').textContent = quotation.created_at;

                        // Show the modal
                        modal.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error fetching quotation details:', error);
                        alert('Failed to load quotation details. Please try again.');
                    });
            });
        });

        // Close the modal when the close button is clicked
        closeModalBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        // Close the modal when clicking outside of it
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });

        // Close the modal with the Esc key
        window.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
            }
        });
    </script>
</body>
</html>