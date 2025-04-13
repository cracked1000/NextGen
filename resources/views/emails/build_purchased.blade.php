<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Build Purchase Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #e53e3e;
            color: #ffffff;
            padding: 15px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .content h2 {
            color: #333333;
            font-size: 20px;
            margin-bottom: 10px;
        }
        .content p {
            color: #666666;
            font-size: 16px;
            line-height: 1.5;
            margin: 5px 0;
        }
        .content .build-details {
            margin-top: 20px;
        }
        .content .build-details p {
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            padding: 15px;
            background-color: #f4f4f4;
            border-radius: 0 0 8px 8px;
            color: #999999;
            font-size: 14px;
        }
        .footer a {
            color: #e53e3e;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>NextGen Computing</h1>
        </div>
        <div class="content">
            <h2>Build Purchase Confirmation</h2>
            <p>Dear {{ $build->user->first_name }} {{ $build->user->last_name }},</p>
            <p>Thank you for purchasing a build with NextGen Computing! Below are the details of your purchased build:</p>

            <div class="build-details">
                <h3>{{ $build->name ?? 'Build #' . $build->id }}</h3>
                <p><strong>CPU:</strong> {{ $build->cpu ? $build->cpu->name : 'Not selected' }}</p>
                <p><strong>Motherboard:</strong> {{ $build->motherboard ? $build->motherboard->name : 'Not selected' }}</p>
                <p><strong>GPU:</strong> {{ $build->gpu ? $build->gpu->name : 'Not selected' }}</p>
                <p><strong>RAM:</strong>
                    @if ($build->rams->isEmpty())
                        Not selected
                    @else
                        @foreach ($build->rams as $ram)
                            {{ $ram->name }}@if (!$loop->last), @endif
                        @endforeach
                    @endif
                </p>
                <p><strong>Storage:</strong>
                    @if ($build->storages->isEmpty())
                        Not selected
                    @else
                        @foreach ($build->storages as $storage)
                            {{ $storage->name }}@if (!$loop->last), @endif
                        @endforeach
                    @endif
                </p>
                <p><strong>Power Supply:</strong> {{ $build->powerSupply ? $build->powerSupply->name : 'Not selected' }}</p>
                <p><strong>Total Price:</strong> {{ number_format($build->total_price, 2) }} LKR</p>
            </div>

            <p>We’ll process your order soon and keep you updated on the status. If you have any questions, feel free to contact us.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} NextGen Computing. All rights reserved.</p>
            <p><a href="{{ url('/') }}">Visit our website</a></p>
        </div>
    </div>
</body>
</html>