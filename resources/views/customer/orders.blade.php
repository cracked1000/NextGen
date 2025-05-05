//customer.orders.blade.php
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
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Inter:wght@300;400;500;600&display=swap');

        :root {
            --primary: #e53e3e;
            --primary-glow: rgba(229, 62, 62, 0.4);
            --background: #0d1117;
            --card-bg: #1a1f25;
            --sidebar-bg: #131820;
            --text: #e2e8f0;
            --text-muted: #a0aec0;
            --border: #2d3748;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top right, #151b24, var(--background) 70%);
            color: var(--text);
            min-height: 100vh;
        }

        .header {
            background: rgba(22, 27, 34, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--primary);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .logo-text {
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            letter-spacing: 2px;
            background: linear-gradient(90deg, var(--primary), #f56565);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .page-title {
            font-family: 'Orbitron', sans-serif;
            color: #ffffff;
            letter-spacing: 2px;
            text-shadow: 0 0 10px var(--primary-glow);
        }

        .sidebar {
            background: var(--sidebar-bg);
            border-right: 1px solid rgba(45, 55, 72, 0.5);
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.2);
        }

        .nav-link {
            color: var(--text-muted);
            transition: all 0.3s ease;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-link:hover {
            background: rgba(229, 62, 62, 0.1);
            color: var(--primary);
            transform: translateX(5px);
        }

        .nav-link.active {
            background: rgba(229, 62, 62, 0.15);
            color: var(--primary);
            font-weight: 500;
            border-left: 3px solid var(--primary);
        }

        .card {
            background: var(--card-bg);
            border: 1px solid rgba(45, 55, 72, 0.3);
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .card-header {
            border-bottom: 1px solid rgba(45, 55, 72, 0.5);
            padding: 1.5rem;
            background: rgba(26, 32, 44, 0.5);
        }

        .card-title {
            font-family: 'Orbitron', sans-serif;
            color: var(--primary);
            letter-spacing: 1px;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .table {
            margin: 0;
        }

        .table th {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(45, 55, 72, 0.5);
        }

        .table td {
            color: var(--text);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(45, 55, 72, 0.2);
        }

        .table tr:hover td {
            background: rgba(26, 32, 44, 0.5);
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: rgba(236, 201, 75, 0.15);
            color: #ecc94b;
            border: 1px solid rgba(236, 201, 75, 0.3);
        }

        .status-completed {
            background: rgba(72, 187, 120, 0.15);
            color: #48bb78;
            border: 1px solid rgba(72, 187, 120, 0.3);
        }

        .status-cancelled {
            background: rgba(229, 62, 62, 0.15);
            color: #e53e3e;
            border: 1px solid rgba(229, 62, 62, 0.3);
        }

        .status-paid {
            background: rgba(72, 187, 120, 0.15);
            color: #48bb78;
            border: 1px solid rgba(72, 187, 120, 0.3);
        }

        .status-unpaid {
            background: rgba(229, 62, 62, 0.15);
            color: #e53e3e;
            border: 1px solid rgba(229, 62, 62, 0.3);
        }

        .status-verified {
            background: rgba(72, 187, 120, 0.15);
            color: #48bb78;
            border: 1px solid rgba(72, 187, 120, 0.3);
        }

        .status-unverified {
            background: rgba(236, 201, 75, 0.15);
            color: #ecc94b;
            border: 1px solid rgba(236, 201, 75, 0.3);
        }

        .empty-state {
            padding: 3rem;
            text-align: center;
            color: var(--text-muted);
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--primary);
            opacity: 0.5;
        }

        .alert {
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid transparent;
        }

        .alert-success {
            background: rgba(72, 187, 120, 0.1);
            border-color: rgba(72, 187, 120, 0.3);
            color: #48bb78;
        }

        .alert-danger {
            background: rgba(229, 62, 62, 0.1);
            border-color: rgba(229, 62, 62, 0.3);
            color: #e53e3e;
        }

        .user-icon {
            height: 40px;
            width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(229, 62, 62, 0.15);
            color: var(--primary);
            font-size: 1.25rem;
            border: 2px solid rgba(229, 62, 62, 0.3);
            box-shadow: 0 0 15px rgba(229, 62, 62, 0.2);
        }

        .back-button {
            height: 36px;
            width: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: rgba(229, 62, 62, 0.15);
            color: var(--primary);
            border: 1px solid rgba(229, 62, 62, 0.3);
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background: rgba(229, 62, 62, 0.25);
            transform: translateX(-3px);
        }

        @keyframes glow {
            0% { box-shadow: 0 0 10px rgba(229, 62, 62, 0.2); }
            50% { box-shadow: 0 0 20px rgba(229, 62, 62, 0.4); }
            100% { box-shadow: 0 0 10px rgba(229, 62, 62, 0.2); }
        }

        .card {
            animation: glow 4s infinite ease-in-out;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 to-black">
    <header class="header py-4 px-6 sticky top-0 z-10">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <a href="{{ route('customer.profile') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="logo-text">NEXTGEN COMPUTING</div>
            </div>
            <h1 class="page-title text-xl font-bold hidden md:block">MY ORDERS</h1>
            <div class="user-icon">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </header>

    <div class="container mx-auto flex flex-col md:flex-row mt-6 px-4">
        <div class="sidebar w-full md:w-64 p-6 mb-6 md:mb-0 rounded-xl md:rounded-l-xl">
            <h2 class="logo-text mb-6 text-lg">ACCOUNT HUB</h2>
            <nav>
                <a href="{{ route('customer.profile') }}" class="nav-link">
                    <i class="fas fa-user"></i>
                    <span>Profile Settings</span>
                </a>
                <a href="{{ route('customer.orders') }}" class="nav-link active">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Order History</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="fas fa-heart"></i>
                    <span>Wishlist</span>
                </a>
                <a href="#" class="nav-link">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                </a>
                <a href="{{ route('logout') }}" class="nav-link mt-6 text-red-400 hover:text-red-300">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </div>

        <div class="flex-1">
            @if (session('success'))
                <div class="alert alert-success">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history"></i>
                        <span>ORDER HISTORY</span>
                    </h3>
                </div>
                
                <div class="card-body p-0">
                    @if ($orders->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <h4 class="text-xl font-semibold mb-2">No Orders Yet</h4>
                            <p class="text-gray-400 mb-4">You haven't placed any orders yet.</p>
                            <a href="{{ route('secondhand.index') }}" class="inline-block bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition duration-300">
                                Explore Products
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="table w-full">
                                <thead>
                                    <tr>
                                        <th>ORDER ID</th>
                                        <th>PRODUCT</th>
                                        <th>SELLER</th>
                                        <th>TOTAL</th>
                                        <th>STATUS</th>
                                        <th>ACCEPTED</th>
                                        <th>SHIPPED</th>
                                        <th>RECEIVED</th>
                                        <th>VERIFIED</th>
                                        <th>PAYMENT</th>
                                        <th>DATE</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->part ? $order->part->part_name : 'N/A' }}</td>
                                            <td>{{ $order->seller_name }}</td>
                                            <td>{{ number_format($order->total, 2) }} LKR</td>
                                            <td>
                                                <span class="status-badge {{ $order->status == 'Completed' ? 'status-completed' : ($order->status == 'Cancelled' ? 'status-cancelled' : 'status-pending') }}">
                                                    {{ $order->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="status-badge {{ $order->is_accepted ? 'status-completed' : 'status-pending' }}">
                                                    {{ $order->is_accepted ? 'Yes' : 'No' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="status-badge {{ $order->is_shipped ? 'status-completed' : 'status-pending' }}">
                                                    {{ $order->is_shipped ? 'Yes' : 'No' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="status-badge {{ $order->is_received ? 'status-completed' : 'status-pending' }}">
                                                    {{ $order->is_received ? 'Yes' : 'No' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="status-badge {{ $order->is_verified ? 'status-verified' : ($order->verify_product ? 'status-unverified' : 'status-completed') }}">
                                                    {{ $order->verify_product ? ($order->is_verified ? 'Yes' : 'Pending') : 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="status-badge {{ $order->payment_status == 'Paid' ? 'status-paid' : 'status-unpaid' }}">
                                                    {{ $order->payment_status }}
                                                </span>
                                            </td>
                                            <td>{{ $order->order_date ? $order->order_date->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                            @if ($order->is_shipped && !$order->is_received)
                                                <form action="{{ route('customer.orders', $order->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded transition duration-200">
                                                        Mark as Received
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-500">N/A</span>
                                            @endif
                                        </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-12 py-6 text-center text-gray-500">
        <div class="container mx-auto">
            <div class="logo-text mb-4">NEXTGEN COMPUTING</div>
            <p class="text-sm">© 2025 NextGen Computing. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>