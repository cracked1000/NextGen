<!DOCTYPE html>
<html>
<head>
    <title>PC Build Quotation</title>
</head>
<body>
    <h1>Your {{ $spec }} PC Build Quotation for {{ $use_case }}</h1>
    <p><strong>Quotation Number:</strong> {{ $quotationNumber }}</p>
    <p><strong>Source:</strong> {{ $source }}</p>
    
    <h2>Build Details</h2>
    <p><strong>Total Price:</strong> LKR {{ number_format($build['total_price'], 2) }}</p>
    <p><strong>Remaining Budget:</strong> LKR {{ number_format($build['remaining_budget'], 2) }}</p>
    <p><strong>Budget Used:</strong> {{ $build['budget_used_percentage'] }}%</p>
    
    <h3>Components</h3>
    <ul>
        <li><strong>CPU:</strong> {{ $build['cpu'] ? $build['cpu']['name'] : 'Not selected' }} (LKR {{ $build['cpu'] ? number_format($build['cpu']['price'], 2) : '0.00' }})</li>
        <li><strong>Motherboard:</strong> {{ $build['motherboard'] ? $build['motherboard']['name'] : 'Not selected' }} (LKR {{ $build['motherboard'] ? number_format($build['motherboard']['price'], 2) : '0.00' }})</li>
        <li><strong>GPU:</strong> {{ $build['gpu'] ? $build['gpu']['name'] : 'Not selected' }} (LKR {{ $build['gpu'] ? number_format($build['gpu']['price'], 2) : '0.00' }})</li>
        <li><strong>RAM:</strong> {{ $build['ram'] ? $build['ram']['name'] : 'Not selected' }} (LKR {{ $build['ram'] ? number_format($build['ram']['price'], 2) : '0.00' }})</li>
        <li><strong>Storage:</strong> {{ $build['storage'] ? $build['storage']['name'] : 'Not selected' }} (LKR {{ $build['storage'] ? number_format($build['storage']['price'], 2) : '0.00' }})</li>
        <li><strong>Power Supply:</strong> {{ $build['power_supply'] ? $build['power_supply']['name'] : 'Not selected' }} (LKR {{ $build['power_supply'] ? number_format($build['power_supply']['price'], 2) : '0.00' }})</li>
    </ul>

    <h3>Compatibility</h3>
    @if ($build['compatibility']['is_compatible'])
        <p>This build is compatible.</p>
    @else
        <p>This build has compatibility issues:</p>
        <ul>
            @foreach ($build['compatibility']['errors'] as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    @if (!empty($build['compatibility']['warnings']))
        <h4>Warnings</h4>
        <ul>
            @foreach ($build['compatibility']['warnings'] as $warning)
                <li>{{ $warning }}</li>
            @endforeach
        </ul>
    @endif

    <p>To continue with the build process please connect with us using whatsapp with the relavent Quotation number</p>
    <p>https://wa.me/94774101481</p>

</body>
</html>