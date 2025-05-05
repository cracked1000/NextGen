<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Confirmation</title>
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
        .content {
            padding: 20px;
        }
        .content h2 {
            color: #333333;
        }
        .content p {
            color: #666666;
            line-height: 1.6;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .details-table th, .details-table td {
            padding: 10px;
            border: 1px solid #dddddd;
            text-align: left;
        }
        .details-table th {
            background-color: #f8f8f8;
            color: #333333;
        }
        .footer {
            text-align: center;
            padding: 10px;
            color: #999999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Purchase Confirmation</h1>
        </div>
        <div class="content">
            <h2>Hello {{ $customer->first_name }} {{ $customer->last_name }},</h2>
            <p>Thank you for your purchase at NextGen Computing! Below are the details of your order:</p>

            <table class="details-table">
                <tr>
                    <th>Order ID</th>
                    <td>#{{ $order->id }}</td>
                </tr>
                <tr>
                    <th>Part Name</th>
                    <td>{{ $part->part_name }}</td>
                </tr>
                <tr>
                    <th>Price</th>
                    <td>{{ number_format($part->price, 2) }} LKR</td>
                </tr>
                <tr>
                    <th>Shipping Address</th>
                    <td>{{ $order->address }}<br>Zipcode: {{ $order->zipcode }}</td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td>{{ $order->phone_number }}</td>
                </tr>
                <tr>
                    <th>Payment Method</th>
                    <td>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
                </tr>
                <tr>
                    <th>Purchase Date</th>
                    <td>{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            </table>

            <p>To continue with the build process please connect with us using whatsapp with the relavent Quotation number</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} NextGen Computing. All rights reserved.</p>
        </div>
    </div>
</body>
</html>