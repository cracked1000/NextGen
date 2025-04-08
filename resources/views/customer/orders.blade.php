<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - NextGen Computing</title>
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
        .header {
            background: #161b22;
            border-bottom: 2px solid #e53e3e;
            box-shadow: 0 0 15px rgba(229, 62, 62, 0.3);
        }
        .header h1 {
            font-family: 'Orbitron', sans-serif;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(229, 62, 62, 0.5);
        }
        .sidebar {
            background: #1f252d;
            border-right: 1px solid #2d3748;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }
        .sidebar a {
            color: #a0aec0;
            transition: color 0.3s ease;
        }
        .sidebar a:hover {
            color: #e53e3e;
        }
        .sidebar .active {
            color: #e53e3e;
            font-weight: 500;
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
        .table {
            background: #2d3748;
            color: #e2e8f0;
        }
        .table th {
            font-family: 'Orbitron', sans-serif;
            color: #e53e3e;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .table td {
            color: #e2e8f0;
        }
        .text-primary {
            color: #e53e3e;
        }
    </style>
</head>
<body>
    <header class="header p-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <a href="{{ route('customer.profile') }}" class="text-white hover:text-gray-300">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="text-sm text-gray-400">NEXTGEN COMPUTING</div>
        </div>
        <h1 class="text-2xl font-bold">My Orders</h1>
        <div class="text-2xl">
            <i class="fas fa-user-circle text-primary"></i>
        </div>
    </header>

    <div class="min-h-screen flex">
        <div class="sidebar w-64 p-6">
            <h2 class="text-xl font-bold text-primary mb-6">ACCOUNT HUB</h2>
            <ul class="space-y-4">
                <li>
                    <a href="{{ route('customer.profile') }}" class="flex items-center space-x-2">
                        <i class="fas fa-user"></i>
                        <span>Edit Profile</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.orders') }}" class="flex items-center space-x-2 active">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Orders</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="flex-1 p-6">
            <div class="card p-8">
                <h3 class="text-xl font-semibold mb-6">ORDER HISTORY</h3>

                @if (session('success'))
                    <div class="bg-green-900/50 text-green-300 rounded-xl p-4 mb-6 text-center border border-green-700/50">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-900/50 text-red-300 rounded-xl p-4 mb-6 text-center border border-red-700/50">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($orders->isEmpty())
                    <p class="text-gray-400 text-center">You have no orders yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Part Name</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Payment Status</th>
                                    <th>Order Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->part ? $order->part->part_name : 'N/A' }}</td>
                                        <td>{{ number_format($order->total, 2) }} LKR</td>
                                        <td>{{ $order->status }}</td>
                                        <td>{{ $order->payment_status }}</td>
                                        <td>{{ $order->order_date ? $order->order_date->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('include.footer')
</body>
</html>