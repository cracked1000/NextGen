@extends('layouts')
@section('title', 'User Profile')
@section('content')
    <div class="flex flex-col lg:flex-row mt-12">
        <div class="bg-gray-800 p-6 rounded-3xl lg:w-1/6 mr-6 ml-32">
            <div class="space-y-6">
                <a href="/editprofile"class="flex items-center text-gray-300 hover:text-blue-500 hover:bg-gray-700 p-2 rounded-md transition-colors duration-200 font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M12 12c2.75 0 5-2.25 5-5s-2.25-5-5-5-5 2.25-5 5 2.25 5 5 5zM12 14c-3.25 0-9 1.25-9 3.75V22h18v-4.25c0-2.5-5.75-3.75-9-3.75z"></path>
                    </svg>
                    Edit Profile
                </a>
            
                <a href="#"class="flex items-center text-gray-300 hover:text-blue-500 hover:bg-gray-700 p-2 rounded-md transition-colors duration-200 font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M4 8h16v12H4zm3 7h10v-2H7z"></path>
                    </svg>
                    Orders
                </a>
            </div>
        </div>

        <div class="lg:w-8/12 bg-gray-800 p-6 rounded-3xl mt-6 lg:mt-0">
            <h2 class="text-3xl mb-6 text-white font-extrabold">My Dashboard</h2>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-4">Profile Picture</h3>
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gray-300 rounded-full"></div>
                    <div>
                        <p class="text-gray-600">No profile picture set</p>
                        <a href="#"class="text-blue-500 hover:underline transition-all duration-200">Upload Picture</a>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md mb-6 mt-6">
                <h3 class="text-xl font-semibold mb-4">Account Information</h3>
                <div class="space-y-4">
                    <div>
                        <h4 class="text-gray-700">Contact Information</h4>
                        <p class="text-gray-600">Shahul hameed</p>
                        <p class="text-gray-600">ExampleAdress@gmail.com</p>
                        <div class="flex space-x-4 mt-2">
                            <a href="#"class="text-blue-500 hover:underline transition-all duration-200">Edit</a>
                            <a href="#"class="text-blue-500 hover:underline transition-all duration-200">Change Password</a>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-gray-700">Address Book</h4>
                        <a href="#"class="text-blue-500 hover:underline transition-all duration-200">Manage Addresses</a>
                    </div>

                    <div>
                        <h4 class="text-gray-700">Default Shipping Address</h4>
                        <p class="text-gray-600">You have not set a default shipping address.</p>
                        <a href="#"class="text-blue-500 hover:underline transition-all duration-200">Edit Address</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
