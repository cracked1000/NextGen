    <script>
        // Robust event handler function
        function addEvent(element, event, handler) {
            if (element.addEventListener) {
                element.addEventListener(event, handler, false);
            } else if (element.attachEvent) {
                element.attachEvent('on' + event, handler);
            } else {
                element['on' + event] = handler;
            }
        }

        // Mobile menu toggle with accessibility support
        const toggleButton = document.getElementById('navbar-toggle');
        const navbarMenu = document.getElementById('navbarNav');
        
        if (toggleButton && navbarMenu) {
            addEvent(toggleButton, 'click', function() {
                navbarMenu.classList.toggle('active');
                const expanded = navbarMenu.classList.contains('active');
                toggleButton.setAttribute('aria-expanded', expanded);
            });
        }

        // Desktop dropdowns with hover and click support
        const dropdowns = document.querySelectorAll('[data-dropdown]');
        
        dropdowns.forEach(dropdown => {
            const button = dropdown.querySelector('[data-dropdown-toggle]');
            const menu = dropdown.querySelector('[data-dropdown-menu]');
            
            if (button && menu) {
                // Click handler for desktop
                addEvent(button, 'click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const isActive = menu.classList.contains('active');
                    
                    // Close all other dropdowns first
                    document.querySelectorAll('[data-dropdown-menu].active').forEach(m => {
                        if (m !== menu) {
                            m.classList.remove('active');
                            const parentButton = m.closest('[data-dropdown]').querySelector('[data-dropdown-toggle]');
                            if (parentButton) parentButton.setAttribute('aria-expanded', 'false');
                        }
                    });
                    
                    // Toggle current dropdown
                    menu.classList.toggle('active');
                    button.setAttribute('aria-expanded', !isActive);
                });
                
                // Hover functionality for desktop
                addEvent(dropdown, 'mouseenter', function() {
                    if (window.innerWidth >= 1024) { // lg breakpoint
                        menu.classList.add('active');
                        button.setAttribute('aria-expanded', 'true');
                    }
                });
                
                addEvent(dropdown, 'mouseleave', function() {
                    if (window.innerWidth >= 1024) { // lg breakpoint
                        menu.classList.remove('active');
                        button.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        });

        // Mobile dropdowns
        const mobileDropdowns = document.querySelectorAll('[data-mobile-dropdown]');
        
        mobileDropdowns.forEach(dropdown => {
            const button = dropdown.querySelector('[data-mobile-dropdown-toggle]');
            const menu = dropdown.querySelector('[data-mobile-dropdown-menu]');
            
            if (button && menu) {
                addEvent(button, 'click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const isExpanded = button.getAttribute('aria-expanded') === 'true';
                    button.setAttribute('aria-expanded', !isExpanded);
                    
                    menu.classList.toggle('hidden');
                });
            }
        });

        // Close dropdowns when clicking outside
        addEvent(document, 'click', function(e) {
            document.querySelectorAll('[data-dropdown-menu].active').forEach(menu => {
                menu.classList.remove('active');
                const parentButton = menu.closest('[data-dropdown]').querySelector('[data-dropdown-toggle]');
                if (parentButton) parentButton.setAttribute('aria-expanded', 'false');
            });
        });

        // Keyboard accessibility
        addEvent(document, 'keydown', function(e) {
            if (e.key === 'Escape') {
                // Close all dropdowns
                document.querySelectorAll('[data-dropdown-menu].active').forEach(menu => {
                    menu.classList.remove('active');
                    const parentButton = menu.closest('[data-dropdown]').querySelector('[data-dropdown-toggle]');
                    if (parentButton) parentButton.setAttribute('aria-expanded', 'false');
                });
                
                // Close mobile menu if open
                if (navbarMenu && navbarMenu.classList.contains('active')) {
                    navbarMenu.classList.remove('active');
                    toggleButton.setAttribute('aria-expanded', 'false');
                }
            }
        });

        // Window resize handler for responsiveness
        let resizeTimer;
        addEvent(window, 'resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                // Reset mobile menu on desktop
                if (window.innerWidth >= 1024 && navbarMenu) {
                    navbarMenu.classList.remove('active');
                    if (toggleButton) toggleButton.setAttribute('aria-expanded', 'false');
                }
            }, 250);
        });
    </script>
</body>
</html><!DOCTYPE html>
<html lang="en">
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

        /* Smooth transitions for dropdown and mobile menu */
        .dropdown-menu {
            transition: opacity 0.2s ease, transform 0.2s ease;
            transform-origin: top;
            opacity: 0;
            transform: scaleY(0);
            pointer-events: none;
        }

        .dropdown-menu.active {
            opacity: 1;
            transform: scaleY(1);
            pointer-events: auto;
        }

        /* Mobile menu transition */
        #navbarNav {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        #navbarNav.active {
            max-height: 500px;
        }

        /* Focus styles for accessibility */
        a:focus, button:focus {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }

        /* Navigation link styling */
        .nav-link {
            position: relative;
            padding: 0.5rem 0;
            font-weight: 500;
            letter-spacing: 0.025em;
        }

        .nav-link:hover {
            color: #3b82f6;
        }
    </style>
</head>
<body>
    <nav class="bg-gray-900 text-white" aria-label="Main Navigation">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('index') }}">
                        <img src="{{ asset('images/Logo.png') }}" alt="NextGen Computing Logo" class="h-10" width="auto" height="40">
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="lg:hidden">
                    <button id="navbar-toggle" 
                        aria-controls="navbarNav" 
                        aria-expanded="false" 
                        aria-label="Toggle navigation menu"
                        class="p-2 rounded-md hover:bg-gray-800 focus:outline-none">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3 5h18a1 1 0 011 1v2a1 1 0 01-1 1H3a1 1 0 01-1-1V6a1 1 0 011-1zM3 12h18a1 1 0 011 1v2a1 1 0 01-1 1H3a1 1 0 01-1-1v-2a1 1 0 011-1zM3 19h18a1 1 0 011 1v2a1 1 0 01-1 1H3a1 1 0 01-1-1v-2a1 1 0 011-1z"></path>
                        </svg>
                    </button>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex lg:items-center lg:justify-between lg:flex-1 lg:ml-10">
                    <div class="flex space-x-8">
                        <a href="{{ route('index') }}" class="nav-link">Home</a>
                        <a href="{{ route('build.index') }}" class="nav-link">Build PC</a>
                        <a href="{{ route('secondhand.index') }}" class="nav-link">Market</a>
                        <a href="{{ route('quotation.index') }}" class="nav-link">Quotation Generator</a>
                        <a href="{{ route('technical.network') }}" class="nav-link">Technical Network</a>
                        <a href="{{ route('reviews.index') }}" class="nav-link">Reviews</a>
                    </div>

                    <div class="flex items-center space-x-8">
                        @if (Auth::check())
                            @if (Auth::user()->role === 'seller' || Auth::user()->role === 'admin')
                                <div class="relative" data-dropdown>
                                    <button class="nav-link flex items-center" 
                                        aria-expanded="false"
                                        aria-haspopup="true"
                                        data-dropdown-toggle>
                                        Profile Management
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    <div class="absolute right-0 z-10 dropdown-menu bg-gray-900 shadow-md rounded-lg mt-2 w-48" data-dropdown-menu>
                                        @if (Auth::user()->role === 'seller')
                                            <a class="block px-4 py-2 hover:text-blue-500 hover:bg-gray-950" href="{{ route('sellers.dashboard') }}">Seller Dashboard</a>
                                        @else
                                            <a class="block px-4 py-2 hover:text-blue-500 hover:bg-gray-950" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                                        @endif
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 hover:text-blue-500 hover:bg-gray-950">Logout</button>
                                        </form>
                                    </div>
                                </div>
                            @elseif (Auth::user()->role === 'customer')
                                <a href="{{ route('customer.profile') }}" class="nav-link">Profile Management</a>
                                <form action="{{ route('logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="nav-link">Logout</button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="nav-link">Login</a>
                            <a href="{{ route('signup') }}" class="nav-link">Sign Up</a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div class="lg:hidden" id="navbarNav">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('index') }}" class="block py-2 px-3 hover:bg-gray-800 rounded-md">Home</a>
                    <a href="{{ route('build.index') }}" class="block py-2 px-3 hover:bg-gray-800 rounded-md">Build PC</a>
                    <a href="{{ route('secondhand.index') }}" class="block py-2 px-3 hover:bg-gray-800 rounded-md">Market</a>
                    <a href="{{ route('quotation.index') }}" class="block py-2 px-3 hover:bg-gray-800 rounded-md">Quotation Generator</a>
                    <a href="{{ route('technical.network') }}" class="block py-2 px-3 hover:bg-gray-800 rounded-md">Technical Network</a>
                    <a href="{{ route('reviews.index') }}" class="block py-2 px-3 hover:bg-gray-800 rounded-md">Reviews</a>

                    @if (Auth::check())
                        @if (Auth::user()->role === 'seller' || Auth::user()->role === 'admin')
                            <div class="relative" data-mobile-dropdown>
                                <button class="w-full text-left py-2 px-3 hover:bg-gray-800 rounded-md flex items-center justify-between" 
                                    data-mobile-dropdown-toggle
                                    aria-expanded="false"
                                    aria-haspopup="true">
                                    Profile Management
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="hidden bg-gray-800 rounded mt-1 ml-3" data-mobile-dropdown-menu>
                                    @if (Auth::user()->role === 'seller')
                                        <a class="block px-4 py-2 hover:bg-gray-700 rounded-md" href="{{ route('sellers.dashboard') }}">Seller Dashboard</a>
                                    @else
                                        <a class="block px-4 py-2 hover:bg-gray-700 rounded-md" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                                    @endif
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-700 rounded-md">Logout</button>
                                    </form>
                                </div>
                            </div>
                        @elseif (Auth::user()->role === 'customer')
                            <a href="{{ route('customer.profile') }}" class="block py-2 px-3 hover:bg-gray-800 rounded-md">Profile Management</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left py-2 px-3 hover:bg-gray-800 rounded-md">Logout</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block py-2 px-3 hover:bg-gray-800 rounded-md">Login</a>
                        <a href="{{ route('signup') }}" class="block py-2 px-3 hover:bg-gray-800 rounded-md">Sign Up</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>
    <div class="border-b-4 border-red-600"></div>

    <script>
        // Robust event handler function
        function addEvent(element, event, handler) {
            if (element.addEventListener) {
                element.addEventListener(event, handler, false);
            } else if (element.attachEvent) {
                element.attachEvent('on' + event, handler);
            } else {
                element['on' + event] = handler;
            }
        }

        // Mobile menu toggle with accessibility support
        const toggleButton = document.getElementById('navbar-toggle');
        const navbarMenu = document.getElementById('navbarNav');
        
        if (toggleButton && navbarMenu) {
            addEvent(toggleButton, 'click', function() {
                navbarMenu.classList.toggle('active');
                const expanded = navbarMenu.classList.contains('active');
                toggleButton.setAttribute('aria-expanded', expanded);
            });
        }

        // Desktop dropdowns with hover and click support
        const dropdowns = document.querySelectorAll('[data-dropdown]');
        
        dropdowns.forEach(dropdown => {
            const button = dropdown.querySelector('[data-dropdown-toggle]');
            const menu = dropdown.querySelector('[data-dropdown-menu]');
            
            if (button && menu) {
                // Click handler for desktop
                addEvent(button, 'click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const isActive = menu.classList.contains('active');
                    
                    // Close all other dropdowns first
                    document.querySelectorAll('[data-dropdown-menu].active').forEach(m => {
                        if (m !== menu) {
                            m.classList.remove('active');
                            const parentButton = m.closest('[data-dropdown]').querySelector('[data-dropdown-toggle]');
                            if (parentButton) parentButton.setAttribute('aria-expanded', 'false');
                        }
                    });
                    
                    // Toggle current dropdown
                    menu.classList.toggle('active');
                    button.setAttribute('aria-expanded', !isActive);
                });
                
                // Hover functionality for desktop
                addEvent(dropdown, 'mouseenter', function() {
                    if (window.innerWidth >= 1024) { // lg breakpoint
                        menu.classList.add('active');
                        button.setAttribute('aria-expanded', 'true');
                    }
                });
                
                addEvent(dropdown, 'mouseleave', function() {
                    if (window.innerWidth >= 1024) { // lg breakpoint
                        menu.classList.remove('active');
                        button.setAttribute('aria-expanded', 'false');
                    }
                });
            }
        });

        // Mobile dropdowns
        const mobileDropdowns = document.querySelectorAll('[data-mobile-dropdown]');
        
        mobileDropdowns.forEach(dropdown => {
            const button = dropdown.querySelector('[data-mobile-dropdown-toggle]');
            const menu = dropdown.querySelector('[data-mobile-dropdown-menu]');
            
            if (button && menu) {
                addEvent(button, 'click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const isExpanded = button.getAttribute('aria-expanded') === 'true';
                    button.setAttribute('aria-expanded', !isExpanded);
                    
                    menu.classList.toggle('hidden');
                });
            }
        });

        // Close dropdowns when clicking outside
        addEvent(document, 'click', function(e) {
            document.querySelectorAll('[data-dropdown-menu].active').forEach(menu => {
                menu.classList.remove('active');
                const parentButton = menu.closest('[data-dropdown]').querySelector('[data-dropdown-toggle]');
                if (parentButton) parentButton.setAttribute('aria-expanded', 'false');
            });
        });

        // Keyboard accessibility
        addEvent(document, 'keydown', function(e) {
            if (e.key === 'Escape') {
                // Close all dropdowns
                document.querySelectorAll('[data-dropdown-menu].active').forEach(menu => {
                    menu.classList.remove('active');
                    const parentButton = menu.closest('[data-dropdown]').querySelector('[data-dropdown-toggle]');
                    if (parentButton) parentButton.setAttribute('aria-expanded', 'false');
                });
                
                // Close mobile menu if open
                if (navbarMenu && navbarMenu.classList.contains('active')) {
                    navbarMenu.classList.remove('active');
                    toggleButton.setAttribute('aria-expanded', 'false');
                }
            }
        });

        // Window resize handler for responsiveness
        let resizeTimer;
        addEvent(window, 'resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                // Reset mobile menu on desktop
                if (window.innerWidth >= 1024 && navbarMenu) {
                    navbarMenu.classList.remove('active');
                    if (toggleButton) toggleButton.setAttribute('aria-expanded', 'false');
                }
            }, 250);
        });
    </script>
</body>
</html>