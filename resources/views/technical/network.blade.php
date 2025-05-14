<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician Network</title>

    <style>
        body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #1a2526;
    color: white;
}


main {
    text-align: center;
    padding: 20px;
}

h1 {
    font-size: 24px;
    margin-bottom: 20px;
}

.search-bar {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.search-bar select {
    padding: 10px;
    font-size: 16px;
    border: none;
    border-radius: 5px 0 0 5px;
    width: 200px;
    background-color: white;
    color: black;
    appearance: none; /* Removes default arrow in some browsers */
    -webkit-appearance: none; /* Safari/Chrome */
    -moz-appearance: none; /* Firefox */
    position: relative;
    cursor: pointer;
}

/* Custom arrow for the dropdown (optional, using pseudo-elements) */
.search-bar select::after {
    content: '▼';
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
}

.search-bar button {
    padding: 10px 20px;
    font-size: 16px;
    background-color: red;
    color: white;
    border: none;
    border-radius: 0 5px 5px 0;
    cursor: pointer;
    transition: background-color 0.3s;
}

.search-bar button:hover {
    background-color: darkred;
}

table {
    width: 80%;
    margin: 0 auto;
    border-collapse: collapse;
}

th, td {
    padding: 10px;
    border: 1px solid white;
    text-align: left;
}

th {
    background-color: #34495e;
    font-weight: normal;
}

tbody tr {
    background-color: #2c3e50;
}
    </style>
</head>
<body>
    @include('include.header') <!-- Include your header -->
<main>
        <h1>Technician Network</h1>
        <div class="search-bar">
            <form method="GET" action="{{ route('technical.network') }}">
                <select name="district" id="district">
                    <option value="">Choose your district</option>
                    @foreach($districts as $district)
                        <option value="{{ $district }}" {{ request('district') == $district ? 'selected' : '' }}>
                            {{ $district }}
                        </option>
                    @endforeach
                </select>
                <button type="submit">Search</button>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>District</th>
                    <th>Town</th>
                    <th>Name</th>
                    <th>Contact Number</th>
                </tr>
            </thead>
            <tbody>
                @forelse($technicians as $technician)
                    <tr>
                        <td>{{ $technician->district }}</td>
                        <td>{{ $technician->town }}</td>
                        <td>{{ $technician->name }}</td>
                        <td>{{ $technician->contact_number }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No technicians found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </main>
</body>
</html>