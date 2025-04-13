<!-- All Users -->
<h3 class="text-2xl font-semibold mb-4 mt-6">All Registered Users</h3>
<div class="bg-gray-800 rounded-lg shadow-md p-6 mb-6">
    @if ($allUsers->isEmpty())
        <p class="text-gray-400">No users found.</p>
    @else
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-gray-700">
                    <th class="py-3 px-4">Name</th>
                    <th class="py-3 px-4">Email</th>
                    <th class="py-3 px-4">Role</th>
                    <th class="py-3 px-4">Registered At</th>
                    <th class="py-3 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allUsers as $user)
                    <tr class="border-b border-gray-700">
                        <td class="py-3 px-4">{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td class="py-3 px-4">{{ $user->email }}</td>
                        <td class="py-3 px-4">{{ ucfirst($user->role) }}</td>
                        <td class="py-3 px-4">{{ $user->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="py-3 px-4 flex space-x-2">
                            @if ($user->role == 'seller')
                                <button onclick="openEditSellerModal({{ $user->id }}, '{{ $user->first_name }}', '{{ $user->last_name }}', '{{ $user->email }}')" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded-md">Edit</button>
                                <form action="{{ route('admin.delete_seller', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this seller?');">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-md">Delete</button>
                                </form>
                            @elseif ($user->role == 'customer')
                                <button onclick="openEditCustomerModal({{ $user->id }}, '{{ $user->first_name }}', '{{ $user->last_name }}', '{{ $user->email }}')" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded-md">Edit</button>
                                <form action="{{ route('admin.delete_customer', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-md">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<!-- Users Who Clicked "Continue with Build" -->
<h3 class="text-2xl font-semibold mb-4 mt-6">Users Who Continued with Build in Quotation Generator</h3>
<div class="mb-4 flex space-x-4">
    <form action="{{ route('admin.export_quotation_actions') }}" method="GET" class="flex items-end">
        <input type="hidden" name="start_date" value="{{ $startDate ?? '' }}">
        <input type="hidden" name="end_date" value="{{ $endDate ?? '' }}">
        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md">Export to CSV</button>
    </form>
</div>
<div class="bg-gray-800 rounded-lg shadow-md p-6 mb-6">
    @if ($quotationActions->isEmpty())
        <p class="text-gray-400">No users have continued with a build yet.</p>
    @else
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-gray-700">
                    <th class="py-3 px-4">User Name</th>
                    <th class="py-3 px-4">Email</th>
                    <th class="py-3 px-4">Build Name</th>
                    <th class="py-3 px-4">Total Price</th>
                    <th class="py-3 px-4">Components</th>
                    <th class="py-3 px-4">Action Taken At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quotationActions as $action)
                    <tr class="border-b border-gray-700">
                        <td class="py-3 px-4">{{ $action->user->first_name }} {{ $action->user->last_name }}</td>
                        <td class="py-3 px-4">{{ $action->user->email }}</td>
                        <td class="py-3 px-4">{{ $action->build_details['name'] ?? 'N/A' }}</td>
                        <td class="py-3 px-4">LKR {{ number_format($action->build_details['total_price'] ?? 0, 2) }}</td>
                        <td class="py-3 px-4">
                            <ul class="list-disc pl-5">
                                <li>CPU: {{ $action->build_details['components']['cpu'] ?? 'N/A' }}</li>
                                <li>Motherboard: {{ $action->build_details['components']['motherboard'] ?? 'N/A' }}</li>
                                <li>GPU: {{ $action->build_details['components']['gpu'] ?? 'N/A' }}</li>
                                <li>RAM: {{ implode(', ', $action->build_details['components']['rams'] ?? []) }}</li>
                                <li>Storage: {{ implode(', ', $action->build_details['components']['storages'] ?? []) }}</li>
                                <li>Power Supply: {{ $action->build_details['components']['power_supply'] ?? 'N/A' }}</li>
                            </ul>
                        </td>
                        <td class="py-3 px-4">{{ $action->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>