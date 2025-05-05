
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

        /* Style for expandable cards in Featured Builds */
        .build-card {
            transition: all 0.3s ease-in-out;
        }

        .build-details {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }

        .build-card:hover .build-details {
            max-height: 200px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 50;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: linear-gradient(to bottom right, #1f2937, #111827);
            padding: 2rem;
            border-radius: 1rem;
            max-width: 500px;
            width: 90%;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            cursor: pointer;
            color: #fff;
            font-size: 1.5rem;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Particle Animation
            const particles = document.querySelector('.particles');
            for (let i = 0; i < 100; i++) {
                let particle = document.createElement('div');
                particle.classList.add('particle');
                particle.style.left = Math.random() * 100 + 'vw';
                particle.style.top = Math.random() * 100 + 'vh';
                let size = Math.random() * 5 + 1;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.opacity = Math.random() * 0.5 + 0.1;
                let animationDuration = Math.random() * 20 + 10;
                particle.style.animation = `pulse ${animationDuration}s infinite alternate`;
                particles.appendChild(particle);
            }

            // Modal Functionality
            const modalTriggers = document.querySelectorAll('[data-modal-target]');
            const modals = document.querySelectorAll('.modal');
            const modalCloses = document.querySelectorAll('.modal-close');

            modalTriggers.forEach(trigger => {
                trigger.addEventListener('click', () => {
                    const modalId = trigger.getAttribute('data-modal-target');
                    const modal = document.getElementById(modalId);
                    modal.style.display = 'flex';
                });
            });

            modalCloses.forEach(close => {
                close.addEventListener('click', () => {
                    const modal = close.closest('.modal');
                    modal.style.display = 'none';
                });
            });

            // Close modal when clicking outside the modal content
            modals.forEach(modal => {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.style.display = 'none';
                    }
                });
            });
        });
    </script>
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white font-sans">
    @include('include.header')

    <div class="relative overflow-hidden pt-24">
        <div class="particles"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-black/80 to-black/40 z-10"></div>
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

                            <img src="images/03.png" alt="images/pc-right-image.png" class="w-64 transform rotate-12">
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
                    <a href="{{ route('quotation.index') }}" class="relative group overflow-hidden bg-gradient-to-r from-red-600 to-pink-600 text-white py-4 px-8 rounded-lg text-xl font-semibold transition transform hover:scale-105 shadow-lg inline-block">
                        <span class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-red-600 to-pink-600 opacity-75 group-hover:opacity-0 transition-opacity duration-300 z-0"></span>
                        <span class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-red-700 to-pink-700 opacity-0 group-hover:opacity-75 transition-opacity duration-300 z-0"></span>
                        <span class="relative z-10 flex items-center">
                            <i class="fas fa-cogs mr-2"></i>
                            BUILD AS A BEGINNER
                        </span>
                    </a>
                    <a href="{{ route('build.index') }}" class="relative group overflow-hidden bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 px-8 rounded-lg text-xl font-semibold transition transform hover:scale-105 shadow-lg inline-block">
                        <span class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-blue-600 to-indigo-600 opacity-75 group-hover:opacity-0 transition-opacity duration-300 z-0"></span>
                        <span class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-blue-700 to-indigo-700 opacity-0 group-hover:opacity-75 transition-opacity duration-300 z-0"></span>
                        <span class="relative z-10 flex items-center">
                            <i class="fas fa-laptop-code mr-2"></i>
                            BUILD AS AN EXPERT
                        </span>
                    </a>
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
                <!-- Component 1: CPU -->
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
                    <button data-modal-target="modal-cpu" class="inline-flex items-center text-red-400 group-hover:text-red-300 cursor-pointer">
                        Learn more <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </button>
                </div>

                <!-- Component 2: RAM -->
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
                    <button data-modal-target="modal-ram" class="inline-flex items-center text-blue-400 group-hover:text-blue-300 cursor-pointer">
                        Learn more <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </button>
                </div>

                <!-- Component 3: GPU -->
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
                    <button data-modal-target="modal-gpu" class="inline-flex items-center text-green-400 group-hover:text-green-300 cursor-pointer">
                        Learn more <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </button>
                </div>

                <!-- Component 4: Motherboard -->
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
                    <button data-modal-target="modal-motherboard" class="inline-flex items-center text-purple-400 group-hover:text-purple-300 cursor-pointer">
                        Learn more <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </button>
                </div>

                <!-- Component 5: Storage -->
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
                    <button data-modal-target="modal-storage" class="inline-flex items-center text-yellow-400 group-hover:text-yellow-300 cursor-pointer">
                        Learn more <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </button>
                </div>

                <!-- Component 6: Power Supply -->
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
                    <button data-modal-target="modal-power-supply" class="inline-flex items-center text-orange-400 group-hover:text-orange-300 cursor-pointer">
                        Learn more <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </button>
                </div>
            </div>
            
            
        </div>
    </section>

    <!-- Modals for Components -->
    <!-- CPU Modal -->
    <div id="modal-cpu" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h3 class="text-2xl font-bold mb-4 text-red-400">CPU Use Case</h3>
            <p class="text-gray-300">
                The CPU is essential for all computing tasks. It handles everything from running your operating system to executing complex calculations in games, video editing software, and data analysis tools. A powerful CPU is crucial for gaming (e.g., high frame rates in titles like Cyberpunk 2077), multitasking (e.g., streaming while gaming), and professional workloads (e.g., 3D rendering in Blender).
            </p>
        </div>
    </div>

    <!-- RAM Modal -->
    <div id="modal-ram" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h3 class="text-2xl font-bold mb-4 text-blue-400">RAM Use Case</h3>
            <p class="text-gray-300">
                RAM is vital for multitasking and running applications smoothly. It temporarily stores data that your CPU needs quick access to, such as open browser tabs, game assets, or video editing timelines. For example, 16GB of RAM is ideal for gaming and general use, while 32GB or more benefits creators working with large Photoshop files or 4K video editing in Adobe Premiere.
            </p>
        </div>
    </div>

    <!-- GPU Modal -->
    <div id="modal-gpu" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h3 class="text-2xl font-bold mb-4 text-green-400">GPU Use Case</h3>
            <p class="text-gray-300">
                The GPU is critical for rendering visuals, making it essential for gaming, graphic design, and video editing. It powers high-quality graphics in games (e.g., ray tracing in AAA titles), accelerates rendering in 3D modeling software like Maya, and supports AI workloads like machine learning with frameworks such as TensorFlow. A good GPU can also enhance streaming quality on platforms like OBS.
            </p>
        </div>
    </div>

    <!-- Motherboard Modal -->
    <div id="modal-motherboard" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h3 class="text-2xl font-bold mb-4 text-purple-400">Motherboard Use Case</h3>
            <p class="text-gray-300">
                The motherboard connects all PC components, enabling communication between the CPU, RAM, GPU, and storage. It determines compatibility (e.g., socket type for your CPU), expandability (e.g., number of RAM slots or PCIe lanes for GPUs), and features like overclocking support or Wi-Fi connectivity. For example, a high-end motherboard is necessary for a gaming PC with multiple GPUs or NVMe SSDs.
            </p>
        </div>
    </div>

    <!-- Storage Modal -->
    <div id="modal-storage" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h3 class="text-2xl font-bold mb-4 text-yellow-400">Storage Use Case</h3>
            <p class="text-gray-300">
                Storage devices like SSDs and HDDs hold your operating system, applications, and files. SSDs offer fast boot times and quick game loading (e.g., a 1TB NVMe SSD for Windows and games), while HDDs provide cost-effective high-capacity storage for large media libraries (e.g., a 4TB HDD for video archives). For creators, a dual setup with an SSD for editing software and an HDD for raw footage is ideal.
            </p>
        </div>
    </div>

    <!-- Power Supply Modal -->
    <div id="modal-power-supply" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h3 class="text-2xl font-bold mb-4 text-orange-400">Power Supply Use Case</h3>
            <p class="text-gray-300">
                The power supply (PSU) delivers stable electricity to all components, ensuring system reliability. A high-wattage PSU (e.g., 850W) is necessary for power-hungry builds like gaming PCs with RTX 4090 GPUs, while a 650W PSU suits budget builds. Efficiency ratings (e.g., 80+ Gold) reduce energy waste, and modular PSUs improve cable management for better airflow in compact cases.
            </p>
        </div>
    </div>

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
                <!-- Build 1: Ultimate Gaming Rig -->
                <div class="build-card relative bg-gradient-to-br from-gray-800/50 to-gray-900/80 backdrop-blur-sm rounded-2xl p-6 text-center border border-gray-700/30 transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/20">
                    <div class="flex justify-center mb-4">
                        <i class="fas fa-gamepad text-5xl text-blue-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Ultimate Gaming Rig</h3>
                    <p class="text-gray-300 text-sm mb-4">RTX 4080 | Ryzen 9 7950X | 64GB RAM</p>
                    <div class="build-details text-gray-400 text-sm">
                        <p>Built for 4K gaming with ray tracing. Includes liquid cooling, RGB lighting, and a custom case design for optimal airflow.</p>
                        <ul class="list-disc list-inside mt-2">
                            <li>Storage: 2TB NVMe SSD</li>
                            <li>PSU: 850W Gold-rated</li>
                            <li>Case: Lian Li PC-O11 Dynamic</li>
                        </ul>
                    </div>
                    <a href="#" class="text-blue-400 text-sm flex items-center justify-center mt-4">
                        View Build <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                
                <!-- Build 2: Content Creator Workstation -->
                <div class="build-card relative bg-gradient-to-br from-gray-800/50 to-gray-900/80 backdrop-blur-sm rounded-2xl p-6 text-center border border-gray-700/30 transition-all duration-300 hover:shadow-lg hover:shadow-pink-500/20">
                    <div class="flex justify-center mb-4">
                        <i class="fas fa-video text-5xl text-pink-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Content Creator Workstation</h3>
                    <p class="text-gray-300 text-sm mb-4">RTX 4090 | i9-14900K | 128GB RAM</p>
                    <div class="build-details text-gray-400 text-sm">
                        <p>Designed for 8K video editing and 3D rendering. Features dual GPUs and a high-speed NVMe storage array.</p>
                        <ul class="list-disc list-inside mt-2">
                            <li>Storage: 4TB NVMe SSD + 8TB HDD</li>
                            <li>PSU: 1000W Platinum-rated</li>
                            <li>Cooling: Custom water loop</li>
                        </ul>
                    </div>
                    <a href="#" class="text-pink-400 text-sm flex items-center justify-center mt-4">
                        View Build <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                
                <!-- Build 3: Budget Gaming PC -->
                <div class="build-card relative bg-gradient-to-br from-gray-800/50 to-gray-900/80 backdrop-blur-sm rounded-2xl p-6 text-center border border-gray-700/30 transition-all duration-300 hover:shadow-lg hover:shadow-green-500/20">
                    <div class="flex justify-center mb-4">
                        <i class="fas fa-wallet text-5xl text-green-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Budget Gaming PC</h3>
                    <p class="text-gray-300 text-sm mb-4">RTX 4060 | Ryzen 5 7600X | 32GB RAM</p>
                    <div class="build-details text-gray-400 text-sm">
                        <p>Affordable build for 1080p gaming. Perfect for esports titles and casual gaming.</p>
                        <ul class="list-disc list-inside mt-2">
                            <li>Storage: 1TB NVMe SSD</li>
                            <li>PSU: 650W Bronze-rated</li>
                            <li>Case: NZXT H510</li>
                        </ul>
                    </div>
                    <a href="#" class="text-green-400 text-sm flex items-center justify-center mt-4">
                        View Build <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    @include('include.footer')
</body>
</html>