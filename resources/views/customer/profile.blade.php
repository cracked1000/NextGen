<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - NextGen Computing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap');

        :root {
            --primary-color: #e53e3e;
            --primary-hover: #c53030;
            --bg-dark: #0d1117;
            --bg-card: #1f252d;
            --bg-sidebar: #161b22;
            --text-light: #e2e8f0;
            --text-gray: #a0aec0;
            --border-dark: #2d3748;
            --accent-glow: rgba(229, 62, 62, 0.3);
        }

        * {
            box-sizing: border-box;
            transition: all 0.2s ease;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, var(--bg-dark) 0%, #1a202c 100%);
            color: var(--text-light);
            line-height: 1.6;
            letter-spacing: 0.01em;
        }

        .container {
            max-width: 1440px;
            margin: 0 auto;
        }
        
        /* Sidebar Styling */
        .sidebar {
            background: var(--bg-sidebar);
            border-right: 2px solid var(--primary-color);
            box-shadow: 0 0 15px var(--accent-glow);
            border-radius: 0.75rem;
            height: 100%;
        }

        .sidebar h3 {
            font-family: 'Orbitron', sans-serif;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 1.25rem;
            font-weight: 700;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(229, 62, 62, 0.2);
            margin-bottom: 1.5rem;
        }

        .sidebar a {
            transition: all 0.3s ease;
            border-radius: 0.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .sidebar a:hover {
            background: var(--primary-color);
            color: #ffffff;
            box-shadow: 0 0 10px var(--accent-glow);
            transform: translateX(5px);
        }

        .sidebar i {
            width: 24px;
            text-align: center;
        }

        /* Card Styling */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-dark);
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 2rem;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4), 0 0 20px var(--accent-glow);
        }

        .card h3 {
            font-family: 'Orbitron', sans-serif;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(229, 62, 62, 0.2);
        }

        /* Typography */
        .text-primary {
            color: var(--primary-color) !important;
        }

        .main-heading {
            font-family: 'Orbitron', sans-serif;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
            text-shadow: 0 0 15px var(--accent-glow);
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--primary-color);
        }

        .font-medium {
            font-weight: 500;
        }

        /* Profile Section */
        .avatar {
            background: var(--border-dark);
            color: var(--text-gray);
            font-weight: 700;
            border: 3px solid var(--primary-color);
            box-shadow: 0 0 15px var(--accent-glow);
            object-fit: cover;
        }

        /* Buttons */
        .btn {
            font-weight: 500;
            letter-spacing: 0.5px;
            padding: 0.5rem 1.25rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 0.875rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            box-shadow: 0 0 15px var(--accent-glow);
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: #dc2626;
            border-color: #dc2626;
        }

        .btn-danger:hover {
            background-color: #b91c1c;
            border-color: #b91c1c;
            box-shadow: 0 0 15px rgba(220, 38, 38, 0.5);
            transform: translateY(-2px);
        }

        /* Build/Quotation Items */
        .build-item {
            background: rgba(45, 55, 72, 0.6);
            border: 1px solid var(--border-dark);
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .build-item:hover {
            background: rgba(45, 55, 72, 0.9);
            border-color: rgba(229, 62, 62, 0.3);
        }

        .build-header {
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            padding: 1rem;
        }

        .build-header .arrow {
            transition: transform 0.3s ease;
            margin-right: 0.75rem;
            color: var(--primary-color);
        }

        .build-details {
            padding: 0 1rem 1rem 2.5rem;
            border-top: 1px solid rgba(229, 62, 62, 0.1);
        }

        .build-details p {
            margin-bottom: 0.5rem;
        }

        .build-actions {
            padding: 1rem;
            display: flex;
            gap: 0.75rem;
            border-top: 1px solid rgba(229, 62, 62, 0.1);
        }

        /* Flash Messages */
        .alert {
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            padding: 1rem;
            border-left: 4px solid;
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.2);
            border-color: #10b981;
            color: #d1fae5;
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.2);
            border-color: #ef4444;
            color: #fee2e2;
        }

        /* Labels and Info */
        .info-label {
            font-weight: 500;
            color: var(--text-gray);
            display: block;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.05em;
        }

        .info-value {
            color: var(--text-light);
            margin-bottom: 1rem;
        }

        /* Responsive Spacing */
        @media (min-width: 992px) {
            .lg-ml-4 {
                margin-left: 1rem;
            }
        }
    </style>
</head>
<body>
    @include('include.header')

    <div class="container mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <div class="lg:w-1/4">
                <div class="sidebar p-6">
                    <h3>Account Hub</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('customer.edit_profile') }}" class="flex items-center text-gray-300 hover:text-white py-3 px-4 rounded-lg">
                                <i class="fas fa-user mr-3 text-primary"></i> Edit Profile
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('customer.orders') }}" class="flex items-center text-gray-300 hover:text-white py-3 px-4 rounded-lg">
                                <i class="fas fa-box mr-3 text-primary"></i> Orders
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('build.index') }}" class="flex items-center text-gray-300 hover:text-white py-3 px-4 rounded-lg">
                                <i class="fas fa-desktop mr-3 text-primary"></i> Build a PC
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <h2 class="main-heading">My Dashboard</h2>

                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Profile Picture Section -->
                <div class="card p-6">
                    <h3>Profile Picture</h3>
                    <div class="flex items-center">
                        @if ($customer->profile_photo)
                            <img src="{{ asset('storage/' . $customer->profile_photo) }}" alt="Profile Picture" class="w-16 h-16 rounded-full mr-4 avatar">
                        @else
                            <div class="w-16 h-16 rounded-full flex items-center justify-center mr-4 avatar">
                                <span class="text-sm text-center">{{ strtoupper(substr($customer->first_name, 0, 1)) . strtoupper(substr($customer->last_name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <a href="{{ route('customer.edit_profile') }}" class="text-primary hover:underline font-medium">Upload Picture</a>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="card p-6">
                    <h3>Account Information</h3>
                    <span class="info-label">Contact Information</span>
                    <p class="info-value">{{ $customer->first_name }} {{ $customer->last_name }}</p>
                    <p class="info-value">{{ $customer->email }}</p>
                    <div class="mt-4 flex space-x-4">
                        <a href="{{ route('customer.edit_profile') }}" class="text-primary hover:underline font-medium">Edit</a>
                        <a href="#" class="text-primary hover:underline font-medium">Change Password</a>
                    </div>
                </div>

                <!-- Address Book -->
                <div class="card p-6">
                    <h3>Address Book</h3>
                    <span class="info-label">Default Shipping Address</span>
                    @if ($customer->address)
                        <p class="info-value">{{ $customer->address }}</p>
                        <p class="info-value">Zipcode: {{ $customer->zipcode }}</p>
                    @else
                        <p class="text-gray-500">You have not set a default shipping address.</p>
                    @endif
                </div>

                <!-- Builds Section -->
                <div class="card p-6">
                    <h3>My Builds</h3>
                    @if ($builds->isEmpty())
                        <p class="text-white mb-2">You have not saved any builds yet.</p>
                        <a href="{{ route('build.index') }}" class="text-primary hover:underline font-medium">Start building a PC now!</a>
                    @else
                        @foreach ($builds as $build)
                            <div class="build-item mb-6 text-white">
                                <!-- Build Name (Clickable to Expand) -->
                                <div class="build-header" onclick="toggleBuildDetails('build-{{ $build->id }}')">
                                    <span class="arrow" id="arrow-{{ $build->id }}">▶</span>
                                    <div>
                                        <span>{{ $build->name ?? 'Build #' . $build->id }}</span>
                                        <span class="text-gray-400 ml-2">Total Price: {{ number_format($build->total_price, 2) }} LKR</span>
                                    </div>
                                </div>
                                
                                <!-- Build Details (Initially Hidden) -->
                                <div id="build-{{ $build->id }}" class="build-details hidden">
                                    <p><span class="font-medium text-gray-400">CPU:</span> {{ $build->cpu ? $build->cpu->name : 'Not found' }}</p>
                                    <p><span class="font-medium text-gray-400">Motherboard:</span> {{ $build->motherboard ? $build->motherboard->name : 'Not found' }}</p>
                                    <p><span class="font-medium text-gray-400">GPU:</span> {{ $build->gpu ? $build->gpu->name : 'Not found' }}</p>
                                    <p><span class="font-medium text-gray-400">RAM:</span>
                                        @if ($build->rams->isEmpty())
                                            None selected
                                        @else
                                            @foreach ($build->rams as $ram)
                                                {{ $ram->name ?? 'Not found' }}@if (!$loop->last), @endif
                                            @endforeach
                                        @endif
                                    </p>
                                    <p><span class="font-medium text-gray-400">Storage:</span>
                                        @if ($build->storages->isEmpty())
                                            None selected
                                        @else
                                            @foreach ($build->storages as $storage)
                                                {{ $storage->name ?? 'Not found' }}@if (!$loop->last), @endif
                                            @endforeach
                                        @endif
                                    </p>
                                    <p><span class="font-medium text-gray-400">Power Supply:</span> {{ $build->powerSupply ? $build->powerSupply->name : 'Not found' }}</p>
                                </div>
                                
                                <!-- Build Actions -->
                                <div class="build-actions">
                                    <a href="{{ route('build.purchase', $build->id) }}" class="btn btn-primary">Purchase Build</a>
                                    <form action="{{ route('customer.build.delete', $build->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this build?')">Delete Build</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                        <div class="mt-6">
                            {{ $builds->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>

                
                <div class="card p-6">
                    <h3>My Quotations</h3>
                    @if ($quotations->isEmpty())
                        <p class="text-gray-500 mb-2">You have not generated any quotations yet.</p>
                        <a href="{{ route('quotation.index') }}" class="text-primary hover:underline font-medium">Generate a quotation now!</a>
                    @else
                        @foreach ($quotations as $quotation)
                            <div class="build-item mb-6 text-white">
                                <div class="build-header" onclick="toggleQuotationDetails('quotation-{{ $quotation->id }}')">
                                    <span class="arrow" id="arrow-quotation-{{ $quotation->id }}">▶</span>
                                    <div>
                                        <span>Quotation #{{ $quotation->quotation_number }}</span>
                                        <span class="text-gray-400 ml-2">Source: {{ $quotation->source }}</span>
                                    </div>
                                </div>
                                
                                <div id="quotation-{{ $quotation->id }}" class="build-details hidden">
                                    <p><span class="font-medium text-gray-400">Total Price:</span> {{ number_format($quotation->build_details['total_price'] ?? 0, 2) }} LKR</p>
                                    <p><span class="font-medium text-gray-400">Status:</span> {{ $quotation->status }}</p>
                                    <p><span class="font-medium text-gray-400">Special Notes:</span> {{ $quotation->special_notes ?? 'No notes provided' }}</p>
                                    <p><span class="font-medium text-gray-400">Created At:</span> {{ $quotation->created_at->format('Y-m-d H:i:s') }}</p>
                                    <p><span class="font-medium text-gray-400">CPU:</span> {{ $quotation->build_details['components']['cpu']['name'] ?? 'Not found' }}</p>
                                    <p><span class="font-medium text-gray-400">Motherboard:</span> {{ $quotation->build_details['components']['motherboard']['name'] ?? 'Not found' }}</p>
                                    <p><span class="font-medium text-gray-400">GPU:</span> {{ $quotation->build_details['components']['gpu']['name'] ?? 'Not found' }}</p>
                                    <p><span class="font-medium text-gray-400">RAM:</span> {{ $quotation->build_details['components']['ram']['name'] ?? 'Not found' }}</p>
                                    <p><span class="font-medium text-gray-400">Storage:</span> {{ $quotation->build_details['components']['storage']['name'] ?? 'Not found' }}</p>
                                    <p><span class="font-medium text-gray-400">Power Supply:</span> {{ $quotation->build_details['components']['power_supply']['name'] ?? 'Not found' }}</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                
            </div>
        </div>
    </div>

    @include('include.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleBuildDetails(buildId) {
            const details = document.getElementById(buildId);
            const arrow = document.getElementById(`arrow-${buildId.split('-')[1]}`);
            
            if (details.classList.contains('hidden')) {
                details.classList.remove('hidden');
                arrow.style.transform = 'rotate(90deg)';
            } else {
                details.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
            }
        }

        function toggleQuotationDetails(quotationId) {
            const details = document.getElementById(quotationId);
            const arrow = document.getElementById(`arrow-${quotationId.replace('quotation-', '')}`);
            
            if (details.classList.contains('hidden')) {
                details.classList.remove('hidden');
                arrow.style.transform = 'rotate(90deg)';
            } else {
                details.classList.add('hidden');
                arrow.style.transform = 'rotate(0deg)';
            }
        }
        function toggleBuildDetails(buildId) {
        const details = document.getElementById(buildId);
        const arrow = document.getElementById(`arrow-${buildId.split('-')[1]}`);
        
        if (details.classList.contains('hidden')) {
            details.classList.remove('hidden');
            arrow.style.transform = 'rotate(90deg)';
        } else {
            details.classList.add('hidden');
            arrow.style.transform = 'rotate(0deg)';
        }
    }

    function toggleQuotationDetails(quotationId) {
        const details = document.getElementById(quotationId);
        const arrow = document.getElementById(`arrow-${quotationId.replace('quotation-', '')}`);
        
        if (details.classList.contains('hidden')) {
            details.classList.remove('hidden');
            arrow.style.transform = 'rotate(90deg)';
        } else {
            details.classList.add('hidden');
            arrow.style.transform = 'rotate(0deg)';
        }
    }

    function toggleOrderDetails(orderId) {
        const details = document.getElementById(orderId);
        const arrow = document.getElementById(`arrow-${orderId.replace('order-', '')}`);
        
        if (details.classList.contains('hidden')) {
            details.classList.remove('hidden');
            arrow.style.transform = 'rotate(90deg)';
        } else {
            details.classList.add('hidden');
            arrow.style.transform = 'rotate(0deg)';
        }
    }
    </script>
</body>
</html>