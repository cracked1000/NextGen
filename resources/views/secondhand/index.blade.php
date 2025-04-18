<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Second-Hand Market</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-900 text-white">
    @include('include.header')
    <div class="container mx-auto py-16">
        <h2 class="text-4xl text-center mb-16 font-bold text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-pink-500">
            Second-Hand Market
        </h2>

        @if ($parts->isEmpty())
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 text-center border border-gray-700/50">
                <p class="text-gray-300">No parts available at the moment.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($parts as $part)
                    <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50">
                        @if ($part->image1)
                            <img src="{{ asset('storage/' . $part->image1) }}" alt="Part Image" class="w-full h-48 object-cover rounded-lg mb-4">
                        @endif
                        <h3 class="text-xl font-semibold mb-2">{{ $part->part_name }}</h3>
                        <p class="text-gray-300 mb-2">Price: LKR {{ number_format($part->price, 2) }}</p>
                        <p class="text-gray-300 mb-2">Status: {{ $part->status }}</p>
                        <p class="text-gray-300 mb-2">Condition: {{ $part->condition ?? 'N/A' }}</p>
                        <p class="text-gray-300 mb-4">Category: {{ $part->category ?? 'N/A' }}</p>

                        

                        <div class="flex space-x-4">
                            <a href="{{ route('secondhand.show', $part->id) }}" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                                View Details
                            </a>
                            @if (Auth::check() && Auth::user()->role === 'customer')
                                <a href="{{ route('secondhand.buy', $part->id) }}" class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition">
                                    Buy Now
                                </a>
                            @endif
                            @if (Auth::check() && Auth::user()->role === 'seller')
                                <a href="{{ route('seller.sell_form') }}" class="bg-yellow-600 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 transition">
                                    Sell
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @include('include.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>