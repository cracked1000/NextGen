@extends('layouts')
@section('title', 'Login')
@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-900">
    <div class=" p-8 rounded-lg  w-full md:w-4/5 lg:w-1/2 xl:w-2/3">
        <h2 class="text-4xl font-bold mb-6  text-white ">Customer Login</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 ">
        <div class="flex flex-col bg-gray-100 rounded-3xl p-6">
    <h3 class="text-lg font-semibold mb-4">Registered Customers</h3>
    <p class="mb-4">If you have an account, sign in with your email address.</p>
    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('login.submit') }}" method="POST">
    @csrf
    <label for="role" class="block text-sm font-bold">Login As *</label>
    <select id="role" name="role" class="w-full p-3 mb-4 bg-white rounded-md" required>
        <option value="">Select your role</option>
        <option value="customer">Customer</option>
        <option value="seller">Seller</option>
        <option value="admin">Admin</option>
    </select>

    <label for="email" class="block text-sm font-bold">Email *</label>
    <input type="email" id="email" name="email" class="w-full p-3 mb-4 bg-white rounded-md" placeholder="Enter Your Email Here" required>
    
    <label for="password" class="block text-sm font-bold">Password *</label>
    <input type="password" id="password" name="password" class="w-full p-3 mb-4 bg-white rounded-md" placeholder="Enter Your Password Here" required>
    
    <div class="flex justify-between items-center mb-4">
        <a href="#" class="text-sm text-blue-400 hover:text-blue-500 transition-colors duration-200">Forgot Your Password?</a>
        <button type="submit" class="bg-red-600 hover:bg-red-700 transition-colors duration-200 text-white py-2 px-4 rounded-md">Sign In</button>
    </div>
</form>
</div>

            <div class="flex flex-col bg-white rounded-3xl p-6">
                <h3 class="text-lg font-semibold mb-4">New Customer?</h3>
                <p class="mb-4">Creating an account has many benefits:</p>
                <ul class="list-disc pl-6 mb-4 text-sm">
                    <li>Check out faster</li>
                    <li>Keep more than one address</li>
                    <li>Track orders and more</li>
                </ul>
                <a href="/signup" class="text-center block text-white bg-red-600 py-2 rounded-md hover:bg-red-700 transition-colors duration-200 justify-center">Create An Account</a>
            </div>
        </div>
    </div>
</div>
    
@endsection