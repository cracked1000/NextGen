@extends('layouts')
@section('title', 'Sell a Part')
@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-900">
    <div class="w-full max-w-2xl mx-auto bg-gray-800 text-white p-6 rounded-lg shadow-lg max-h-[90vh] overflow-y-auto">
        <h3 class="text-2xl font-semibold mb-4">Sell a Part</h3>

        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('seller.sell') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="part_name" class="block text-gray-300 font-bold mb-2">Part Name</label>
                <input type="text" name="part_name" id="part_name" class="w-full p-3 rounded-md border border-gray-600 bg-gray-700 text-white @error('part_name') border-red-500 @enderror" value="{{ old('part_name') }}" required>
                @error('part_name')
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
                    <option value="pending">Pending</option>
                    <!-- <option value="Available">Available</option>
                    <option value="Sold">Sold</option> -->
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
                <a href="{{ route('sellers.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-full text-lg text-center">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection