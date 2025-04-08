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
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Roboto:wght@400;500&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #0d1117 0%, #1a202c 100%);
            color: #e2e8f0;
        }
        .sidebar {
            background: #161b22;
            border-right: 2px solid #e53e3e;
            box-shadow: 0 0 15px rgba(229, 62, 62, 0.3);
        }
        .sidebar h3 {
            font-family: 'Orbitron', sans-serif;
            color: #e53e3e;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .sidebar a {
            transition: all 0.3s ease;
        }
        .sidebar a:hover {
            background: #e53e3e;
            color: #ffffff;
            box-shadow: 0 0 10px rgba(229, 62, 62, 0.5);
        }
        .card {
            background: #1f252d;
            border: 1px solid #2d3748;
            border-radius: 0.75rem;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 20px rgba(229, 62, 62, 0.3);
        }
        .card h3 {
            font-family: 'Orbitron', sans-serif;
            color: #e53e3e;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .text-primary {
            color: #e53e3e;
        }
        .avatar {
            background: #2d3748;
            color: #a0aec0;
            font-weight: 500;
            border: 2px solid #e53e3e;
            box-shadow: 0 0 10px rgba(229, 62, 62, 0.3);
        }
        .main-heading {
            font-family: 'Orbitron', sans-serif;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(229, 62, 62, 0.5);
        }
        .btn-primary {
            background-color: #e53e3e;
            border-color: #e53e3e;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #c53030;
            border-color: #c53030;
            box-shadow: 0 0 10px rgba(229, 62, 62, 0.5);
        }
        .btn-danger {
            background-color: #dc2626;
            border-color: #dc2626;
            transition: all 0.3s ease;
        }
        .btn-danger:hover {
            background-color: #b91c1c;
            border-color: #b91c1c;
            box-shadow: 0 0 10px rgba(220, 38, 38, 0.5);
        }
    </style>
</head>
<body>
    @include('include.header')

    <div class="container mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <div class="lg:w-1/4">
                <div class="sidebar rounded-xl p-6">
                    <h3 class="text-lg font-semibold mb-6">Account Hub</h3>
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

            <div class="lg:w-3/4">
                <h2 class="text-4xl font-bold mb-8 main-heading">My Dashboard</h2>

                <div class="card p-6 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Profile Picture</h3>
                    <div class="flex items-center">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center mr-4 avatar">
                            <span class="text-sm text-center">No profile picture set</span>
                        </div>
                        <a href="#" class="text-primary hover:underline font-medium">Upload Picture</a>
                    </div>
                </div>

                <div class="card p-6 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Account Information</h3>
                    <p class="font-medium text-gray-400 mb-2">Contact Information</p>
                    <p class="text-gray-300">{{ $customer->first_name }} {{ $customer->last_name }}</p>
                    <p class="text-gray-300">{{ $customer->email }}</p>
                    <div class="mt-4 flex space-x-4">
                        <a href="{{ route('customer.edit_profile') }}" class="text-primary hover:underline font-medium">Edit</a>
                        <a href="#" class="text-primary hover:underline font-medium">Change Password</a>
                    </div>
                </div>

                <div class="card p-6 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Address Book</h3>
                    <p class="font-medium text-gray-400 mb-2">Default Shipping Address</p>
                    @if ($customer->address)
                        <p class="text-gray-300">{{ $customer->address }}</p>
                        <p class="text-gray-300">Zipcode: {{ $customer->zipcode }}</p>
                    @else
                        <p class="text-gray-500">You have not set a default shipping address.</p>
                    @endif
                </div>

                <!-- Builds Section -->
                <div class="card p-6">
                    <h3 class="text-lg font-semibold mb-4">My Builds</h3>
                    @if ($builds->isEmpty())
                        <p class="text-gray-500 mb-2">You have not saved any builds yet.</p>
                        <a href="{{ route('build.index') }}" class="text-primary hover:underline font-medium">Start building a PC now!</a>
                    @else
                        @foreach ($builds as $build)
                            <div class="mb-6 p-4 bg-gray-700 rounded-lg">
                                <h4 class="text-md font-semibold text-gray-200 mb-2">
                                    {{ $build->name ?? 'Build #' . $build->id }} - <span class="text-gray-400">Total Price:</span> {{ number_format($build->total_price, 2) }} LKR
                                </h4>
                                <div class="text-gray-300 space-y-1">
                                    <p><span class="font-medium text-gray-400">CPU:</span> {{ $build->cpu->name }}</p>
                                    <p><span class="font-medium text-gray-400">Motherboard:</span> {{ $build->motherboard->name }}</p>
                                    <p><span class="font-medium text-gray-400">GPU:</span> {{ $build->gpu->name }}</p>
                                    <p><span class="font-medium text-gray-400">RAM:</span> {{ $build->ram->name }}</p>
                                    <p><span class="font-medium text-gray-400">Storage:</span> {{ $build->storage->name }}</p>
                                    <p><span class="font-medium text-gray-400">Power Supply:</span> {{ $build->powerSupply->name }}</p>
                                </div>
                                <div class="mt-4 flex space-x-3">
                                    <a href="{{ route('build.purchase', $build->id) }}" class="btn btn-primary btn-sm">Purchase Build</a>
                                    <form action="{{ route('customer.build.delete', $build->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this build?')">Delete Build</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                        <div class="mt-6">
                            {{ $builds->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('include.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>