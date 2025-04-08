@extends('layouts')
@section('title', 'Admin Dashboard')
@section('content')
<div class="min-h-screen bg-gray-900 text-white p-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-4xl font-bold">Admin Dashboard</h2>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md">Logout</button>
            </form>
        </div>

        @if (session('success'))
            <div class="bg-green-500 text-white p-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-500 text-white p-3 rounded-lg mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-gray-800 rounded-lg shadow-md p-6">
                <h4 class="text-lg font-semibold mb-2">Total Parts</h4>
                <p class="text-3xl font-bold">{{ $totalParts }}</p>
            </div>
            <div class="bg-gray-800 rounded-lg shadow-md p-6">
                <h4 class="text-lg font-semibold mb-2">Total Sellers</h4>
                <p class="text-3xl font-bold">{{ $totalSellers }}</p>
            </div>
            <div class="bg-gray-800 rounded-lg shadow-md p-6">
                <h4 class="text-lg font-semibold mb-2">Total Customers</h4>
                <p class="text-3xl font-bold">{{ $totalCustomers }}</p>
            </div>
            <div class="bg-gray-800 rounded-lg shadow-md p-6">
                <h4 class="text-lg font-semibold mb-2">Total Sales</h4>
                <p class="text-3xl font-bold">LKR {{ number_format($totalSales, 2) }}</p>
            </div>
        </div>

        <!-- Add Part Button -->
        <h3 class="text-2xl font-semibold mb-4">Parts Management</h3>
        <div class="mb-6">
            <button onclick="openAddPartModal()" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Add New Part</button>
        </div>
        <!-- Add Part Modal -->
        <div id="addPartModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden">
            <div class="w-full max-w-2xl mx-auto bg-gray-800 text-white p-6 rounded-lg shadow-lg max-h-[90vh] overflow-y-auto">
                <h3 class="text-2xl font-semibold mb-4">Add New Part</h3>
                <form action="{{ route('admin.add_part') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="part_name" class="block text-gray-300 font-bold mb-2">Part Name</label>
                        <input type="text" name="part_name" id="part_name" class="w-full p-3 rounded-md border border-gray-600 bg-gray-700 text-white @error('part_name') border-red-500 @enderror" value="{{ old('part_name') }}" required>
                        @error('part_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="seller" class="block text-gray-300 font-bold mb-2">Seller</label>
                        <select name="seller" id="seller" class="w-full p-3 rounded-md border border-gray-600 bg-gray-700 text-white @error('seller') border-red-500 @enderror" required>
                            <option value="">Select a seller</option>
                            @foreach ($sellers as $seller)
                                <option value="{{ $seller->id }}">{{ $seller->first_name }} {{ $seller->last_name }} ({{ $seller->email }})</option>
                            @endforeach
                        </select>
                        @error('seller')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-gray-300 font-bold mb-2">Price (LKR)</label>
                        <input type="number" step="0.01" name="price" id="price" class="w-full p-3 rounded-md border border-gray-600 bg-gray-700 text-white @error('price') border-red-500 @enderror" value="{{ old('price') }}" required>
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-gray-300 font-bold mb-2">Status</label>
                        <select name="status" id="status" class="w-full p-3 rounded-md border border-gray-600 bg-gray-700 text-white @error('status') border-red-500 @enderror" required>
                            <option value="Available">Available</option>
                            <option value="Sold">Sold</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="condition" class="block text-gray-300 font-bold mb-2">Condition</label>
                        <select name="condition" id="condition" class="w-full p-3 rounded-md border border-gray-600 bg-gray-700 text-white @error('condition') border-red-500 @enderror">
                            <option value="New">New</option>
                            <option value="Used">Used</option>
                        </select>
                        @error('condition')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="category" class="block text-gray-300 font-bold mb-2">Category</label>
                        <select name="category" id="category" class="w-full p-3 rounded-md border border-gray-600 bg-gray-700 text-white @error('category') border-red-500 @enderror">
                            <option value="">Select a category (optional)</option>
                            <option value="GPU">GPU</option>
                            <option value="CPU">CPU</option>
                            <option value="Motherboard">Motherboard</option>
                            <option value="RAM">RAM</option>
                            <option value="Storage">Storage</option>
                            <option value="PSU">PSU</option>
                        </select>
                        @error('category')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-gray-300 font-bold mb-2">Description</label>
                        <textarea name="description" id="description" class="w-full p-3 rounded-md border border-gray-600 bg-gray-700 text-white @error('description') border-red-500 @enderror" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="image1" class="block text-gray-300 font-bold mb-2">Image 1</label>
                        <input type="file" name="image1" id="image1" class="w-full p-3 rounded-md border border-gray-600 bg-gray-700 text-white @error('image1') border-red-500 @enderror">
                        @error('image1')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="image2" class="block text-gray-300 font-bold mb-2">Image 2</label>
                        <input type="file" name="image2" id="image2" class="w-full p-3 rounded-md border border-gray-600 bg-gray-700 text-white @error('image2') border-red-500 @enderror">
                        @error('image2')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="image3" class="block text-gray-300 font-bold mb-2">Image 3</label>
                        <input type="file" name="image3" id="image3" class="w-full p-3 rounded-md border border-gray-600 bg-gray-700 text-white @error('image3') border-red-500 @enderror">
                        @error('image3')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex space-x-2 justify-center">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-full text-lg">List Part</button>
                        <button type="button" onclick="closeAddPartModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-full text-lg">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Available Parts -->
        <h3 class="text-2xl font-semibold mb-4">Available Parts</h3>
        @if ($parts->isEmpty())
            <p class="text-gray-400 mb-6">No parts to display.</p>
        @else
            <div class="bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-700">
                            <th class="py-3 px-4">Part Name</th>
                            <!-- <th class="py-3 px-4">Description</th> -->
                            <th class="py-3 px-4">Price</th>
                            <th class="py-3 px-4">Seller</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Condition</th>
                            <th class="py-3 px-4">Category</th>
                            <th class="py-3 px-4">Images</th>
                            <th class="py-3 px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($parts as $part)
                            <tr class="border-b border-gray-700">
                                <td class="py-3 px-4">{{ $part->part_name }}</td>
                                <!-- <td class="py-3 px-4">{{ $part->description ?? 'N/A' }}</td> -->
                                <td class="py-3 px-4">LKR {{ number_format($part->price, 2) }}</td>
                                <td class="py-3 px-4">{{ $part->seller ? $part->seller->first_name . ' ' . $part->seller->last_name : 'N/A' }}</td>
                                <td class="py-3 px-4">{{ $part->status }}</td>
                                <td class="py-3 px-4">{{ $part->condition ?? 'N/A' }}</td>
                                <td class="py-3 px-4">{{ $part->category ?? 'N/A' }}</td>
                                <td class="py-3 px-4">
                                    @if ($part->image1)
                                        <img src="{{ asset('storage/' . $part->image1) }}" alt="Image 1" class="w-16 h-16 object-cover rounded">
                                    @endif
                                </td>
                                <td class="py-3 px-4 flex space-x-2">
                                    <button onclick="openEditPartModal({{ $part->id }}, '{{ addslashes($part->part_name) }}', '{{ addslashes($part->description ?? '') }}', {{ $part->price }}, {{ $part->seller_id ?? 'null' }}, '{{ $part->status }}', '{{ $part->condition ?? 'New' }}', '{{ $part->category ?? '' }}')" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded-md">Edit</button>
                                    <form action="{{ route('admin.delete_part', $part->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this part?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-md">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <h3 class="text-2xl font-semibold mb-4">Pending Parts for Approval</h3>
        @if ($pendingParts->isEmpty())
            <p class="text-gray-400 mb-6">No pending parts to review.</p>
        @else
            <div class="bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-700">
                            <th class="py-3 px-4">Part Name</th>
                            <th class="py-3 px-4">Price</th>
                            <th class="py-3 px-4">Seller</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Condition</th>
                            <th class="py-3 px-4">Category</th>
                            <th class="py-3 px-4">Images</th>
                            <th class="py-3 px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingParts as $part)
                            <tr class="border-b border-gray-700">
                                <td class="py-3 px-4">{{ $part->part_name }}</td>
                                <td class="py-3 px-4">LKR {{ number_format($part->price, 2) }}</td>
                                <td class="py-3 px-4">{{ $part->seller ? $part->seller->first_name . ' ' . $part->seller->last_name : 'N/A' }}</td>
                                <td class="py-3 px-4">{{ $part->status }}</td>
                                <td class="py-3 px-4">{{ $part->condition ?? 'N/A' }}</td>
                                <td class="py-3 px-4">{{ $part->category ?? 'N/A' }}</td>
                                <td class="py-3 px-4 flex space-x-2">
                                    @if ($part->image1)
                                        <img src="{{ asset('storage/' . $part->image1) }}" alt="Image 1" class="w-16 h-16 object-cover rounded">
                                    @endif
                                    @if ($part->image2)
                                        <img src="{{ asset('storage/' . $part->image2) }}" alt="Image 2" class="w-16 h-16 object-cover rounded">
                                    @endif
                                    @if ($part->image3)
                                        <img src="{{ asset('storage/' . $part->image3) }}" alt="Image 3" class="w-16 h-16 object-cover rounded">
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <form action="{{ route('admin.approve_part', $part->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded-md">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.decline_part', $part->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-md mt-4">Decline</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Edit Part Modal -->
        <div id="editPartModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden">
            <div class="w-full max-w-2xl mx-auto bg-gray-800 text-white p-6 rounded-lg shadow-lg max-h-[90vh] overflow-y-auto">
                <h3 class="text-2xl font-semibold mb-4">Edit Part</h3>
                <form id="editPartForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="edit_part_id">
                    <div class="mb-4">
                        <label for="edit_part_name" class="block text-gray-300 font-bold mb-2">Part Name</label>
                        <input type="text" name="part_name" id="edit_part_name" class="w-full p-3 rounded-md border border-gray-600 bg-gray-700 text-white @error('part_name') border-red-500 @enderror" required>
                        @error('part_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="edit_part_seller" class="block text-gray-300 font-bold mb-2">Seller</label>
                        <select name="seller" id="edit_part_seller" class="w-full p-3 rounded-md border border-gray-600 bg-gray-700 text-white @error('seller') border-red-500 @enderror" required>
                            <option value="">Select a seller</option>
                            @foreach ($sellers as $seller)
                                <option value="{{ $seller->id }}">{{ $seller->first_name }} {{ $seller->last_name }} ({{ $seller->email }})</option>
                            @endforeach
                        </select>
                        @error('seller')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="edit_part_price" class="block text-gray-300 font-bold mb-2">Price (LKR)</label>
                        <input type="number" name="price" id="edit_part_price" step="0.01" class="w-full p-3 rounded-md border border-gray-600 bg-gray-700 text-white @error('price') border-red-500 @enderror" required>
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="edit_part_status" class="block text-gray-300 font-bold mb-2">Status</label>
                        <select name="status" id="edit_part_status" class="w-full p-3 rounded-md border border-gray-600 bg-gray-700 text-white @error('status') border-red-500 @enderror" required>
                            <option value="Available">Available</option>
                            <option value="Sold">Sold</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="edit_part_condition" class="block text-gray-300 font-bold mb-2">Condition</label>
                        <select name="condition" id="edit_part_condition" class="w-full p-3 rounded-md border border-gray-600 bg-gray-700 text-white @error('condition') border-red-500 @enderror">
                            <option value="New">New</option>
                            <option value="Used">Used</option>
                        </select>
                        @error('condition')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="edit_part_category" class="block text-gray-300 font-bold mb-2">Category</label>
                        <select name="category" id="edit_part_category" class="w-full p-3 rounded-md border border-gray-600 bg-gray-700 text-white @error('category') border-red-500 @enderror">
                            <option value="">Select a category (optional)</option>
                            <option value="GPU">GPU</option>
                            <option value="CPU">CPU</option>
                            <option value="Motherboard">Motherboard</option>
                            <option value="RAM">RAM</option>
                            <option value="Storage">Storage</option>
                            <option value="PSU">PSU</option>
                        </select>
                        @error('category')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="edit_part_description" class="block text-gray-300 font-bold mb-2">Description</label>
                        <textarea name="description" id="edit_part_description" class="w-full p-3 rounded-md border border-gray-600 bg-gray-700 text-white @error('description') border-red-500 @enderror" rows="4"></textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="edit_image1" class="block text-gray-300 font-bold mb-2">Image 1</label>
                        <input type="file" name="image1" id="edit_image1" class="w-full p-3 rounded-md border border-gray-600 bg-gray-700 text-white @error('image1') border-red-500 @enderror">
                        @error('image1')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="edit_image2" class="block text-gray-300 font-bold mb-2">Image 2</label>
                        <input type="file" name="image2" id="edit_image2" class="w-full p-3 rounded-md border border-gray-600 bg-gray-700 text-white @error('image2') border-red-500 @enderror">
                        @error('image2')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="edit_image3" class="block text-gray-300 font-bold mb-2">Image 3</label>
                        <input type="file" name="image3" id="edit_image3" class="w-full p-3 rounded-md border border-gray-600 bg-gray-700 text-white @error('image3') border-red-500 @enderror">
                        @error('image3')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex space-x-2 justify-center">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-full text-lg">Save</button>
                        <button type="button" onclick="closeEditPartModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-full text-lg">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sales Report -->
        <h3 class="text-2xl font-semibold mb-4">Sales Report (Sold Parts)</h3>
        <div class="mb-4 flex space-x-4">
            <form action="{{ route('admin.dashboard') }}" method="GET" class="flex space-x-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate ?? '' }}" class="bg-gray-700 text-white rounded-md p-2">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate ?? '' }}" class="bg-gray-700 text-white rounded-md p-2">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Filter</button>
                </div>
            </form>
            <form action="{{ route('admin.export_sales_report') }}" method="GET" class="flex items-end">
                <input type="hidden" name="start_date" value="{{ $startDate ?? '' }}">
                <input type="hidden" name="end_date" value="{{ $endDate ?? '' }}">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md">Export to CSV</button>
            </form>
        </div>
        @if ($parts->isEmpty())
            <p class="text-gray-400 mb-6">No sales to report.</p>
        @else
            <div class="bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-700">
                            <th class="py-3 px-4">Part Name</th>
                            <th class="py-3 px-4">Description</th>
                            <th class="py-3 px-4">Price</th>
                            <th class="py-3 px-4">Seller</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Condition</th>
                            <th class="py-3 px-4">Category</th>
                            <th class="py-3 px-4">Sold At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($parts->where('status', 'Sold') as $part)
                            <tr class="border-b border-gray-700">
                                <td class="py-3 px-4">{{ $part->part_name }}</td>
                                <td class="py-3 px-4">{{ $part->description ?? 'N/A' }}</td>
                                <td class="py-3 px-4">LKR {{ number_format($part->price, 2) }}</td>
                                <td class="py-3 px-4">{{ $part->seller ? $part->seller->first_name . ' ' . $part->seller->last_name : 'N/A' }}</td>
                                <td class="py-3 px-4">{{ $part->status }}</td>
                                <td class="py-3 px-4">{{ $part->condition ?? 'N/A' }}</td>
                                <td class="py-3 px-4">{{ $part->category ?? 'N/A' }}</td>
                                <td class="py-3 px-4">{{ $part->updated_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Add Seller Form -->
        <h3 class="text-2xl font-semibold mb-4">Add New Seller</h3>
        <div class="bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <form action="{{ route('admin.add_seller') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="seller_first_name" class="block text-sm font-medium">First Name</label>
                        <input type="text" name="first_name" id="seller_first_name" class="bg-gray-700 text-white rounded-md p-2 w-full" required>
                    </div>
                    <div>
                        <label for="seller_last_name" class="block text-sm font-medium">Last Name</label>
                        <input type="text" name="last_name" id="seller_last_name" class="bg-gray-700 text-white rounded-md p-2 w-full" required>
                    </div>
                    <div>
                        <label for="seller_email" class="block text-sm font-medium">Email</label>
                        <input type="email" name="email" id="seller_email" class="bg-gray-700 text-white rounded-md p-2 w-full" required>
                    </div>
                    <div>
                        <label for="seller_password" class="block text-sm font-medium">Password</label>
                        <input type="password" name="password" id="seller_password" class="bg-gray-700 text-white rounded-md p-2 w-full" required>
                    </div>
                </div>
                <button type="submit" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Add Seller</button>
            </form>
        </div>

        <!-- Sellers -->
        <h3 class="text-2xl font-semibold mb-4">Sellers</h3>
        @if ($sellers->isEmpty())
            <p class="text-gray-400 mb-6">No sellers found.</p>
        @else
            <div class="bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-700">
                            <th class="py-3 px-4">Name</th>
                            <th class="py-3 px-4">Email</th>
                            <th class="py-3 px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sellers as $seller)
                            <tr class="border-b border-gray-700">
                                <td class="py-3 px-4">{{ $seller->first_name }} {{ $seller->last_name }}</td>
                                <td class="py-3 px-4">{{ $seller->email }}</td>
                                <td class="py-3 px-4 flex space-x-2">
                                    <button onclick="openEditSellerModal({{ $seller->id }}, '{{ $seller->first_name }}', '{{ $seller->last_name }}', '{{ $seller->email }}')" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded-md">Edit</button>
                                    <form action="{{ route('admin.delete_seller', $seller->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this seller?');">
                                        @csrf
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-md">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Edit Seller Modal -->
        <div id="editSellerModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md">
                <h3 class="text-2xl font-semibold mb-4">Edit Seller</h3>
                <form id="editSellerForm" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="edit_seller_id">
                    <div class="mb-4">
                        <label for="edit_seller_first_name" class="block text-sm font-medium">First Name</label>
                        <input type="text" name="first_name" id="edit_seller_first_name" class="bg-gray-700 text-white rounded-md p-2 w-full" required>
                    </div>
                    <div class="mb-4">
                        <label for="edit_seller_last_name" class="block text-sm font-medium">Last Name</label>
                        <input type="text" name="last_name" id="edit_seller_last_name" class="bg-gray-700 text-white rounded-md p-2 w-full" required>
                    </div>
                    <div class="mb-4">
                        <label for="edit_seller_email" class="block text-sm font-medium">Email</label>
                        <input type="email" name="email" id="edit_seller_email" class="bg-gray-700 text-white rounded-md p-2 w-full" required>
                    </div>
                    <div class="mb-4">
                        <label for="edit_seller_password" class="block text-sm font-medium">Password (leave blank to keep unchanged)</label>
                        <input type="password" name="password" id="edit_seller_password" class="bg-gray-700 text-white rounded-md p-2 w-full">
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Save</button>
                        <button type="button" onclick="closeEditSellerModal()" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Add Customer Form -->
        <h3 class="text-2xl font-semibold mb-4">Add New Customer</h3>
        <div class="bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <form action="{{ route('admin.add_customer') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="customer_first_name" class="block text-sm font-medium">First Name</label>
                        <input type="text" name="first_name" id="customer_first_name" class="bg-gray-700 text-white rounded-md p-2 w-full" required>
                    </div>
                    <div>
                        <label for="customer_last_name" class="block text-sm font-medium">Last Name</label>
                        <input type="text" name="last_name" id="customer_last_name" class="bg-gray-700 text-white rounded-md p-2 w-full" required>
                    </div>
                    <div>
                        <label for="customer_email" class="block text-sm font-medium">Email</label>
                        <input type="email" name="email" id="customer_email" class="bg-gray-700 text-white rounded-md p-2 w-full" required>
                    </div>
                    <div>
                        <label for="customer_password" class="block text-sm font-medium">Password</label>
                        <input type="password" name="password" id="customer_password" class="bg-gray-700 text-white rounded-md p-2 w-full" required>
                    </div>
                </div>
                <button type="submit" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Add Customer</button>
            </form>
        </div>

        <!-- Customers -->
        <h3 class="text-2xl font-semibold mb-4">Customers</h3>
        @if ($customers->isEmpty())
            <p class="text-gray-400 mb-6">No customers found.</p>
        @else
            <div class="bg-gray-800 rounded-lg shadow-md p-6">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-700">
                            <th class="py-3 px-4">Name</th>
                            <th class="py-3 px-4">Email</th>
                            <th class="py-3 px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr class="border-b border-gray-700">
                                <td class="py-3 px-4">{{ $customer->first_name }} {{ $customer->last_name }}</td>
                                <td class="py-3 px-4">{{ $customer->email }}</td>
                                <td class="py-3 px-4 flex space-x-2">
                                    <button onclick="openEditCustomerModal({{ $customer->id }}, '{{ $customer->first_name }}', '{{ $customer->last_name }}', '{{ $customer->email }}')" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded-md">Edit</button>
                                    <form action="{{ route('admin.delete_customer', $customer->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                        @csrf
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-md">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Edit Customer Modal -->
        <div id="editCustomerModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md">
                <h3 class="text-2xl font-semibold mb-4">Edit Customer</h3>
                <form id="editCustomerForm" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="edit_customer_id">
                    <div class="mb-4">
                        <label for="edit_customer_first_name" class="block text-sm font-medium">First Name</label>
                        <input type="text" name="first_name" id="edit_customer_first_name" class="bg-gray-700 text-white rounded-md p-2 w-full" required>
                    </div>
                    <div class="mb-4">
                        <label for="edit_customer_last_name" class="block text-sm font-medium">Last Name</label>
                        <input type="text" name="last_name" id="edit_customer_last_name" class="bg-gray-700 text-white rounded-md p-2 w-full" required>
                    </div>
                    <div class="mb-4">
                        <label for="edit_customer_email" class="block text-sm font-medium">Email</label>
                        <input type="email" name="email" id="edit_customer_email" class="bg-gray-700 text-white rounded-md p-2 w-full" required>
                    </div>
                    <div class="mb-4">
                        <label for="edit_customer_password" class="block text-sm font-medium">Password (leave blank to keep unchanged)</label>
                        <input type="password" name="password" id="edit_customer_password" class="bg-gray-700 text-white rounded-md p-2 w-full">
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Save</button>
                        <button type="button" onclick="closeEditCustomerModal()" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Add New Admin -->
        <h3 class="text-2xl font-semibold mb-4 mt-4">Add New Admin</h3>
        <div class="bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <form action="{{ route('admin.add_admin') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="first_name" class="block text-gray-300 font-bold mb-2">First Name</label>
                        <input type="text" name="first_name" id="first_name" class="w-full p-2 rounded-md border border-gray-600 bg-gray-700 text-white @error('first_name') border-red-500 @enderror" value="{{ old('first_name') }}" required>
                        @error('first_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="last_name" class="block text-gray-300 font-bold mb-2">Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="w-full p-2 rounded-md border border-gray-600 bg-gray-700 text-white @error('last_name') border-red-500 @enderror" value="{{ old('last_name') }}" required>
                        @error('last_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="email" class="block text-gray-300 font-bold mb-2">Email</label>
                        <input type="email" name="email" id="email" class="w-full p-2 rounded-md border border-gray-600 bg-gray-700 text-white @error('email') border-red-500 @enderror" value="{{ old('email') }}" required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-gray-300 font-bold mb-2">Password</label>
                        <input type="password" name="password" id="password" class="w-full p-2 rounded-md border border-gray-600 bg-gray-700 text-white @error('password') border-red-500 @enderror" required>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex justify-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">Add Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('include.footer')
<script>
    function openAddPartModal() {
        document.getElementById('part_name').value = '';
        document.getElementById('seller').value = '';
        document.getElementById('price').value = '';
        document.getElementById('status').value = 'Available';
        document.getElementById('condition').value = 'New';
        document.getElementById('category').value = '';
        document.getElementById('description').value = '';
        document.getElementById('image1').value = '';
        document.getElementById('image2').value = '';
        document.getElementById('image3').value = '';
        document.getElementById('addPartModal').classList.remove('hidden');
    }

    function closeAddPartModal() {
        document.getElementById('addPartModal').classList.add('hidden');
    }

    function openEditPartModal(id, part_name, description, price, seller_id, status, condition, category) {
        document.getElementById('edit_part_id').value = id;
        document.getElementById('edit_part_name').value = part_name;
        document.getElementById('edit_part_description').value = description || '';
        document.getElementById('edit_part_price').value = price;
        document.getElementById('edit_part_seller').value = seller_id;
        document.getElementById('edit_part_status').value = status;
        document.getElementById('edit_part_condition').value = condition || 'New';
        document.getElementById('edit_part_category').value = category || '';
        document.getElementById('editPartForm').action = '/admin/edit-part/' + id;
        document.getElementById('editPartModal').classList.remove('hidden');
    }

    function closeEditPartModal() {
        document.getElementById('editPartModal').classList.add('hidden');
    }

    function openEditSellerModal(id, first_name, last_name, email) {
        document.getElementById('edit_seller_id').value = id;
        document.getElementById('edit_seller_first_name').value = first_name;
        document.getElementById('edit_seller_last_name').value = last_name;
        document.getElementById('edit_seller_email').value = email;
        document.getElementById('edit_seller_password').value = '';
        document.getElementById('editSellerForm').action = '/admin/edit-seller/' + id;
        document.getElementById('editSellerModal').classList.remove('hidden');
    }

    function closeEditSellerModal() {
        document.getElementById('editSellerModal').classList.add('hidden');
    }

    function openEditCustomerModal(id, first_name, last_name, email) {
        document.getElementById('edit_customer_id').value = id;
        document.getElementById('edit_customer_first_name').value = first_name;
        document.getElementById('edit_customer_last_name').value = last_name;
        document.getElementById('edit_customer_email').value = email;
        document.getElementById('edit_customer_password').value = '';
        document.getElementById('editCustomerForm').action = '/admin/edit-customer/' + id;
        document.getElementById('editCustomerModal').classList.remove('hidden');
    }

    function closeEditCustomerModal() {
        document.getElementById('editCustomerModal').classList.add('hidden');
    }
</script>
@endsection