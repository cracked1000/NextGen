<!-- resources/views/emails/build_details.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your PC Build Quotation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        h1, h2 {
            color: #d32f2f;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .summary, .breakdown, .compatibility {
            margin-top: 20px;
        }
        .compatibility .error {
            color: #d32f2f;
        }
        .compatibility .warning {
            color: #f57c00;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>NextGen Computing - PC Build Quotation</h1>
        <p>Dear Customer,</p>
        <p>Thank you for choosing NextGen Computing! Below are the details of your {{ strtoupper($spec) }} spec build for {{ ucfirst($use_case) }}.</p>

        <h2>Component Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Component</th>
                    <th>Name</th>
                    <th>Price (LKR)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>CPU</td>
                    <td>{{ $build['cpu']->name }}</td>
                    <td>{{ $build['cpu']->price ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Motherboard</td>
                    <td>{{ $build['motherboard']->name }}</td>
                    <td>{{ $build['motherboard']->price ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>GPU</td>
                    <td>{{ $build['gpu']->name }}</td>
                    <td>{{ $build['gpu']->price ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>RAM</td>
                    <td>{{ $build['ram']->name }}</td>
                    <td>{{ $build['ram']->price ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Storage</td>
                    <td>{{ $build['storage']->name }}</td>
                    <td>{{ $build['storage']->price ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Power Supply</td>
                    <td>{{ $build['power_supply']->name }}</td>
                    <td>{{ $build['power_supply']->price ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>

        <div class="summary">
            <h2>Summary</h2>
            <p><strong>Total Price:</strong> {{ $build['total_price'] }} LKR</p>
            <p><strong>Remaining Budget:</strong> {{ $build['remaining_budget'] }} LKR</p>
            <p><strong>Budget Used:</strong> {{ $build['budget_used_percentage'] }}%</p>
        </div>

        <div class="breakdown">
            <h2>Price Breakdown</h2>
            <ul>
                @foreach ($build['price_breakdown'] as $component => $percentage)
                    <li>{{ ucfirst($component) }}: {{ round($percentage, 2) }}%</li>
                @endforeach
            </ul>
        </div>

        <div class="compatibility">
            <h2>Compatibility Check</h2>
            @if ($build['compatibility']['is_compatible'])
                <p style="color: #2e7d32;">Build is compatible!</p>
            @else
                <p class="error">Build has compatibility issues!</p>
            @endif

            @if (!empty($build['compatibility']['errors']))
                <h3>Compatibility Errors</h3>
                <ul>
                    @foreach ($build['compatibility']['errors'] as $error)
                        <li class="error">{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            @if (!empty($build['compatibility']['warnings']))
                <h3>Compatibility Warnings</h3>
                <ul>
                    @foreach ($build['compatibility']['warnings'] as $warning)
                        <li class="warning">{{ $warning }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <p>We look forward to assisting you with your PC build. If you have any questions, feel free to contact us at support@nextgencomputing.com.</p>
        <p>Best regards,<br>NextGen Computing Team</p>
    </div>
</body>
</html>