<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: black !important;
        }
        h1, h2, p, table {
            color: black !important;
        }
        strong {
            color: black !important;
        }
        a {
            color: black !important;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h1>New Quotation Submitted</h1>
    <p><strong>Customer Name:</strong> {{ $quotation->customer_name }}</p>
    <p><strong>Email:</strong> <a href="mailto:{{ $quotation->email }}">{{ $quotation->email }}</a></p>
    <p><strong>Company Name:</strong> {{ $quotation->company_name }}</p>
    <p><strong>Phone Number:</strong> {{ $quotation->phone_number }}</p>
    <p><strong>Business Unit:</strong> {{ $quotation->business_unit }}</p>
    <p><strong>Notes:</strong> {{ $quotation->notes }}</p>
    
    <h2>Quotation Items</h2>
    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ number_format($item['price'], 2) }}</td>
                    <td>{{ number_format($item['total'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <h2>Summary</h2>
    <p><strong>Subtotal:</strong> {{ number_format($quotation->subtotal, 2) }}</p>
    <p><strong>Tax:</strong> {{ number_format($quotation->tax, 2) }}</p>
    <p><strong>Total:</strong> {{ number_format($quotation->total, 2) }}</p>

</body>
</html>
