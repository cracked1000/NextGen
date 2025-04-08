<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextGen Coumputing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" >
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-900 text-white">
    @include('include.header')
    <div class="relative overflow-hidden">
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/50 z-10"></div>
        
        <!-- Animated Background -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute w-full h-full" style="background-image: 
                linear-gradient(45deg, transparent 0%, rgba(255,0,0,0.05) 50%, transparent 100%),
                linear-gradient(-45deg, transparent 0%, rgba(0,255,255,0.05) 50%, transparent 100%);
                background-size: 100px 100px;
                animation: moveBackground 20s linear infinite;
            "></div>
        </div>

        <section class="relative z-20 min-h-screen flex flex-col justify-center items-center text-center px-4">
            <div class="container mx-auto">
                <div class="flex justify-center mb-12">
                    <div class="relative">
                        <div class="absolute -inset-2 bg-red-500/50 rounded-xl blur-xl"></div>
                        <div class="relative flex space-x-8">
                        <img src="{{ asset('images/cpu.png') }}" alt="cpu" class="w-40">
                        <img src="{{ asset('images/SeekPng 2.png') }}" alt="cpu2" class="w-80">
                        
                        </div>
                    </div>
                </div>
                
                <h1 class="text-6xl font-bold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-pink-500">
                    BUILD YOUR CUSTOM PC
                </h1>
                
                <div class="space-x-6 mb-16">
                    <button class="bg-red-600 text-white py-4 px-8 rounded-lg text-xl font-semibold 
                        hover:bg-red-700 transition transform hover:scale-105 
                        shadow-lg hover:shadow-red-500/50">
                        BUILD AS A BEGINNER
                    </button>
                    <button class="bg-blue-600 text-white py-4 px-8 rounded-lg text-xl font-semibold 
                        hover:bg-blue-700 transition transform hover:scale-105 
                        shadow-lg hover:shadow-blue-500/50">
                        BUILD AS AN EXPERT
                    </button>
                </div>
            </div>
        </section>
    </div>

    <section class="bg-transparent py-16">
        <div class="container mx-auto">
            <h2 class="text-4xl text-center mb-16 font-bold text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-pink-500">
                BEGINNERS IN BUILDING PC, WE GOT YOU!
            </h2>
            
            <div class="grid grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Component 1 -->
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 text-center glow-effect border border-gray-700/50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto mb-4 text-red-500">
                        <rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect>
                        <rect x="9" y="9" width="6" height="6"></rect>
                        <line x1="9" y1="1" x2="9" y2="4"></line>
                        <line x1="15" y1="1" x2="15" y2="4"></line>
                        <line x1="9" y1="20" x2="9" y2="23"></line>
                        <line x1="15" y1="20" x2="15" y2="23"></line>
                    </svg>
                    <h3 class="text-xl font-semibold mb-2">CPU</h3>
                    <p class="text-gray-300 text-sm">Central Processing Unit</p>
                </div>

                <!-- Component 2 -->
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 text-center glow-effect border border-gray-700/50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto mb-4 text-blue-500">
                        <line x1="20" y1="20" x2="20" y2="16"></line>
                        <line x1="4" y1="20" x2="4" y2="16"></line>
                        <line x1="20" y1="8" x2="20" y2="4"></line>
                        <line x1="4" y1="8" x2="4" y2="4"></line>
                        <rect x="4" y="4" width="16" height="12" rx="2"></rect>
                    </svg>
                    <h3 class="text-xl font-semibold mb-2">RAM</h3>
                    <p class="text-gray-300 text-sm">Random Access Memory</p>
                </div>

                <!-- Component 3 -->
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 text-center glow-effect border border-gray-700/50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto mb-4 text-green-500">
                        <rect x="2" y="4" width="20" height="14" rx="2" ry="2"></rect>
                        <line x1="22" y1="18" x2="2" y2="18"></line>
                        <line x1="6" y1="22" x2="18" y2="22"></line>
                    </svg>
                    <h3 class="text-xl font-semibold mb-2">GPU</h3>
                    <p class="text-gray-300 text-sm">Graphics Processing Unit</p>
                </div>

                <!-- Component 4 -->
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 text-center glow-effect border border-gray-700/50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto mb-4 text-purple-500">
                        <rect x="3" y="4" width="18" height="12" rx="2" ry="2"></rect>
                        <line x1="2" y1="20" x2="22" y2="20"></line>
                    </svg>
                    <h3 class="text-xl font-semibold mb-2">MOTHERBOARD</h3>
                    <p class="text-gray-300 text-sm">Main Circuit Board</p>
                </div>

                <!-- Component 5 -->
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 text-center glow-effect border border-gray-700/50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto mb-4 text-yellow-500">
                        <path d="M22 16H2a2 2 0 0 1 2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 2 2z"></path>
                        <line x1="5" y1="20" x2="19" y2="20"></line>
                    </svg>
                    <h3 class="text-xl font-semibold mb-2">STORAGE</h3>
                    <p class="text-gray-300 text-sm">Data Storage Device</p>
                </div>

                <!-- Component 6 -->
                <div class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 text-center glow-effect border border-gray-700/50">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="mx-auto mb-4 text-orange-500">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <line x1="22" y1="11" x2="2" y2="11"></line>
                        <line x1="7" y1="4" x2="7" y2="8"></line>
                        <line x1="17" y1="4" x2="17" y2="8"></line>
                    </svg>
                    <h3 class="text-xl font-semibold mb-2">POWER SUPPLY</h3>
                    <p class="text-gray-300 text-sm">Electrical Power Unit</p>
                </div>
            </div>
        </div>
    </section>

  @include('include.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" ></script>
</body>
</html>