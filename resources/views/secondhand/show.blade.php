<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $part->part_name }} - Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-900 text-black">
    @include('include.header')

    <div class="min-h-screen bg-gray-900 p-6">
        <div class="text-center text-white mb-6">
            <h2 class="text-4xl font-bold">{{ $part->part_name }} - Details</h2>
        </div>

        <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                @if($part->image1)
                    <img src="{{ asset('storage/' . $part->image1) }}" alt="{{ $part->part_name }} Image 1" class="w-full h-48 object-cover rounded-lg">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center rounded-lg">
                        <span class="text-gray-500">No Image</span>
                    </div>
                @endif

                @if($part->image2)
                    <img src="{{ asset('storage/' . $part->image2) }}" alt="{{ $part->part_name }} Image 2" class="w-full h-48 object-cover rounded-lg">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center rounded-lg">
                        <span class="text-gray-500">No Image</span>
                    </div>
                @endif

                @if($part->image3)
                    <img src="{{ asset('storage/' . $part->image3) }}" alt="{{ $part->part_name }} Image 3" class="w-full h-48 object-cover rounded-lg">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center rounded-lg">
                        <span class="text-gray-500">No Image</span>
                    </div>
                @endif
            </div>

            <div class="text-gray-800">
                <h3 class="text-2xl font-semibold mb-2">{{ $part->part_name }}</h3>
                <p class="text-gray-600 mb-4">{{ $part->description ?? 'No description available.' }}</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <p><strong>Price:</strong> <span class="text-red-600 font-bold">{{ number_format($part->price, 2) }} LKR</span></p>
                        <p><strong>Status:</strong> {{ $part->status }}</p>
                        <p><strong>Condition:</strong> {{ $part->condition }}</p>
                    </div>
                    <div>
                        <p><strong>Seller:</strong> {{ $part->seller ? $part->seller->first_name . ' ' . $part->seller->last_name : 'Not specified' }}</p>
                        <p><strong>Category:</strong> {{ $part->category ?? 'Not specified' }}</p>
                        <p><strong>Listing Date:</strong> {{ $part->listing_date ? $part->listing_date->format('Y-m-d') : 'Not specified' }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    <!-- Back Button -->
                    <a href="{{ route('secondhand.index') }}" class="inline-block bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg">
                        Back to Market
                    </a>

                    @if($part->status === 'Available')
                        <a href="{{ route('secondhand.buy', $part->id) }}" class="inline-block bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-lg">
                            Buy Now
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('include.footer')

</body>
</html>         