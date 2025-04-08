<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation - NextGen Computing</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-8">Your PC Build Quotations</h1>
        <p class="mb-4">Budget: {{ number_format($budget, 2) }} LKR | Use Case: {{ ucfirst($use_case) }}</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach (['low' => 'Low Spec', 'medium' => 'Medium Spec', 'high' => 'High Spec'] as $spec => $label)
                <div class="bg-gray-800 p-6 rounded-lg">
                    <h2 class="text-xl font-semibold mb-4">{{ $label }} Build</h2>
                    <div class="space-y-2">
                        <p><strong>CPU:</strong> {{ $builds[$spec]['cpu']->name }} - {{ number_format($builds[$spec]['cpu']->prices->min('price'), 2) }} LKR</p>
                        <p><strong>Motherboard:</strong> {{ $builds[$spec]['motherboard']->name }} - {{ number_format($builds[$spec]['motherboard']->prices->min('price'), 2) }} LKR</p>
                        <p><strong>GPU:</strong> {{ $builds[$spec]['gpu']->name }} - {{ number_format($builds[$spec]['gpu']->prices->min('price'), 2) }} LKR</p>
                        <p><strong>RAM:</strong> {{ $builds[$spec]['ram']->name }} - {{ number_format($builds[$spec]['ram']->prices->min('price'), 2) }} LKR</p>
                        <p><strong>Storage:</strong> {{ $builds[$spec]['storage']->name }} - {{ number_format($builds[$spec]['storage']->prices->min('price'), 2) }} LKR</p>
                        <p><strong>Power Supply:</strong> {{ $builds[$spec]['power_supply']->name }} - {{ number_format($builds[$spec]['power_supply']->prices->min('price'), 2) }} LKR</p>
                    </div>
                    <p class="mt-4"><strong>Total Price:</strong> {{ number_format($builds[$spec]['total_price'], 2) }} LKR</p>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>