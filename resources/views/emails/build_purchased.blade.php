<!DOCTYPE html>
<html>
<head>
    <title>Build Purchase Confirmation</title>
</head>
<body>
    <h1>Thank You for Your Purchase!</h1>
    <p><strong>Quotation Number:</strong> {{ $quotationNumber }}</p>
    <p><strong>Source:</strong> {{ $source }}</p>
    <p>Dear {{ $build->user->first_name }},</p>
    <p>You have successfully purchased a build. Here are the details:</p>
    
    <h2>Build Details</h2>
    <p><strong>Name:</strong> {{ $build->name ?? 'Build #' . $build->id }}</p>
    <p><strong>Total Price:</strong> {{ number_format($build->total_price, 2) }} LKR</p>
    
    <h3>Components</h3>
    <ul>
        <li><strong>CPU:</strong> {{ $build->cpu ? $build->cpu->name : 'Not selected' }}</li>
        <li><strong>Motherboard:</strong> {{ $build->motherboard ? $build->motherboard->name : 'Not selected' }}</li>
        <li><strong>GPU:</strong> {{ $build->gpu ? $build->gpu->name : 'Not selected' }}</li>
        <li><strong>RAM:</strong>
            @if ($build->rams->isEmpty())
                Not selected
            @else
                @foreach ($build->rams as $ram)
                    {{ $ram->name }}@if (!$loop->last), @endif
                @endforeach
            @endif
        </li>
        <li><strong>Storage:</strong>
            @if ($build->storages->isEmpty())
                Not selected
            @else
                @foreach ($build->storages as $storage)
                    {{ $storage->name }}@if (!$loop->last), @endif
                @endforeach
            @endif
        </li>
        <li><strong>Power Supply:</strong> {{ $build->powerSupply ? $build->powerSupply->name : 'Not selected' }}</li>
    </ul>

    <p>To continue with the build process please connect with us using whatsapp with the relavent Quotation number</p>
    <p>https://wa.me/94774101481</p>
</body>
</html>