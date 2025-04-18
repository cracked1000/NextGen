@extends('layouts')
@section('title', 'SignUp')
@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-900">
        <div class="p-8 rounded-xl w-full md:w-4/5 lg:w-1/2 xl:w-2/3">
            <h2 class="text-4xl font-bold mb-6 text-white">Create Your Account</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="flex flex-col bg-gray-100 rounded-3xl p-6">
                    <h3 class="text-lg font-semibold mb-4">Sign Up</h3>
                    @if ($errors->any())
                        <div class="mb-4 text-red-600">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('signup.store') }}" method="POST">
                        @csrf
                        <!-- Role -->
                        <label for="role" class="block text-sm font-bold">Sign Up As *</label>
                        <select id="role" name="role" class="w-full p-3 mb-4 bg-white rounded-md" required>
                            <option value="">Select your role</option>
                            <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="seller" {{ old('role') == 'seller' ? 'selected' : '' }}>Seller</option>
                        </select>

                        <!-- First Name -->
                        <label for="first_name" class="block text-sm font-bold">First Name *</label>
                        <input type="text" id="first_name" name="first_name" class="w-full p-3 mb-4 bg-white rounded-md" placeholder="Your First Name" value="{{ old('first_name') }}" required>

                        <!-- Last Name -->
                        <label for="last_name" class="block text-sm font-bold">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" class="w-full p-3 mb-4 bg-white rounded-md" placeholder="Your Last Name" value="{{ old('last_name') }}" required>

                        <!-- Email -->
                        <label for="email" class="block text-sm font-bold">Email *</label>
                        <input type="email" id="email" name="email" class="w-full p-3 mb-4 bg-white rounded-md" placeholder="Your Email" value="{{ old('email') }}" required>

                        <!-- Address -->
                        <label for="address" class="block text-sm font-bold">Address *</label>
                        <textarea id="address" name="address" class="w-full p-3 mb-4 bg-white rounded-md" placeholder="Your Address" required>{{ old('address') }}</textarea>

                        <!-- Zipcode -->
                        <label for="zipcode" class="block text-sm font-bold">Zipcode *</label>
                        <input type="text" id="zipcode" name="zipcode" class="w-full p-3 mb-4 bg-white rounded-md" placeholder="Your Zipcode" value="{{ old('zipcode') }}" required>

                        <!-- Phone Number -->
                        <label for="phone_number" class="block text-sm font-bold">Phone Number *</label>
                        <input type="text" id="phone_number" name="phone_number" class="w-full p-3 mb-4 bg-white rounded-md" placeholder="Your Phone Number" value="{{ old('phone_number') }}" required>

                        <!-- Optional Phone Number -->
                        <label for="optional_phone_number" class="block text-sm font-bold">Optional Phone Number</label>
                        <input type="text" id="optional_phone_number" name="optional_phone_number" class="w-full p-3 mb-4 bg-white rounded-md" placeholder="Optional Phone Number" value="{{ old('optional_phone_number') }}">

                        <!-- Password -->
                        <label for="password" class="block text-sm font-bold">Password *</label>
                        <input type="password" id="password" name="password" class="w-full p-3 mb-4 bg-white rounded-md" placeholder="Your Password" required>

                        <!-- Password Confirmation -->
                        <label for="password_confirmation" class="block text-sm font-bold">Confirm Password *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="w-full p-3 mb-4 bg-white rounded-md" placeholder="Confirm Your Password" required>

                        <div class="flex justify-between items-center mb-4">
                            <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition-colors duration-200">Sign Up</button>
                        </div>
                    </form>
                </div>

                <div class="flex flex-col bg-gray-200 rounded-3xl p-6">
                    <h3 class="text-lg font-semibold mb-4">Why Create An Account?</h3>
                    <p class="mb-4">Creating an account has many benefits:</p>
                    <ul class="list-disc pl-6 mb-4 text-sm">
                        <li>Check out faster</li>
                        <li>Keep more than one address</li>
                        <li>Track orders and more</li>
                    </ul>
                    <a href="/login" class="text-center block text-white bg-red-600 py-2 rounded-md hover:bg-red-700 transition-colors duration-200">Already have an account? Sign In</a>
                </div>
            </div>
        </div>
    </div>
@endsection