<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - NextGen Computing</title>
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
        .btn-primary {
            background: #e53e3e;
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(229, 62, 62, 0.3);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: #c53030;
            box-shadow: 0 0 15px rgba(229, 62, 62, 0.5);
        }
        .btn-secondary {
            background: #4a5568;
            border: none;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background: #2d3748;
            box-shadow: 0 0 10px rgba(229, 62, 62, 0.3);
        }
        .text-primary {
            color: #e53e3e;
        }
        .main-heading {
            font-family: 'Orbitron', sans-serif;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(229, 62, 62, 0.5);
        }
        input, textarea, select {
            background: #2d3748;
            border: 1px solid #4a5568;
            color: #e2e8f0;
            transition: all 0.3s ease;
        }
        input:focus, textarea:focus, select:focus {
            border-color: #e53e3e;
            box-shadow: 0 0 10px rgba(229, 62, 62, 0.3);
        }
        .avatar {
            background: #2d3748;
            color: #a0aec0;
            font-weight: 500;
            border: 2px solid #e53e3e;
            box-shadow: 0 0 10px rgba(229, 62, 62, 0.3);
            object-fit: cover;
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
                    </ul>
                </div>
            </div>

            <div class="lg:w-3/4">
                <h2 class="text-4xl font-bold mb-8 main-heading">Edit Profile</h2>

                @if (session('success'))
                    <div class="bg-green-900/50 text-green-300 rounded-xl p-4 mb-8 text-center border border-green-700/50">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-900/50 text-red-300 rounded-xl p-4 mb-8 text-center border border-red-700/50">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card p-8 text-gray-300">
                    <form action="{{ route('customer.update_profile') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Profile Photo Upload -->
                        <div class="mb-6">
                            <label for="profile_photo" class="block text-gray-300 font-medium mb-2">Profile Photo</label>
                            <div class="flex items-center mb-4">
                                @if ($customer->profile_photo)
                                    <img src="{{ asset('storage/' . $customer->profile_photo) }}" alt="Profile Picture" class="w-16 h-16 rounded-full mr-4 avatar">
                                @else
                                    <div class="w-16 h-16 rounded-full flex items-center justify-center mr-4 avatar">
                                        <span class="text-sm text-center">{{ strtoupper(substr($customer->first_name, 0, 1)) . strtoupper(substr($customer->last_name, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <input type="file" name="profile_photo" id="profile_photo" class="w-full p-3 rounded-lg @error('profile_photo') border-red-500 @enderror">
                            </div>
                            @error('profile_photo')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="mb-6">
                                <label for="first_name" class="block text-gray-300 font-medium mb-2">First Name</label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $customer->first_name) }}" class="w-full p-3 rounded-lg @error('first_name') border-red-500 @enderror" required>
                                @error('first_name')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label for="last_name" class="block text-gray-300 font-medium mb-2">Last Name</label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $customer->last_name) }}" class="w-full p-3 rounded-lg @error('last_name') border-red-500 @enderror" required>
                                @error('last_name')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="address" class="block text-gray-300 font-medium mb-2">Address</label>
                            <textarea name="address" id="address" class="w-full p-3 rounded-lg @error('address') border-red-500 @enderror" rows="3">{{ old('address', $customer->address) }}</textarea>
                            @error('address')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="mb-6">
                                <label for="zipcode" class="block text-gray-300 font-medium mb-2">Zipcode</label>
                                <input type="text" name="zipcode" id="zipcode" value="{{ old('zipcode', $customer->zipcode) }}" class="w-full p-3 rounded-lg @error('zipcode') border-red-500 @enderror">
                                @error('zipcode')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label for="phone_number" class="block text-gray-300 font-medium mb-2">Phone Number</label>
                                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $customer->phone_number) }}" class="w-full p-3 rounded-lg @error('phone_number') border-red-500 @enderror" required>
                                @error('phone_number')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="mb-6">
                                <label for="optional_phone_number" class="block text-gray-300 font-medium mb-2">Optional Phone Number</label>
                                <input type="text" name="optional_phone_number" id="optional_phone_number" value="{{ old('optional_phone_number', $customer->optional_phone_number) }}" class="w-full p-3 rounded-lg @error('optional_phone_number') border-red-500 @enderror">
                                @error('optional_phone_number')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label for="email" class="block text-gray-300 font-medium mb-2">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}" class="w-full p-3 rounded-lg @error('email') border-red-500 @enderror" required>
                                @error('email')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="status" class="block text-gray-300 font-medium mb-2">Status</label>
                            <select name="status" id="status" class="w-full p-3 rounded-lg @error('status') border-red-500 @enderror" required>
                                <option value="active" {{ old('status', $customer->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $customer->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex space-x-4 justify-center">
                            <button type="submit" class="btn-primary text-white py-3 px-6 rounded-lg">
                                Save Changes
                            </button>
                            <a href="{{ route('customer.profile') }}" class="btn-secondary text-white py-3 px-6 rounded-lg">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('include.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>