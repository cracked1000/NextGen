<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="min-h-screen bg-gray-900">

    <!-- Header Section -->
    <header class="bg-gray-900 shadow">    
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-white">Seller Dashboard</h1>
            <div class="flex items-center space-x-4">
                <a href="{{ route('seller.sell_form') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                    Sell a New Part
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Section -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            @if (isset($seller))
                <h2 class="text-xl font-semibold text-gray-800">Welcome, {{ $seller->first_name }} {{ $seller->last_name }}!</h2>
                <p class="text-gray-600 mt-1">Email: {{ $seller->email }}</p>
            @else
                <p class="text-red-600">Error: Seller information not available.</p>
            @endif
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-8" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-8" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Orders Section -->
        <div class="mb-8">
            <h3 class="text-2xl font-bold text-white mb-6">Your Orders</h3>
            @if ($orders->isEmpty())
                <div class="bg-white shadow rounded-lg p-6 text-center">
                    <p class="text-gray-600">No orders for your parts yet.</p>
                </div>
            @else
                <div class="bg-white shadow rounded-lg p-6">
                    <!-- Display Errors -->
                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-4">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full border">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="py-3 px-4 border text-left">Order ID</th>
                                    <th class="py-3 px-4 border text-left">Customer</th>
                                    <th class="py-3 px-4 border text-left">Part</th>
                                    <th class="py-3 px-4 border text-left">Total</th>
                                    <th class="py-3 px-4 border text-left">Accepted</th>
                                    <th class="py-3 px-4 border text-left">Shipped</th>
                                    <th class="py-3 px-4 border text-left">Received</th>
                                    <th class="py-3 px-4 border text-left">Verified</th>
                                    <th class="py-3 px-4 border text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td class="py-3 px-4 border">#{{ $order->id }}</td>
                                        <td class="py-3 px-4 border">{{ $order->customer ? $order->customer->first_name . ' ' . $order->customer->last_name : 'N/A' }}</td>
                                        <td class="py-3 px-4 border">{{ $order->part ? $order->part->part_name : 'N/A' }}</td>
                                        <td class="py-3 px-4 border">{{ number_format($order->total, 2) }} LKR</td>
                                        <td class="py-3 px-4 border">
                                            <span class="inline-flex items-center px-2 py-1 text-sm font-medium rounded-full {{ $order->is_accepted ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                                {{ $order->is_accepted ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 border">
                                            <span class="inline-flex items-center px-2 py-1 text-sm font-medium rounded-full {{ $order->is_shipped ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                                {{ $order->is_shipped ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 border">
                                            <span class="inline-flex items-center px-2 py-1 text-sm font-medium rounded-full {{ $order->is_received ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                                {{ $order->is_received ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 border">
                                            <span class="inline-flex items-center px-2 py-1 text-sm font-medium rounded-full {{ $order->verify_product ? ($order->is_verified ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700') : 'bg-gray-100 text-gray-700' }}">
                                                {{ $order->verify_product ? ($order->is_verified ? 'Yes' : 'Pending') : 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 border">
                                            <form action="{{ route('seller.orders.update-status', $order->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="flex items-center space-x-2">
                                                    <label class="flex items-center">
                                                        <input type="checkbox" name="is_accepted" value="1" {{ $order->is_accepted ? 'checked' : '' }} onchange="this.form.querySelector('[name=is_shipped]').disabled = !this.checked" class="mr-1">
                                                        Accept
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="checkbox" name="is_shipped" value="1" {{ $order->is_shipped ? 'checked' : '' }} {{ $order->is_accepted ? '' : 'disabled' }} class="mr-1">
                                                        Shipped
                                                    </label>
                                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded transition duration-200">
                                                        Update
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Parts Section -->
        <div>
            <h3 class="text-2xl font-bold text-white mb-6">Your Parts</h3>
            @if ($parts->isEmpty())
                <div class="bg-white shadow rounded-lg p-6 text-center">
                    <p class="text-gray-600">No parts listed yet. Start by selling a new part!</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($parts as $part)
                        <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition duration-200">
                            <h4 class="text-lg font-bold text-gray-800 mb-2">{{ $part->part_name }}</h4>
                            <div class="space-y-1 text-gray-600">
                                <p><span class="font-medium">Price:</span> LKR {{ number_format($part->price, 2) }}</p>
                                <p>
                                    <span class="font-medium">Status:</span> 
                                    <span class="inline-flex items-center px-2 py-1 text-sm font-medium rounded-full {{ $part->status == 'Available' ? 'bg-green-100 text-green-700' : ($part->status == 'Sold' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ $part->status }}
                                    </span>
                                </p>
                                <p><span class="font-medium">Condition:</span> {{ $part->condition ?? 'N/A' }}</p>
                                <p><span class="font-medium">Category:</span> {{ $part->category ?? 'N/A' }}</p>
                            </div>
                            @if ($part->image1 || $part->image2 || $part->image3)
                                <div class="mt-4">
                                    <p class="text-gray-600 font-medium mb-2">Images:</p>
                                    <div class="flex space-x-3">
                                        @if ($part->image1)
                                            <img src="{{ asset('storage/' . $part->image1) }}" alt="Part Image 1" class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                        @endif
                                        @if ($part->image2)
                                            <img src="{{ asset('storage/' . $part->image2) }}" alt="Part Image 2" class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                        @endif
                                        @if ($part->image3)
                                            <img src="{{ asset('storage/' . $part->image3) }}" alt="Part Image 3" class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="mt-6 flex space-x-3">
                                <a href="{{ route('seller.edit_part', $part->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                                    Edit
                                </a>
                                <form action="{{ route('seller.delete_part', $part->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this part?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>
</div>
</body>
</html>