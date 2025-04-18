<!-- resources/views/quotation/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextGen Computing - PC Build Quotation</title>
    @if(!isset($is_pdf))
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <style>
        @font-face {
            font-family: 'PixelFont';
            src: url('/fonts/pixel-font.woff2') format('woff2');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 100%;
            width: 100%;
            padding: 20px;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'PixelFont', Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        @if(!isset($is_pdf))
            body {
                background-color: #f3f4f6;
                color: #000;
            }

            .no-print {
                display: block;
            }

            .build-details {
                display: none;
            }

            .build-details.active {
                display: block;
            }
        @endif

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background-color: white;
                color: black;
            }

            .container {
                padding: 10px;
            }

            .build-details {
                display: block !important;
            }

            h1, h2, h3, h4, h5 {
                color: #000;
            }

            table {
                page-break-inside: avoid;
            }

            .bg-black {
                background-color: transparent !important;
                border: 1px solid #000;
            }

            .bg-gray-800, .bg-gray-700 {
                background-color: #f5f5f5 !important;
            }

            .text-white {
                color: #000 !important;
            }

            .text-green-400 {
                color: #28a745 !important;
            }

            .text-red-400 {
                color: #dc3545 !important;
            }

            .bg-red-900 {
                background-color: #f8d7da !important;
                border-left: 4px solid #dc3545;
            }

            .bg-yellow-700 {
                background-color: #fff3cd !important;
                border-left: 4px solid #ffc107;
            }
        }
    </style>
</head>
<body class="bg-gray-100 text-black">
@include('include.header')

    <div class="container mx-auto px-4 py-8">
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(!isset($is_pdf))
            <h1 class="text-3xl font-bold text-center mb-8">BEGINNER - PC BUILDING</h1>

            <div class="max-w-2xl mx-auto bg-gray-50 rounded-3xl p-8 shadow-lg mb-16 no-print">
                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="quotationForm" action="{{ route('quotation.generate') }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label for="use_case" class="block font-bold mb-2">FIELD THE PC IS USED FOR</label>
                        <select id="use_case" name="use_case" required
                            class="w-full p-3 border border-gray-300 rounded bg-white text-black">
                            <option value="video_editing" {{ old('use_case', isset($use_case) ? $use_case : '') == 'video_editing' ? 'selected' : '' }}>Video Editing</option>
                            <option value="gaming" {{ old('use_case', isset($use_case) ? $use_case : '') == 'gaming' ? 'selected' : '' }}>Gaming</option>
                            <option value="general_use" {{ old('use_case', isset($use_case) ? $use_case : '') == 'general_use' ? 'selected' : '' }}>General Use</option>
                        </select>
                    </div>

                    <div class="mb-8">
                        <label for="budget" class="block font-bold mb-2">TOTAL BUDGET FOR THE PC (MINIMUM BUDGET OF 200,000)</label>
                        <input type="number" id="budget" name="budget" min="200000" required
                            class="w-full p-3 border border-gray-300 rounded bg-white text-black"
                            value="{{ old('budget', isset($budget) ? $budget : '250000') }}">
                    </div>

                    <div class="text-center">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-12 rounded-full">
                            SUBMIT FORM
                        </button>
                    </div>
                </form>
            </div>
        @endif

        @if(isset($builds))
            <h2 class="text-3xl font-bold text-center mb-8 font-['PixelFont']">CREATED QUOTATIONS</h2>

            @foreach ($builds as $spec => $build)
                <div class="bg-black text-white rounded-3xl p-8 mb-6">
                    <h3 class="text-2xl font-bold text-center mb-6">{{ strtoupper($spec) }} SPEC BUILD</h3>
                    @if(!isset($is_pdf))
                        <div class="flex justify-center gap-4 no-print">
                            <button onclick="toggleBuildDetails('{{ $spec }}')" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-full">
                                Open Quotation
                            </button>
                            <a href="{{ route('quotation.download', ['spec' => $spec]) }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-full">
                                Download Quotation
                            </a>
                            @if (auth()->check())
                                <form action="{{ route('quotation.send_email', ['spec' => $spec]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-full">
                                        Continue with the Build
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}?redirect={{ urlencode(route('quotation.index')) }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-full">
                                    Continue with the Build (Login Required)
                                </a>
                            @endif
                        </div>
                    @endif

                    <div id="details-{{ $spec }}" class="mt-6 build-details {{ isset($is_pdf) ? 'active' : 'hidden' }}">
                        <div class="bg-gray-800 p-4 rounded-lg mb-4">
                            <h4 class="text-lg font-bold mb-3">Components</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-gray-700 border border-gray-600">
                                    <thead>
                                        <tr>
                                            <th class="py-2 px-4 border border-gray-600 text-left">Component</th>
                                            <th class="py-2 px-4 border border-gray-600 text-left">Name</th>
                                            <th class="py-2 px-4 border border-gray-600 text-left">Price (LKR)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="py-2 px-4 border border-gray-600">CPU</td>
                                            <td class="py-2 px-4 border border-gray-600">{{ $build['cpu']->name }}</td>
                                            <td class="py-2 px-4 border border-gray-600">{{ $build['cpu']->price ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border border-gray-600">Motherboard</td>
                                            <td class="py-2 px-4 border border-gray-600">{{ $build['motherboard']->name }}</td>
                                            <td class="py-2 px-4 border border-gray-600">{{ $build['motherboard']->price ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border border-gray-600">GPU</td>
                                            <td class="py-2 px-4 border border-gray-600">{{ $build['gpu']->name }}</td>
                                            <td class="py-2 px-4 border border-gray-600">{{ $build['gpu']->price ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border border-gray-600">RAM</td>
                                            <td class="py-2 px-4 border border-gray-600">{{ $build['ram']->name }}</td>
                                            <td class="py-2 px-4 border border-gray-600">{{ $build['ram']->price ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border border-gray-600">Storage</td>
                                            <td class="py-2 px-4 border border-gray-600">{{ $build['storage']->name }}</td>
                                            <td class="py-2 px-4 border border-gray-600">{{ $build['storage']->price ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border border-gray-600">Power Supply</td>
                                            <td class="py-2 px-4 border border-gray-600">{{ $build['power_supply']->name }}</td>
                                            <td class="py-2 px-4 border border-gray-600">{{ $build['power_supply']->price ?? 'N/A' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="bg-gray-800 p-4 rounded-lg mb-4">
                            <h4 class="text-lg font-bold mb-3">Summary</h4>
                            <p><strong>Total Price:</strong> {{ $build['total_price'] }} LKR</p>
                            <p><strong>Remaining Budget:</strong> {{ $build['remaining_budget'] }} LKR</p>
                            <p><strong>Budget Used:</strong> {{ $build['budget_used_percentage'] }}%</p>
                        </div>

                        <div class="bg-gray-800 p-4 rounded-lg mb-4">
                            <h4 class="text-lg font-bold mb-3">Price Breakdown</h4>
                            <ul class="list-disc pl-5">
                                @foreach ($build['price_breakdown'] as $component => $percentage)
                                    <li>{{ ucfirst($component) }}: {{ round($percentage, 2) }}%</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="bg-gray-800 p-4 rounded-lg">
                            <h4 class="text-lg font-bold mb-3">Compatibility Check</h4>
                            @if ($build['compatibility']['is_compatible'])
                                <p class="text-green-400 font-bold">Build is compatible!</p>
                            @else
                                <p class="text-red-400 font-bold">Build has compatibility issues!</p>
                            @endif

                            @if (!empty($build['compatibility']['errors']))
                                <div class="bg-red-900 border-l-4 border-red-500 text-white p-4 mt-3">
                                    <h5 class="font-bold">Compatibility Errors</h5>
                                    <ul class="list-disc pl-5">
                                        @foreach ($build['compatibility']['errors'] as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (!empty($build['compatibility']['warnings']))
                                <div class="bg-yellow-700 border-l-4 border-yellow-500 text-white p-4 mt-3">
                                    <h5 class="font-bold">Compatibility Warnings</h5>
                                    <ul class="list-disc pl-5">
                                        @foreach ($build['compatibility']['warnings'] as $warning)
                                            <li>{{ $warning }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            @if (!empty($build_errors))
                <div class="mt-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4">
                    <h4 class="font-bold">Build Errors</h4>
                    <ul class="list-disc pl-5">
                        @foreach ($build_errors as $spec => $error)
                            <li><strong>{{ ucfirst($spec) }} Spec:</strong> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endif
    </div>

    @if(!isset($is_pdf))
        <script>
            function toggleBuildDetails(spec) {
                const detailsElement = document.getElementById('details-' + spec);
                if (detailsElement.classList.contains('hidden')) {
                    detailsElement.classList.remove('hidden');
                    detailsElement.classList.add('active');
                } else {
                    detailsElement.classList.add('hidden');
                    detailsElement.classList.remove('active');
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const buildsSection = document.getElementById('builds-section');
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('generated') && buildsSection) {
                    buildsSection.scrollIntoView({ behavior: 'smooth' });
                }
            });
        </script>
    @endif
</body>
</html>