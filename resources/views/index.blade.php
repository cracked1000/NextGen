<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextGen Computing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @keyframes floatAnimation {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 0.8; }
            50% { opacity: 0.4; }
        }
        
        @keyframes moveBackground {
            0% { background-position: 0 0; }
            100% { background-position: 100px 100px; }
        }
        
        .animate-float {
            animation: floatAnimation 6s ease-in-out infinite;
        }
        
        .component-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 25px -5px rgba(255, 0, 130, 0.2), 0 10px 10px -5px rgba(0, 204, 255, 0.1);
        }
        
        .glow-hover:hover {
            box-shadow: 0 0 20px rgba(255, 0, 130, 0.5);
        }
        
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        
        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background-color: red;
            border-radius: 50%;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const particles = document.querySelector('.particles');
            
            for (let i = 0; i < 100; i++) {
                let particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Random positioning
                particle.style.left = Math.random() * 100 + 'vw';
                particle.style.top = Math.random() * 100 + 'vh';
                
                // Random size
                let size = Math.random() * 5 + 1;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                
                // Random opacity
                particle.style.opacity = Math.random() * 0.5 + 0.1;
                
                // Random animation duration
                let animationDuration = Math.random() * 20 + 10;
                particle.style.animation = `pulse ${animationDuration}s infinite alternate`;
                
                particles.appendChild(particle);
            }
        });
    </script>
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white font-sans">
    @include('include.header')

    <div class="relative overflow-hidden pt-24">
        <!-- Particles Background -->
        <div class="particles"></div>
        
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-black/80 to-black/40 z-10"></div>
        
        <!-- Animated Background -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute w-full h-full" style="background-image: 
                linear-gradient(45deg, transparent 0%, rgba(255,0,130,0.05) 50%, transparent 100%),
                linear-gradient(-45deg, transparent 0%, rgba(0,204,255,0.05) 50%, transparent 100%);
                background-size: 100px 100px;
                animation: moveBackground 20s linear infinite;
            "></div>
        </div>

        <section class="relative z-20 min-h-screen flex flex-col justify-center items-center text-center px-4">
            <div class="container mx-auto">
                <div class="flex justify-center mb-12 relative">
                    <div class="animate-float">
                        <div class="absolute -inset-4 bg-gradient-to-r from-blue-500/30 to-red-500/30 rounded-full blur-2xl"></div>
                        <div class="relative flex items-center justify-center">
                            <img src="images/cpu.png" alt="CPU" class="w-36 transform -rotate-12">
                            <img src="images/Seekpng 2.png" alt="images/pc-right-image.png" class="w-64 ml-10 transform rotate-3">
                            <img src="images/03.png" alt="images/pc-right-image.png" class="w-64  transform rotate-12">
                        </div>
                    </div>
                </div>
                
                <h1 class="text-7xl font-extrabold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-red-500 to-pink-500">
                    BUILD YOUR CUSTOM PC
                </h1>
                
                <p class="text-xl text-gray-300 max-w-3xl mx-auto mb-10">
                    Unleash your potential with a custom-built PC that perfectly matches your needs.
                    Whether you're a gamer, creator, or professional, we've got you covered.
                </p>
                
                <div class="space-x-6 mb-20">
                    <button class="relative group overflow-hidden bg-gradient-to-r from-red-600 to-pink-600 text-white py-4 px-8 rounded-lg text-xl font-semibold 
                        transition transform hover:scale-105 shadow-lg">
                        <span class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-red-600 to-pink-600 opacity-75 
                        group-hover:opacity-0 transition-opacity duration-300 z-0"></span>
                        <span class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-red-700 to-pink-700 opacity-0 
                        group-hover:opacity-75 transition-opacity duration-300 z-0"></span>
                        <span class="relative z-10 flex items-center">
                            <i class="fas fa-cogs mr-2"></i>
                            BUILD AS A BEGINNER
                        </span>
                    </button>
                    <button class="relative group overflow-hidden bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 px-8 rounded-lg text-xl font-semibold 
                        transition transform hover:scale-105 shadow-lg">
                        <span class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-blue-600 to-indigo-600 opacity-75 
                        group-hover:opacity-0 transition-opacity duration-300 z-0"></span>
                        <span class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-blue-700 to-indigo-700 opacity-0 
                        group-hover:opacity-75 transition-opacity duration-300 z-0"></span>
                        <span class="relative z-10 flex items-center">
                            <i class="fas fa-laptop-code mr-2"></i>
                            BUILD AS AN EXPERT
                        </span>
                    </button>
                </div>
                
                <div class="flex justify-center">
                    <a href="#components" class="text-gray-400 animate-bounce">
                        <i class="fas fa-chevron-down text-3xl"></i>
                    </a>
                </div>
            </div>
        </section>
    </div>

    <section id="components" class="relative py-24 overflow-hidden">
        <!-- Background Effects -->
        <div class="absolute inset-0 bg-gradient-to-b from-black via-gray-900 to-black"></div>
        <div class="absolute inset-0 opacity-10">
            <div class="absolute w-full h-full" style="background-image: 
                radial-gradient(circle at 20% 30%, rgba(255,0,130,0.1) 0%, transparent 20%),
                radial-gradient(circle at 80% 70%, rgba(0,204,255,0.1) 0%, transparent 20%);
            "></div>
        </div>
        
        <div class="container mx-auto relative z-10">
            <div class="text-center mb-20">
                <span class="inline-block px-3 py-1 text-sm font-semibold text-red-400 bg-red-900/30 rounded-full mb-4">PC BUILDING GUIDE</span>
                <h2 class="text-5xl font-extrabold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-pink-500 to-red-500">
                    BEGINNERS IN BUILDING PC, WE GOT YOU!
                </h2>
                <p class="text-gray-300 max-w-2xl mx-auto">
                    Learn about the essential components that make up a powerful custom PC. 
                    We'll guide you through each part to help you make informed decisions.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Component 1 -->
                <div class="component-card bg-gradient-to-br from-gray-800/50 to-gray-900/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-gray-700/30 transition-all duration-300 group glow-hover">
                    <div class="relative w-24 h-24 mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-red-500/20 rounded-full blur-xl group-hover:bg-red-500/30 transition-colors"></div>
                        <div class="relative flex items-center justify-center h-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-red-500">
                                <rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect>
                                <rect x="9" y="9" width="6" height="6"></rect>
                                <line x1="9" y1="1" x2="9" y2="4"></line>
                                <line x1="15" y1="1" x2="15" y2="4"></line>
                                <line x1="9" y1="20" x2="9" y2="23"></line>
                                <line x1="15" y1="20" x2="15" y2="23"></line>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-white group-hover:text-red-400 transition-colors">CPU</h3>
                    <p class="text-gray-400 mb-4">Central Processing Unit - The brain of your computer that executes instructions</p>
                    <a href="#" class="inline-flex items-center text-red-400 group-hover:text-red-300">
                        Learn more <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>

                <!-- Component 2 -->
                <div class="component-card bg-gradient-to-br from-gray-800/50 to-gray-900/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-gray-700/30 transition-all duration-300 group glow-hover">
                    <div class="relative w-24 h-24 mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-blue-500/20 rounded-full blur-xl group-hover:bg-blue-500/30 transition-colors"></div>
                        <div class="relative flex items-center justify-center h-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-blue-500">
                                <line x1="20" y1="20" x2="20" y2="16"></line>
                                <line x1="4" y1="20" x2="4" y2="16"></line>
                                <line x1="20" y1="8" x2="20" y2="4"></line>
                                <line x1="4" y1="8" x2="4" y2="4"></line>
                                <rect x="4" y="4" width="16" height="12" rx="2"></rect>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-white group-hover:text-blue-400 transition-colors">RAM</h3>
                    <p class="text-gray-400 mb-4">Random Access Memory - Temporary storage that allows your PC to run multiple programs</p>
                    <a href="#" class="inline-flex items-center text-blue-400 group-hover:text-blue-300">
                        Learn more <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>

                <!-- Component 3 -->
                <div class="component-card bg-gradient-to-br from-gray-800/50 to-gray-900/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-gray-700/30 transition-all duration-300 group glow-hover">
                    <div class="relative w-24 h-24 mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-green-500/20 rounded-full blur-xl group-hover:bg-green-500/30 transition-colors"></div>
                        <div class="relative flex items-center justify-center h-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-green-500">
                                <rect x="2" y="4" width="20" height="14" rx="2" ry="2"></rect>
                                <line x1="22" y1="18" x2="2" y2="18"></line>
                                <line x1="6" y1="22" x2="18" y2="22"></line>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-white group-hover:text-green-400 transition-colors">GPU</h3>
                    <p class="text-gray-400 mb-4">Graphics Processing Unit - Powers graphics rendering for games and creative work</p>
                    <a href="#" class="inline-flex items-center text-green-400 group-hover:text-green-300">
                        Learn more <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>

                <!-- Component 4 -->
                <div class="component-card bg-gradient-to-br from-gray-800/50 to-gray-900/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-gray-700/30 transition-all duration-300 group glow-hover">
                    <div class="relative w-24 h-24 mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-purple-500/20 rounded-full blur-xl group-hover:bg-purple-500/30 transition-colors"></div>
                        <div class="relative flex items-center justify-center h-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-purple-500">
                                <rect x="3" y="4" width="18" height="12" rx="2" ry="2"></rect>
                                <line x1="2" y1="20" x2="22" y2="20"></line>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-white group-hover:text-purple-400 transition-colors">MOTHERBOARD</h3>
                    <p class="text-gray-400 mb-4">Main Circuit Board - The foundation that connects all your components together</p>
                    <a href="#" class="inline-flex items-center text-purple-400 group-hover:text-purple-300">
                        Learn more <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>

                <!-- Component 5 -->
                <div class="component-card bg-gradient-to-br from-gray-800/50 to-gray-900/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-gray-700/30 transition-all duration-300 group glow-hover">
                    <div class="relative w-24 h-24 mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-yellow-500/20 rounded-full blur-xl group-hover:bg-yellow-500/30 transition-colors"></div>
                        <div class="relative flex items-center justify-center h-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-yellow-500">
                                <path d="M22 16H2a2 2 0 0 1 2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 2 2z"></path>
                                <line x1="5" y1="20" x2="19" y2="20"></line>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-white group-hover:text-yellow-400 transition-colors">STORAGE</h3>
                    <p class="text-gray-400 mb-4">Data Storage Device - SSDs and HDDs that store your operating system and files</p>
                    <a href="#" class="inline-flex items-center text-yellow-400 group-hover:text-yellow-300">
                        Learn more <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>

                <!-- Component 6 -->
                <div class="component-card bg-gradient-to-br from-gray-800/50 to-gray-900/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-gray-700/30 transition-all duration-300 group glow-hover">
                    <div class="relative w-24 h-24 mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute inset-0 bg-orange-500/20 rounded-full blur-xl group-hover:bg-orange-500/30 transition-colors"></div>
                        <div class="relative flex items-center justify-center h-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-orange-500">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                <line x1="22" y1="11" x2="2" y2="11"></line>
                                <line x1="7" y1="4" x2="7" y2="8"></line>
                                <line x1="17" y1="4" x2="17" y2="8"></line>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-white group-hover:text-orange-400 transition-colors">POWER SUPPLY</h3>
                    <p class="text-gray-400 mb-4">Electrical Power Unit - Provides stable power to all your PC components</p>
                    <a href="#" class="inline-flex items-center text-orange-400 group-hover:text-orange-300">
                        Learn more <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>
            </div>
            
            <div class="mt-16 text-center">
                <a href="#" class="inline-block py-4 px-8 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-lg font-semibold hover:from-red-700 hover:to-pink-700 transition-colors shadow-lg">
                    Start Building Your PC
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Builds Section -->
    <section class="py-24 relative">
        <div class="absolute inset-0 bg-gradient-to-t from-black via-gray-900 to-black"></div>
        
        <div class="container mx-auto relative z-10">
            <div class="text-center mb-16">
                <span class="inline-block px-3 py-1 text-sm font-semibold text-blue-400 bg-blue-900/30 rounded-full mb-4">INSPIRATION</span>
                <h2 class="text-5xl font-bold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">
                    FEATURED BUILDS
                </h2>
                <p class="text-gray-300 max-w-2xl mx-auto">
                    Check out these amazing PC builds created by our community. Get inspired for your next project!
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Build 1 -->
                <div class="relative group overflow-hidden rounded-2xl">
                    <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70 z-10"></div>
                    <img src="/api/placeholder/400/500" alt="Gaming PC Build" class="w-full h-80 object-cover object-center group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute bottom-0 left-0 right-0 p-6 z-20">
                        <h3 class="text-xl font-bold text-white mb-2">Ultimate Gaming Rig</h3>
                        <p class="text-gray-300 text-sm mb-4">RTX 4080 | Ryzen 9 7950X | 64GB RAM</p>
                        <a href="#" class="text-blue-400 text-sm flex items-center">
                            View Build <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Build 2 -->
                <div class="relative group overflow-hidden rounded-2xl">
                    <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70 z-10"></div>
                    <img src="/api/placeholder/400/500" alt="Workstation PC Build" class="w-full h-80 object-cover object-center group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute bottom-0 left-0 right-0 p-6 z-20">
                        <h3 class="text-xl font-bold text-white mb-2">Content Creator Workstation</h3>
                        <p class="text-gray-300 text-sm mb-4">RTX 4090 | i9-14900K | 128GB RAM</p>
                        <a href="#" class="text-pink-400 text-sm flex items-center">
                            View Build <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Build 3 -->
                <div class="relative group overflow-hidden rounded-2xl">
                    <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70 z-10"></div>
                    <img src="/api/placeholder/400/500" alt="Budget PC Build" class="w-full h-80 object-cover object-center group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute bottom-0 left-0 right-0 p-6 z-20">
                        <h3 class="text-xl font-bold text-white mb-2">Budget Gaming PC</h3>
                        <p class="text-gray-300 text-sm mb-4">RTX 4060 | Ryzen 5 7600X | 32GB RAM</p>
                        <a href="#" class="text-green-400 text-sm flex items-center">
                            View Build <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Call to Action -->
    <section class="py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-900/40 to-purple-900/40"></div>
        <div class="absolute inset-0" style="background-image: 
            radial-gradient(circle at 20% 50%, rgba(111,71,255,0.1) 0%, transparent 40%),
            radial-gradient(circle at 80% 50%, rgba(255,92,187,0.1) 0%, transparent 40%);
        "></div>
        
        <div class="container mx-auto relative z-10 px-4">
            <div class="max-w-4xl mx-auto bg-gradient-to-br from-gray-800/70 to-gray-900/70 backdrop-blur-xl p-12 rounded-3xl border border-gray-700/50 shadow-2xl">
                <div class="text-center">
                    <h2 class="text-4xl font-bold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-blue-500">
                        READY TO BUILD YOUR DREAM PC?
                    </h2>
                    <p class="text-gray-300 mb-8 text-lg">
                        Start your journey today and create a custom PC that perfectly matches your needs and budget.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="#" class="bg-gradient-to-r from-pink-600 to-purple-600 hover:from-pink-700 hover:to-purple-700 text-white py-3 px-8 rounded-lg font-semibold transition-all shadow-lg hover:shadow-pink-500/30">
                            Start Building
                        </a>
                        <a href="#" class="bg-transparent border border-gray-600 hover:border-white text-white py-3 px-8 rounded-lg font-semibold transition-all">
                            View Guides
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black py-12 border-t border-gray-800">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-microchip text-red-500 text-xl"></i>
                        <span class="text-lg font-bold text-white">NextGen Computing</span>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Your trusted partner for custom PC building. Providing high-quality components and expert guidance since 2015.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>