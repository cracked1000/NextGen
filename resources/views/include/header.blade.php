<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'NextGen Computing') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/Logo.png') }}?v=2" type="image/png">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Import Jersey 10 from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Jersey+10&display=swap" rel="stylesheet">

    <!-- Custom CSS to override Tailwind's font -->
    <style>
        html, body {
            font-family: 'Jersey 10', sans-serif !important;
            font-size: large;
        }

        /* Override Tailwind's font-sans utility */
        .font-sans {
            font-family: 'Jersey 10', sans-serif !important;
        }
    </style>
    
</head>

<nav class="shadow-md text-white bg-gray-900">
    <div class="max-w-screen-xl mx-auto px-4 py-2.5">
        <div class="flex items-center justify-between">
            <a href="{{ route('index') }}">
                <img src="{{ asset('images/Logo.png') }}" alt="NextGen Computing Logo" class="h-12">
            </a>

            <button class="lg:hidden p-2 hover:bg-gray-950 rounded-md focus:outline-none" aria-controls="navbarNav" aria-expanded="false" id="navbar-toggle">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M3 5h18a1 1 0 011 1v2a1 1 0 01-1 1H3a1 1 0 01-1-1V6a1 1 0 011-1zM3 12h18a1 1 0 011 1v2a1 1 0 01-1 1H3a1 1 0 01-1-1v-2a1 1 0 011-1zM3 19h18a1 1 0 011 1v2a1 1 0 01-1 1H3a1 1 0 01-1-1v-2a1 1 0 011-1z"></path>
                </svg>
            </button>

            <div class="hidden lg:flex space-x-8">
                <a class="hover:text-blue-500" href="{{ route('index') }}">Home</a>
                <a class="hover:text-blue-500" href="{{ route('build.index') }}">Build PC</a>
                <a class="hover:text-blue-500" href="{{ route('secondhand.index') }}">Market</a>
                <a class="hover:text-blue-500" href="{{ route('quotation.index') }}">Quotation Generator</a>
                <a class="hover:text-blue-500" href="{{ route('technical.network') }}">Technical Network</a>
                


                @if (Auth::check())
                    @if (Auth::user()->role === 'seller')
                        <div class="relative group">
                            <button class="hover:text-blue-500 flex items-center">Profile Management
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6 9l6 6 6-6"></path>
                                </svg>
                            </button>
                            <div class="absolute hidden bg-gray-900 shadow-md rounded-lg mt-2 w-48 group-hover:block">
                                <a class="block px-4 py-2 hover:text-blue-500 hover:bg-gray-950" href="{{ route('sellers.dashboard') }}">Seller Dashboard</a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 hover:text-blue-500 hover:bg-gray-950">Logout</button>
                                </form>
                            </div>
                        </div>
                    @elseif (Auth::user()->role === 'admin')
                        <div class="relative group">
                            <button class="hover:text-blue-500 flex items-center">Profile Management
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6 9l6 6 6-6"></path>
                                </svg>
                            </button>
                            <div class="absolute hidden bg-gray-900 shadow-md rounded-lg mt-2 w-48 group-hover:block">
                                <a class="block px-4 py-2 hover:text-blue-500 hover:bg-gray-950" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 hover:text-blue-500 hover:bg-gray-950">Logout</button>
                                </form>
                            </div>
                        </div>
                    @elseif (Auth::user()->role === 'customer')
                        <a class="hover:text-blue-500" href="{{ route('customer.profile') }}">Profile Management</a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-blue-500">Logout</button>
                        </form>
                    @endif
                @else
                    <a class="hover:text-blue-500" href="{{ route('login') }}">Login</a>
                    <a class="hover:text-blue-500" href="{{ route('signup') }}">Sign Up</a>
                @endif
            </div>
        </div>
        <div class="lg:hidden collapse" id="navbarNav">
            <ul class="space-y-4">
                <li><a class="block py-2 px-4 hover:bg-gray-950" href="{{ route('index') }}">Home</a></li>
                <li><a class="block py-2 px-4 hover:bg-gray-950" href="{{ route('build.index') }}">Build PC</a></li>
                <li><a class="block py-2 px-4 hover:bg-gray-950" href="{{ route('secondhand.index') }}">Market</a></li>
                @if (Auth::check())
                    @if (Auth::user()->role === 'seller')
                        <li class="relative">
                            <button class="hover:text-blue-500 w-full flex items-center justify-between">
                                Profile Management
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6 9l6 6 6-6"></path>
                                </svg>
                            </button>
                            <div class="absolute hidden bg-gray-900 shadow-md rounded-lg mt-2 w-48">
                                <a class="block px-4 py-2 hover:bg-gray-950" href="{{ route('sellers.dashboard') }}">Seller Dashboard</a>
                                <a class="block px-4 py-2 hover:bg-gray-950" href="{{ route('profile') }}">Profile</a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-950">Logout</button>
                                </form>
                            </div>
                        </li>
                    @elseif (Auth::user()->role === 'admin')
                        <li class="relative">
                            <button class="hover:text-blue-500 w-full flex items-center justify-between">
                                Profile Management
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6 9l6 6 6-6"></path>
                                </svg>
                            </button>
                            <div class="absolute hidden bg-gray-900 shadow-md rounded-lg mt-2 w-48">
                                <a class="block px-4 py-2 hover:bg-gray-950" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-950">Logout</button>
                                </form>
                            </div>
                        </li>
                    @elseif (Auth::user()->role === 'customer')
                        <li><a class="block py-2 px-4 hover:bg-gray-950" href="{{ route('customer.profile') }}">Profile Management</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left py-2 px-4 hover:bg-gray-950">Logout</button>
                            </form>
                        </li>
                    @endif
                @else
                    <li><a class="block py-2 px-4 hover:bg-gray-950" href="{{ route('login') }}">Login</a></li>
                    <li><a class="block py-2 px-4 hover:bg-gray-950" href="{{ route('signup') }}">Sign Up</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>
<div class="border-b-4 border-red-600"></div>

<script>
const toggleButton = document.getElementById('navbar-toggle');
const navbarMenu = document.getElementById('navbarNav');

toggleButton.addEventListener('click', () => {
    navbarMenu.classList.toggle('collapse');
});

const dropdownButton = document.querySelectorAll('.relative');
dropdownButton.forEach(button => {
    button.addEventListener('click', () => {
        const dropdownMenu = button.querySelector('div');
        dropdownMenu.classList.toggle('hidden');
    });
});
</script>