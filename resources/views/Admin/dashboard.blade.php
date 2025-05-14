<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - NextGen Computing</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        .sidebar {
            background: #1a202c;
            min-height: 100vh;
        }

        .sidebar a {
            color: #a0aec0;
            transition: color 0.3s, background 0.3s;
        }

        .sidebar a:hover {
            color: #ffffff;
            background: #2d3748;
        }

        .card {
            background: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background: #edf2f7;
            font-weight: 600;
            text-transform: uppercase;
        }

        .table tbody tr:hover {
            background: #f7fafc;
        }

        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
        }

        .status-pending {
            background: #fefcbf;
            color: #b7791f;
        }

        .status-available {
            background: #c6f6d5;
            color: #2f855a;
        }

        .status-sold {
            background: #bee3f8;
            color: #2b6cb0;
        }

        .status-completed {
            background: #c6f6d5;
            color: #2f855a;
        }

        .status-build-pending {
            background: #fefcbf;
            color: #b7791f;
        }

        .status-build-in-progress {
            background: #bee3f8;
            color: #2b6cb0;
        }

        .status-unverified {
            background: #fefcbf;
            color: #b7791f;
        }

        .status-verified {
            background: #c6f6d5;
            color: #2f855a;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: #ffffff;
            padding: 2rem;
            border-radius: 0.5rem;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-close {
            float: right;
            cursor: pointer;
            font-size: 1.5rem;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">
    @include('include.header')

    <div class="flex">
        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Messages -->
            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded mb-6">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 text-red-700 p-4 rounded mb-6">{{ session('error') }}</div>
            @endif

            <!-- Overview -->
            <section id="overview" class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Overview</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="card p-4">
                        <h3 class="text-lg font-medium">Total Second-Hand Parts</h3>
                        <p class="text-2xl">{{ $totalParts }}</p>
                    </div>
                    <div class="card p-4">
                        <h3 class="text-lg font-medium">Total Sellers</h3>
                        <p class="text-2xl">{{ $totalSellers }}</p>
                    </div>
                    <div class="card p-4">
                        <h3 class="text-lg font-medium">Total Customers</h3>
                        <p class="text-2xl">{{ $totalCustomers }}</p>
                    </div>
                    <div class="card p-4">
                        <h3 class="text-lg font-medium">Total Sales (LKR)</h3>
                        <p class="text-2xl">{{ number_format($totalSales, 2) }}</p>
                    </div>
                    <div class="card p-4">
                        <h3 class="text-lg font-medium">Total CPUs</h3>
                        <p class="text-2xl">{{ $totalCpus }}</p>
                    </div>
                    <div class="card p-4">
                        <h3 class="text-lg font-medium">Total Motherboards</h3>
                        <p class="text-2xl">{{ $totalMotherboards }}</p>
                    </div>
                    <div class="card p-4">
                        <h3 class="text-lg font-medium">Total GPUs</h3>
                        <p class="text-2xl">{{ $totalGpus }}</p>
                    </div>
                    <div class="card p-4">
                        <h3 class="text-lg font-medium">Total RAMs</h3>
                        <p class="text-2xl">{{ $totalRams }}</p>
                    </div>
                    <div class="card p-4">
                        <h3 class="text-lg font-medium">Total Storages</h3>
                        <p class="text-2xl">{{ $totalStorages }}</p>
                    </div>
                    <div class="card p-4">
                        <h3 class="text-lg font-medium">Total Power Supplies</h3>
                        <p class="text-2xl">{{ $totalPowerSupplies }}</p>
                    </div>
                </div>
            </section>

            <!-- Filters -->
            <section class="mb-8">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-col md:flex-row gap-4">
                    <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="border p-2 rounded">
                    <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="border p-2 rounded">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search..."
                        class="border p-2 rounded">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Filter</button>
                </form>
            </section>

            <!-- Users -->
            <section id="users" class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Users</h2>
                <div class="card">
                    <div class="p-4">
                        <!-- Users Table -->
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4">Name</th>
                                    <th class="py-2 px-4">Email</th>
                                    <th class="py-2 px-4">Role</th>
                                    <th class="py-2 px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($allUsers as $user)
                                    <tr>
                                        <td class="py-2 px-4">{{ $user->first_name }} {{ $user->last_name }}</td>
                                        <td class="py-2 px-4">{{ $user->email }}</td>
                                        <td class="py-2 px-4">{{ $user->role }}</td>
                                        <td class="py-2 px-4">
                                            @if ($user->role === 'seller')
                                                <form action="{{ route('admin.edit_seller', $user->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <input type="text" name="first_name"
                                                        value="{{ $user->first_name }}" class="border p-1 rounded"
                                                        required>
                                                    <input type="text" name="last_name"
                                                        value="{{ $user->last_name }}" class="border p-1 rounded"
                                                        required>
                                                    <input type="email" name="email" value="{{ $user->email }}"
                                                        class="border p-1 rounded" required>
                                                    <button type="submit"
                                                        class="bg-blue-600 text-white px-2 py-1 rounded">Edit</button>
                                                </form>
                                                <form action="{{ route('admin.delete_seller', $user->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="bg-red-600 text-white px-2 py-1 rounded"
                                                        onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            @elseif ($user->role === 'customer')
                                                <form action="{{ route('admin.edit_customer', $user->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <input type="text" name="first_name"
                                                        value="{{ $user->first_name }}" class="border p-1 rounded"
                                                        required>
                                                    <input type="text" name="last_name"
                                                        value="{{ $user->last_name }}" class="border p-1 rounded"
                                                        required>
                                                    <input type="email" name="email" value="{{ $user->email }}"
                                                        class="border p-1 rounded" required>
                                                    <button type="submit"
                                                        class="bg-blue-600 text-white px-2 py-1 rounded">Edit</button>
                                                </form>
                                                <form action="{{ route('admin.delete_customer', $user->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="bg-red-600 text-white px-2 py-1 rounded"
                                                        onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-2 px-4 text-center text-gray-600">No users found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $allUsers->links() }}</div>
                    </div>
                </div>
            </section>

            <!-- Second-Hand Parts -->
            <section id="parts" class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Second-Hand Parts</h2>
                <div class="card">
                    <div class="p-4">
                        <!-- Parts Table -->
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4">Part Name</th>
                                    <th class="py-2 px-4">Seller</th>
                                    <th class="py-2 px-4">Price (LKR)</th>
                                    <th class="py-2 px-4">Condition</th>
                                    <th class="py-2 px-4">Status</th>
                                    <th class="py-2 px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($parts as $part)
                                    <tr>
                                        <td class="py-2 px-4">{{ $part->part_name }}</td>
                                        <td class="py-2 px-4">
                                            {{ $part->seller ? $part->seller->first_name . ' ' . $part->seller->last_name : 'N/A' }}
                                        </td>
                                        <td class="py-2 px-4">{{ number_format($part->price, 2) }}</td>
                                        <td class="py-2 px-4">{{ $part->condition }}</td>
                                        <td class="py-2 px-4">
                                            <span
                                                class="status-badge {{ $part->status == 'Available' ? 'status-available' : ($part->status == 'Sold' ? 'status-sold' : 'status-pending') }}">
                                                {{ $part->status }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4">
                                            <form action="{{ route('admin.edit_part', $part->id) }}" method="POST"
                                                enctype="multipart/form-data" class="inline">
                                                @csrf
                                                <input type="text" name="part_name"
                                                    value="{{ $part->part_name }}" class="border p-1 rounded"
                                                    required>
                                                <input type="number" name="price" value="{{ $part->price }}"
                                                    step="0.01" class="border p-1 rounded" required>
                                                <select name="condition" class="border p-1 rounded">
                                                    <option value="New"
                                                        {{ $part->condition == 'New' ? 'selected' : '' }}>New</option>
                                                    <option value="Used"
                                                        {{ $part->condition == 'Used' ? 'selected' : '' }}>Used
                                                    </option>
                                                </select>
                                                <input type="file" name="image1" accept="image/*"
                                                    class="border p-1 rounded">
                                                <button type="submit"
                                                    class="bg-blue-600 text-white px-2 py-1 rounded">Edit</button>
                                            </form>
                                            <form action="{{ route('admin.delete_part', $part->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                            @if ($part->status == 'pending')
                                                <form action="{{ route('admin.approve_part', $part->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="bg-green-600 text-white px-2 py-1 rounded">Approve</button>
                                                </form>
                                                <form action="{{ route('admin.decline_part', $part->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="bg-yellow-600 text-white px-2 py-1 rounded">Decline</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-2 px-4 text-center text-gray-600">No parts found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $parts->links() }}</div>
                    </div>
                </div>
            </section>

            <!-- Orders -->
            <section id="orders" class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Orders</h2>
                <div class="card">
                    <div class="p-4">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4">Order ID</th>
                                    <th class="py-2 px-4">Customer</th>
                                    <th class="py-2 px-4">Seller</th>
                                    <th class="py-2 px-4">Part</th>
                                    <th class="py-2 px-4">Total (LKR)</th>
                                    <th class="py-2 px-4">Status</th>
                                    <th class="py-2 px-4">Verification</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr>
                                        <td class="py-2 px-4">{{ $order->id }}</td>
                                        <td class="py-2 px-4">
                                            {{ $order->customer ? $order->customer->first_name . ' ' . $order->customer->last_name : 'N/A' }}
                                        </td>
                                        <td class="py-2 px-4">
                                            {{ $order->part && $order->part->seller ? $order->part->seller->first_name . ' ' . $order->part->seller->last_name : 'N/A' }}
                                        </td>
                                        <td class="py-2 px-4">{{ $order->part ? $order->part->part_name : 'N/A' }}
                                        </td>
                                        <td class="py-2 px-4">{{ number_format($order->total, 2) }}</td>
                                        <td class="py-2 px-4">
                                            <span
                                                class="status-badge {{ $order->status == 'Completed' ? 'status-completed' : 'status-pending' }}">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4">
                                            <span
                                                class="status-badge {{ $order->verify_product ? ($order->is_verified ? 'status-verified' : 'status-unverified') : 'status-verified' }}">
                                                {{ $order->verify_product ? ($order->is_verified ? 'Verified' : 'Unverified') : 'N/A' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-2 px-4 text-center text-gray-600">No orders
                                            found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $orders->links() }}</div>
                    </div>
                </div>
            </section>

            <!-- Verification Requests -->
            <section id="verification-requests" class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Verification Requests</h2>
                <div class="card">
                    <div class="p-4">
                        @if ($verificationRequests->isEmpty())
                            <p class="text-gray-600">No pending verification requests.</p>
                        @else
                            <table class="table w-full">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4">Order ID</th>
                                        <th class="py-2 px-4">Customer</th>
                                        <th class="py-2 px-4">Seller</th>
                                        <th class="py-2 px-4">Part</th>
                                        <th class="py-2 px-4">Total (LKR)</th>
                                        <th class="py-2 px-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($verificationRequests as $order)
                                        <tr>
                                            <td class="py-2 px-4">{{ $order->id }}</td>
                                            <td class="py-2 px-4">
                                                {{ $order->customer ? $order->customer->first_name . ' ' . $order->customer->last_name : 'N/A' }}
                                            </td>
                                            <td class="py-2 px-4">
                                                {{ $order->part && $order->part->seller ? $order->part->seller->first_name . ' ' . $order->part->seller->last_name : 'N/A' }}
                                            </td>
                                            <td class="py-2 px-4">{{ $order->part ? $order->part->part_name : 'N/A' }}
                                            </td>
                                            <td class="py-2 px-4">{{ number_format($order->total, 2) }}</td>
                                            <td class="py-2 px-4">
                                                <form action="{{ route('admin.orders.verify', $order->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="is_verified" value="1">
                                                    <button type="submit"
                                                        class="bg-green-600 text-white px-2 py-1 rounded">Verify</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </section>

            <!-- CPUs -->
            <section id="cpus" class="mb-8">
                <h2 class="text-xl font-semibold mb-4">CPUs</h2>
                <div class="card">
                    <div class="p-4">
                        <!-- Add CPU Form -->
                        <form action="{{ route('cpus.store') }}" method="POST" class="mb-4">
                            @csrf
                            <div class="flex flex-col md:flex-row gap-2">
                                <input type="text" name="name" placeholder="Name" class="border p-1 rounded"
                                    required>
                                <input type="text" name="socket_type" placeholder="Socket Type"
                                    class="border p-1 rounded" required>
                                <input type="number" name="power_requirement" placeholder="Power Requirement (W)"
                                    class="border p-1 rounded" required>
                                <input type="number" name="price" placeholder="Price (LKR)" step="0.01"
                                    class="border p-1 rounded" required>
                                <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded">Add
                                    CPU</button>
                            </div>
                        </form>

                        <!-- CPUs Table -->
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4">Name</th>
                                    <th class="py-2 px-4">Socket Type</th>
                                    <th class="py-2 px-4">Power Requirement (W)</th>
                                    <th class="py-2 px-4">Price (LKR)</th>
                                    <th class="py-2 px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cpus as $cpu)
                                    <tr>
                                        <td class="py-2 px-4">{{ $cpu->name }}</td>
                                        <td class="py-2 px-4">{{ $cpu->socket_type }}</td>
                                        <td class="py-2 px-4">{{ $cpu->power_requirement }}</td>
                                        <td class="py-2 px-4">{{ number_format($cpu->price, 2) }}</td>
                                        <td class="py-2 px-4">
                                            <form action="{{ route('cpus.update', $cpu->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="text" name="name" value="{{ $cpu->name }}"
                                                    class="border p-1 rounded" required>
                                                <input type="text" name="socket_type"
                                                    value="{{ $cpu->socket_type }}" class="border p-1 rounded"
                                                    required>
                                                <input type="number" name="power_requirement"
                                                    value="{{ $cpu->power_requirement }}" class="border p-1 rounded"
                                                    required>
                                                <input type="number" name="price" value="{{ $cpu->price }}"
                                                    step="0.01" class="border p-1 rounded" required>
                                                <button type="submit"
                                                    class="bg-blue-600 text-white px-2 py-1 rounded">Edit</button>
                                            </form>
                                            <form action="{{ route('cpus.destroy', $cpu->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-2 px-4 text-center text-gray-600">No CPUs found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $cpus->links() }}</div>
                    </div>
                </div>
            </section>

            <!-- Motherboards -->
            <section id="motherboards" class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Motherboards</h2>
                <div class="card">
                    <div class="p-4">
                        <!-- Add Motherboard Form -->
                        <form action="{{ route('motherboards.store') }}" method="POST" class="mb-4">
                            @csrf
                            <div class="flex flex-col md:flex-row gap-2 flex-wrap">
                                <input type="text" name="name" placeholder="Name" class="border p-1 rounded"
                                    required>
                                <input type="text" name="socket_type" placeholder="Socket Type"
                                    class="border p-1 rounded" required>
                                <select name="ram_type" class="border p-1 rounded" required>
                                    <option value="DDR4">DDR4</option>
                                    <option value="DDR5">DDR5</option>
                                </select>
                                <input type="number" name="ram_speed" placeholder="RAM Speed (MHz)"
                                    class="border p-1 rounded" required>
                                <select name="form_factor" class="border p-1 rounded" required>
                                    <option value="ATX">ATX</option>
                                    <option value="Micro ATX">Micro ATX</option>
                                    <option value="Mini ITX">Mini ITX</option>
                                </select>
                                <input type="number" name="ram_slots" placeholder="RAM Slots"
                                    class="border p-1 rounded" required>
                                <input type="number" name="sata_slots" placeholder="SATA Slots"
                                    class="border p-1 rounded" required>
                                <input type="number" name="m2_slots" placeholder="M.2 Slots"
                                    class="border p-1 rounded" required>
                                <select name="m2_nvme_support" class="border p-1 rounded" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                <input type="number" name="pcie_version" placeholder="PCIe Version" step="0.1"
                                    class="border p-1 rounded" required>
                                <input type="number" name="price" placeholder="Price (LKR)" step="0.01"
                                    class="border p-1 rounded" required>
                                <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded">Add
                                    Motherboard</button>
                            </div>
                        </form>

                        <!-- Motherboards Table -->
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4">Name</th>
                                    <th class="py-2 px-4">Socket Type</th>
                                    <th class="py-2 px-4">RAM Type</th>
                                    <th class="py-2 px-4">RAM Speed (MHz)</th>
                                    <th class="py-2 px-4">Form Factor</th>
                                    <th class="py-2 px-4">RAM Slots</th>
                                    <th class="py-2 px-4">SATA Slots</th>
                                    <th class="py-2 px-4">M.2 Slots</th>
                                    <th class="py-2 px-4">M.2 NVMe Support</th>
                                    <th class="py-2 px-4">PCIe Version</th>
                                    <th class="py-2 px-4">Price (LKR)</th>
                                    <th class="py-2 px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($motherboards as $motherboard)
                                    <tr>
                                        <td class="py-2 px-4">{{ $motherboard->name }}</td>
                                        <td class="py-2 px-4">{{ $motherboard->socket_type }}</td>
                                        <td class="py-2 px-4">{{ $motherboard->ram_type }}</td>
                                        <td class="py-2 px-4">{{ $motherboard->ram_speed }}</td>
                                        <td class="py-2 px-4">{{ $motherboard->form_factor }}</td>
                                        <td class="py-2 px-4">{{ $motherboard->ram_slots }}</td>
                                        <td class="py-2 px-4">{{ $motherboard->sata_slots }}</td>
                                        <td class="py-2 px-4">{{ $motherboard->m2_slots }}</td>
                                        <td class="py-2 px-4">{{ $motherboard->m2_nvme_support ? 'Yes' : 'No' }}</td>
                                        <td class="py-2 px-4">{{ $motherboard->pcie_version }}</td>
                                        <td class="py-2 px-4">{{ number_format($motherboard->price, 2) }}</td>
                                        <td class="py-2 px-4">
                                            <form action="{{ route('motherboards.update', $motherboard->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="text" name="name"
                                                    value="{{ $motherboard->name }}" class="border p-1 rounded"
                                                    required>
                                                <input type="text" name="socket_type"
                                                    value="{{ $motherboard->socket_type }}"
                                                    class="border p-1 rounded" required>
                                                <select name="ram_type" class="border p-1 rounded" required>
                                                    <option value="DDR4"
                                                        {{ $motherboard->ram_type == 'DDR4' ? 'selected' : '' }}>DDR4
                                                    </option>
                                                    <option value="DDR5"
                                                        {{ $motherboard->ram_type == 'DDR5' ? 'selected' : '' }}>DDR5
                                                    </option>
                                                </select>
                                                <input type="number" name="ram_speed"
                                                    value="{{ $motherboard->ram_speed }}" class="border p-1 rounded"
                                                    required>
                                                <select name="form_factor" class="border p-1 rounded" required>
                                                    <option value="ATX"
                                                        {{ $motherboard->form_factor == 'ATX' ? 'selected' : '' }}>ATX
                                                    </option>
                                                    <option value="Micro ATX"
                                                        {{ $motherboard->form_factor == 'Micro ATX' ? 'selected' : '' }}>
                                                        Micro ATX</option>
                                                    <option value="Mini ITX"
                                                        {{ $motherboard->form_factor == 'Mini ITX' ? 'selected' : '' }}>
                                                        Mini ITX</option>
                                                </select>
                                                <input type="number" name="ram_slots"
                                                    value="{{ $motherboard->ram_slots }}" class="border p-1 rounded"
                                                    required>
                                                <input type="number" name="sata_slots"
                                                    value="{{ $motherboard->sata_slots }}" class="border p-1 rounded"
                                                    required>
                                                <input type="number" name="m2_slots"
                                                    value="{{ $motherboard->m2_slots }}" class="border p-1 rounded"
                                                    required>
                                                <select name="m2_nvme_support" class="border p-1 rounded" required>
                                                    <option value="1"
                                                        {{ $motherboard->m2_nvme_support ? 'selected' : '' }}>Yes
                                                    </option>
                                                    <option value="0"
                                                        {{ !$motherboard->m2_nvme_support ? 'selected' : '' }}>No
                                                    </option>
                                                </select>
                                                <input type="number" name="pcie_version"
                                                    value="{{ $motherboard->pcie_version }}" step="0.1"
                                                    class="border p-1 rounded" required>
                                                <input type="number" name="price"
                                                    value="{{ $motherboard->price }}" step="0.01"
                                                    class="border p-1 rounded" required>
                                                <button type="submit"
                                                    class="bg-blue-600 text-white px-2 py-1 rounded">Edit</button>
                                            </form>
                                            <form action="{{ route('motherboards.destroy', $motherboard->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="py-2 px-4 text-center text-gray-600">No motherboards
                                            found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $motherboards->links() }}</div>
                    </div>
                </div>
            </section>

            <!-- GPUs -->
            <section id="gpus" class="mb-8">
                <h2 class="text-xl font-semibold mb-4">GPUs</h2>
                <div class="card">
                    <div class="p-4">
                        <!-- Add GPU Form -->
                        <form action="{{ route('gpus.store') }}" method="POST" class="mb-4">
                            @csrf
                            <div class="flex flex-col md:flex-row gap-2">
                                <input type="text" name="name" placeholder="Name" class="border p-1 rounded"
                                    required>
                                <input type="number" name="pcie_version" placeholder="PCIe Version" step="0.1"
                                    class="border p-1 rounded" required>
                                <input type="number" name="power_requirement" placeholder="Power Requirement (W)"
                                    class="border p-1 rounded" required>
                                <input type="number" name="length" placeholder="Length (mm)"
                                    class="border p-1 rounded" required>
                                <input type="number" name="height" placeholder="Height (mm)"
                                    class="border p-1 rounded" required>
                                <input type="number" name="price" placeholder="Price (LKR)" step="0.01"
                                    class="border p-1 rounded" required>
                                <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded">Add
                                    GPU</button>
                            </div>
                        </form>

                        <!-- GPUs Table -->
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4">Name</th>
                                    <th class="py-2 px-4">PCIe Version</th>
                                    <th class="py-2 px-4">Power Requirement (W)</th>
                                    <th class="py-2 px-4">Length (mm)</th>
                                    <th class="py-2 px-4">Height (mm)</th>
                                    <th class="py-2 px-4">Price (LKR)</th>
                                    <th class="py-2 px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($gpus as $gpu)
                                    <tr>
                                        <td class="py-2 px-4">{{ $gpu->name }}</td>
                                        <td class="py-2 px-4">{{ $gpu->pcie_version }}</td>
                                        <td class="py-2 px-4">{{ $gpu->power_requirement }}</td>
                                        <td class="py-2 px-4">{{ $gpu->length }}</td>
                                        <td class="py-2 px-4">{{ $gpu->height }}</td>
                                        <td class="py-2 px-4">{{ number_format($gpu->price, 2) }}</td>
                                        <td class="py-2 px-4">
                                            <form action="{{ route('gpus.update', $gpu->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="text" name="name" value="{{ $gpu->name }}"
                                                    class="border p-1 rounded" required>
                                                <input type="number" name="pcie_version"
                                                    value="{{ $gpu->pcie_version }}" step="0.1"
                                                    class="border p-1 rounded" required>
                                                <input type="number" name="power_requirement"
                                                    value="{{ $gpu->power_requirement }}" class="border p-1 rounded"
                                                    required>
                                                <input type="number" name="length" value="{{ $gpu->length }}"
                                                    class="border p-1 rounded" required>
                                                <input type="number" name="height" value="{{ $gpu->height }}"
                                                    class="border p-1 rounded" required>
                                                <input type="number" name="price" value="{{ $gpu->price }}"
                                                    step="0.01" class="border p-1 rounded" required>
                                                <button type="submit"
                                                    class="bg-blue-600 text-white px-2 py-1 rounded">Edit</button>
                                            </form>
                                            <form action="{{ route('gpus.destroy', $gpu->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-2 px-4 text-center text-gray-600">No GPUs found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $gpus->links() }}</div>
                    </div>
                </div>
            </section>

            <!-- RAMs -->
            <section id="rams" class="mb-8">
                <h2 class="text-xl font-semibold mb-4">RAMs</h2>
                <div class="card">
                    <div class="p-4">
                        <!-- Add RAM Form -->
                        <form action="{{ route('rams.store') }}" method="POST" class="mb-4">
                            @csrf
                            <div class="flex flex-col md:flex-row gap-2">
                                <input type="text" name="name" placeholder="Name" class="border p-1 rounded"
                                    required>
                                <select name="ram_type" class="border p-1 rounded" required>
                                    <option value="DDR4">DDR4</option>
                                    <option value="DDR5">DDR5</option>
                                </select>
                                <input type="number" name="ram_speed" placeholder="RAM Speed (MHz)"
                                    class="border p-1 rounded" required>
                                <input type="number" name="capacity" placeholder="Capacity (GB)"
                                    class="border p-1 rounded" required>
                                <input type="number" name="stick_count" placeholder="Stick Count"
                                    class="border p-1 rounded" required>
                                <input type="number" name="price" placeholder="Price (LKR)" step="0.01"
                                    class="border p-1 rounded" required>
                                <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded">Add
                                    RAM</button>
                            </div>
                        </form>

                        <!-- RAMs Table -->
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4">Name</th>
                                    <th class="py-2 px-4">RAM Type</th>
                                    <th class="py-2 px-4">RAM Speed (MHz)</th>
                                    <th class="py-2 px-4">Capacity (GB)</th>
                                    <th class="py-2 px-4">Stick Count</th>
                                    <th class="py-2 px-4">Price (LKR)</th>
                                    <th class="py-2 px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rams as $ram)
                                    <tr>
                                        <td class="py-2 px-4">{{ $ram->name }}</td>
                                        <td class="py-2 px-4">{{ $ram->ram_type }}</td>
                                        <td class="py-2 px-4">{{ $ram->ram_speed }}</td>
                                        <td class="py-2 px-4">{{ $ram->capacity }}</td>
                                        <td class="py-2 px-4">{{ $ram->stick_count }}</td>
                                        <td class="py-2 px-4">{{ number_format($ram->price, 2) }}</td>
                                        <td class="py-2 px-4">
                                            <form action="{{ route('rams.update', $ram->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="text" name="name" value="{{ $ram->name }}"
                                                    class="border p-1 rounded" required>
                                                <select name="ram_type" class="border p-1 rounded" required>
                                                    <option value="DDR4"
                                                        {{ $ram->ram_type == 'DDR4' ? 'selected' : '' }}>DDR4</option>
                                                    <option value="DDR5"
                                                        {{ $ram->ram_type == 'DDR5' ? 'selected' : '' }}>DDR5</option>
                                                </select>
                                                <input type="number" name="ram_speed"
                                                    value="{{ $ram->ram_speed }}" class="border p-1 rounded"
                                                    required>
                                                <input type="number" name="capacity" value="{{ $ram->capacity }}"
                                                    class="border p-1 rounded" required>
                                                <input type="number" name="stick_count"
                                                    value="{{ $ram->stick_count }}" class="border p-1 rounded"
                                                    required>
                                                <input type="number" name="price" value="{{ $ram->price }}"
                                                    step="0.01" class="border p-1 rounded" required>
                                                <button type="submit"
                                                    class="bg-blue-600 text-white px-2 py-1 rounded">Edit</button>
                                            </form>
                                            <form action="{{ route('rams.destroy', $ram->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-2 px-4 text-center text-gray-600">No RAMs found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $rams->links() }}</div>
                    </div>
                </div>
            </section>

            <!-- Storages -->
            <section id="storages" class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Storages</h2>
                <div class="card">
                    <div class="p-4">
                        <!-- Add Storage Form -->
                        <form action="{{ route('storages.store') }}" method="POST" class="mb-4">
                            @csrf
                            <div class="flex flex-col md:flex-row gap-2">
                                <input type="text" name="name" placeholder="Name" class="border p-1 rounded"
                                    required>
                                <select name="type" class="border p-1 rounded" required>
                                    <option value="M.2">M.2</option>
                                    <option value="SATA">SATA</option>
                                </select>
                                <select name="is_nvme" class="border p-1 rounded" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                <input type="number" name="capacity" placeholder="Capacity (GB)"
                                    class="border p-1 rounded" required>
                                <input type="number" name="price" placeholder="Price (LKR)" step="0.01"
                                    class="border p-1 rounded" required>
                                <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded">Add
                                    Storage</button>
                            </div>
                        </form>

                        <!-- Storages Table -->
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4">Name</th>
                                    <th class="py-2 px-4">Type</th>
                                    <th class="py-2 px-4">Is NVMe</th>
                                    <th class="py-2 px-4">Capacity (GB)</th>
                                    <th class="py-2 px-4">Price (LKR)</th>
                                    <th class="py-2 px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($storages as $storage)
                                    <tr>
                                        <td class="py-2 px-4">{{ $storage->name }}</td>
                                        <td class="py-2 px-4">{{ $storage->type }}</td>
                                        <td class="py-2 px-4">{{ $storage->is_nvme ? 'Yes' : 'No' }}</td>
                                        <td class="py-2 px-4">{{ $storage->capacity }}</td>
                                        <td class="py-2 px-4">{{ number_format($storage->price, 2) }}</td>
                                        <td class="py-2 px-4">
                                            <form action="{{ route('storages.update', $storage->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="text" name="name" value="{{ $storage->name }}"
                                                    class="border p-1 rounded" required>
                                                <select name="type" class="border p-1 rounded" required>
                                                    <option value="M.2"
                                                        {{ $storage->type == 'M.2' ? 'selected' : '' }}>M.2</option>
                                                    <option value="SATA"
                                                        {{ $storage->type == 'SATA' ? 'selected' : '' }}>SATA</option>
                                                </select>
                                                <select name="is_nvme" class="border p-1 rounded" required>
                                                    <option value="1" {{ $storage->is_nvme ? 'selected' : '' }}>
                                                        Yes</option>
                                                    <option value="0"
                                                        {{ !$storage->is_nvme ? 'selected' : '' }}>No</option>
                                                </select>
                                                <input type="number" name="capacity"
                                                    value="{{ $storage->capacity }}" class="border p-1 rounded"
                                                    required>
                                                <input type="number" name="price" value="{{ $storage->price }}"
                                                    step="0.01" class="border p-1 rounded" required>
                                                <button type="submit"
                                                    class="bg-blue-600 text-white px-2 py-1 rounded">Edit</button>
                                            </form>
                                            <form action="{{ route('storages.destroy', $storage->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-2 px-4 text-center text-gray-600">No storages
                                            found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $storages->links() }}</div>
                    </div>
                </div>
            </section>

            <!-- Power Supplies -->
            <section id="power-supplies" class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Power Supplies</h2>
                <div class="card">
                    <div class="p-4">
                        <!-- Add Power Supply Form -->
                        <form action="{{ route('power_supplies.store') }}" method="POST" class="mb-4">
                            @csrf
                            <div class="flex flex-col md:flex-row gap-2">
                                <input type="text" name="name" placeholder="Name" class="border p-1 rounded"
                                    required>
                                <input type="number" name="wattage" placeholder="Wattage (W)"
                                    class="border p-1 rounded" required>
                                <select name="form_factor" class="border p-1 rounded" required>
                                    <option value="ATX">ATX</option>
                                    <option value="SFX">SFX</option>
                                </select>
                                <input type="number" name="price" placeholder="Price (LKR)" step="0.01"
                                    class="border p-1 rounded" required>
                                <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded">Add Power
                                    Supply</button>
                            </div>
                        </form>

                        <!-- Power Supplies Table -->
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4">Name</th>
                                    <th class="py-2 px-4">Wattage (W)</th>
                                    <th class="py-2 px-4">Form Factor</th>
                                    <th class="py-2 px-4">Price (LKR)</th>
                                    <th class="py-2 px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($powerSupplies as $powerSupply)
                                    <tr>
                                        <td class="py-2 px-4">{{ $powerSupply->name }}</td>
                                        <td class="py-2 px-4">{{ $powerSupply->wattage }}</td>
                                        <td class="py-2 px-4">{{ $powerSupply->form_factor }}</td>
                                        <td class="py-2 px-4">{{ number_format($powerSupply->price, 2) }}</td>
                                        <td class="py-2 px-4">
                                            <form action="{{ route('power_supplies.update', $powerSupply->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="text" name="name"
                                                    value="{{ $powerSupply->name }}" class="border p-1 rounded"
                                                    required>
                                                <input type="number" name="wattage"
                                                    value="{{ $powerSupply->wattage }}" class="border p-1 rounded"
                                                    required>
                                                <select name="form_factor" class="border p-1 rounded" required>
                                                    <option value="ATX"
                                                        {{ $powerSupply->form_factor == 'ATX' ? 'selected' : '' }}>ATX
                                                    </option>
                                                    <option value="SFX"
                                                        {{ $powerSupply->form_factor == 'SFX' ? 'selected' : '' }}>SFX
                                                    </option>
                                                </select>
                                                <input type="number" name="price"
                                                    value="{{ $powerSupply->price }}" step="0.01"
                                                    class="border p-1 rounded" required>
                                                <button type="submit"
                                                    class="bg-blue-600 text-white px-2 py-1 rounded">Edit</button>
                                            </form>
                                            <form action="{{ route('power_supplies.destroy', $powerSupply->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-2 px-4 text-center text-gray-600">No power
                                            supplies found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $powerSupplies->links() }}</div>
                    </div>
                </div>
            </section>

            <!-- Quotations -->
            <section id="quotations" class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Quotation Actions</h2>
                <div class="card">
                    <div class="p-4">
                        <a href="{{ route('admin.export_quotation_actions') }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">Export Quotations</a>
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4">Quotation Number</th>
                                    <th class="py-2 px-4">Source</th>
                                    <th class="py-2 px-4">User Email</th>
                                    <th class="py-2 px-4">Status</th>
                                    <th class="py-2 px-4">Special Notes</th>
                                    <th class="py-2 px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($quotationActions as $quotation)
                                    <tr>
                                        <td class="py-2 px-4">{{ $quotation->quotation_number }}</td>
                                        <td class="py-2 px-4">{{ $quotation->source }}</td>
                                        <td class="py-2 px-4">
                                            {{ $quotation->user ? $quotation->user->email : 'Guest' }}</td>
                                        <td class="py-2 px-4">
                                            <span
                                                class="status-badge {{ $quotation->status == 'Build Pending' ? 'status-build-pending' : ($quotation->status == 'Build in Progress' ? 'status-build-in-progress' : 'status-completed') }}">
                                                {{ $quotation->status }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-4">{{ $quotation->special_notes ?? 'N/A' }}</td>
                                        <td class="py-2 px-4">
                                            <button onclick="fetchQuotationDetails({{ $quotation->id }})"
                                                class="bg-indigo-600 text-white px-2 py-1 rounded mr-1">View
                                                Details</button>
                                            <form
                                                action="{{ route('admin.update_quotation_status', $quotation->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="start_date"
                                                    value="{{ $startDate ?? '' }}">
                                                <input type="hidden" name="end_date" value="{{ $endDate ?? '' }}">
                                                <input type="hidden" name="search" value="{{ $search ?? '' }}">
                                                <select name="status" class="border p-1 rounded">
                                                    <option value="Build Pending"
                                                        {{ $quotation->status == 'Build Pending' ? 'selected' : '' }}>
                                                        Build Pending</option>
                                                    <option value="Build in Progress"
                                                        {{ $quotation->status == 'Build in Progress' ? 'selected' : '' }}>
                                                        Build in Progress</option>
                                                    <option value="Completed"
                                                        {{ $quotation->status == 'Completed' ? 'selected' : '' }}>
                                                        Completed</option>
                                                </select>
                                                <input type="text" name="special_notes"
                                                    value="{{ $quotation->special_notes ?? '' }}"
                                                    class="border p-1 rounded">
                                                <button type="submit"
                                                    class="bg-blue-600 text-white px-2 py-1 rounded">Update</button>
                                            </form>
                                            <form action="{{ route('admin.delete_quotation', $quotation->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-2 px-4 text-center text-gray-600">No quotations
                                            found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $quotationActions->links() }}</div>
                    </div>
                </div>
            </section>

            <!-- Technician Network -->
            <section id="technicians" class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Technician Network</h2>
                @if (session('success'))
                    <div class="bg-green-100 text-green-700 p-4 mb-4 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="card">
                    <div class="p-4">
                        <!-- Add Technician Form -->
                        <form action="{{ route('admin.technicians.store') }}" method="POST" class="mb-4">
                            @csrf
                            <div class="flex flex-col md:flex-row gap-2">
                                <input type="text" name="district" placeholder="District"
                                    class="border p-1 rounded" required>
                                <input type="text" name="town" placeholder="Town" class="border p-1 rounded"
                                    required>
                                <input type="text" name="name" placeholder="Name" class="border p-1 rounded"
                                    required>
                                <input type="text" name="contact_number" placeholder="Contact Number"
                                    class="border p-1 rounded" required>
                                <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded">Add
                                    Technician</button>
                            </div>
                        </form>

                        <!-- Technicians Table -->
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4">District</th>
                                    <th class="py-2 px-4">Town</th>
                                    <th class="py-2 px-4">Name</th>
                                    <th class="py-2 px-4">Contact Number</th>
                                    <th class="py-2 px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($technicians as $technician)
                                    <tr>
                                        <td class="py-2 px-4">{{ $technician->district }}</td>
                                        <td class="py-2 px-4">{{ $technician->town }}</td>
                                        <td class="py-2 px-4">{{ $technician->name }}</td>
                                        <td class="py-2 px-4">{{ $technician->contact_number }}</td>
                                        <td class="py-2 px-4">
                                            <form action="{{ route('admin.technicians.update', $technician->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="text" name="district"
                                                    value="{{ $technician->district }}" class="border p-1 rounded"
                                                    required>
                                                <input type="text" name="town"
                                                    value="{{ $technician->town }}" class="border p-1 rounded"
                                                    required>
                                                <input type="text" name="name"
                                                    value="{{ $technician->name }}" class="border p-1 rounded"
                                                    required>
                                                <input type="text" name="contact_number"
                                                    value="{{ $technician->contact_number }}"
                                                    class="border p-1 rounded" required>
                                                <button type="submit"
                                                    class="bg-blue-600 text-white px-2 py-1 rounded">Edit</button>
                                            </form>
                                            <form action="{{ route('admin.technicians.destroy', $technician->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-2 px-4 text-center text-gray-600">No technicians
                                            found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $technicians->links() }}</div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Quotation Details Modal -->
    <div id="quotationModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal()">×</span>
            <h2 class="text-xl font-semibold mb-4">Quotation Details</h2>
            <div id="quotationDetails">
                <p>Loading...</p>
            </div>
        </div>
    </div>

    <script>
        function fetchQuotationDetails(id) {
            axios.get(`/admin/quotations/${id}`)
                .then(response => {
                    const data = response.data;
                    const details = `
                        <p><strong>Quotation Number:</strong> ${data.quotation_number}</p>
                        <p><strong>Source:</strong> ${data.source}</p>
                        <p><strong>User Email:</strong> ${data.user_email}</p>
                        <p><strong>Total Price (LKR):</strong> ${data.total_price}</p>
                        <p><strong>Components:</strong></p>
                        <ul>
                            <li>CPU: ${data.components.cpu}</li>
                            <li>Motherboard: ${data.components.motherboard}</li>
                            <li>GPU: ${data.components.gpu}</li>
                            <li>RAM: ${data.components.ram}</li>
                            <li>Storage: ${data.components.storage}</li>
                            <li>Power Supply: ${data.components.power_supply}</li>
                        </ul>
                        <p><strong>Status:</strong> ${data.status}</p>
                        <p><strong>Special Notes:</strong> ${data.special_notes}</p>
                        <p><strong>Created At:</strong> ${data.created_at}</p>
                    `;
                    document.getElementById('quotationDetails').innerHTML = details;
                    document.getElementById('quotationModal').style.display = 'flex';
                })
                .catch(error => {
                    console.error('Error fetching quotation details:', error);
                    document.getElementById('quotationDetails').innerHTML =
                        '<p class="text-red-600">Failed to load details. Please try again.</p>';
                    document.getElementById('quotationModal').style.display = 'flex';
                });
        }

        function closeModal() {
            document.getElementById('quotationModal').style.display = 'none';
            document.getElementById('quotationDetails').innerHTML = '<p>Loading...</p>';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('quotationModal');
            if (event.target === modal) {
                closeModal();
            }
        };
    </script>
</body>

</html>
