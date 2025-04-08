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
            <h2 class="text-xl font-semibold text-gray-800">Welcome, {{ $seller->first_name }} {{ $seller->last_name }}!</h2>
            <p class="text-gray-600 mt-1">Email: {{ $seller->email }}</p>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-8" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

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
                            <!-- Part Name -->
                            <h4 class="text-lg font-bold text-gray-800 mb-2">{{ $part->part_name }}</h4>

                            <!-- Part Details -->
                            <div class="space-y-1 text-gray-600">
                                <p><span class="font-medium">Price:</span> LKR {{ number_format($part->price, 2) }}</p>
                                <p><span class="font-medium">Status:</span> {{ $part->status }}</p>
                                <p><span class="font-medium">Condition:</span> {{ $part->condition ?? 'N/A' }}</p>
                                <p><span class="font-medium">Category:</span> {{ $part->category ?? 'N/A' }}</p>
                                <!-- <p><span class="font-medium">Description:</span> {{ $part->description ?? 'N/A' }}</p> -->
                            </div>

                            <!-- Images -->
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

                            <!-- Action Buttons -->
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