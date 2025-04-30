<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .success-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            padding: 40px;
            text-align: center;
        }
        .check-icon {
            background-color: #27ae60;
            color: white;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            line-height: 80px;
            font-size: 40px;
            margin: 0 auto 20px;
        }
        h1 {
            color: #2b5797;
            margin-bottom: 10px;
        }
        .success-message {
            font-size: 18px;
            margin-bottom: 30px;
            color: #555;
        }
        .invoice-info {
            background-color: #f8f8f8;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 30px;
            text-align: left;
        }
        .invoice-info p {
            margin: 5px 0;
        }
        .invoice-label {
            color: #777;
            font-size: 14px;
        }
        .button {
            background-color: #2b5797;
            color: white;
            padding: 12px 30px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
            border: none;
            cursor: pointer;
        }
        .secondary-button {
            background-color: transparent;
            color: #2b5797;
            border: 1px solid #2b5797;
            padding: 12px 30px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
            margin-left: 10px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2b5797;
            margin-bottom: 30px;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="logo">MoLibrary</div>
        <div class="check-icon">✓</div>
        <h1>Payment Successful!</h1>
        <p class="success-message">Congratulations! Your payment has been processed successfully.</p>
        
        <div class="invoice-info">
            <p><span class="invoice-label">Invoice Number:</span> {{ $invoice_number }}</p>
            <p><span class="invoice-label">Email:</span> {{ $receipt_email }}</p>
            <p><span class="invoice-label">Amount Paid:</span> Rs.{{ number_format($amount, 2) }}</p>
            <p><span class="invoice-label">Date:</span>{{ $date }} </p>
            <p><span class="invoice-label">Payment Method:</span> Card</p>
        </div>
        
        <p>An invoice has been attched to this email,Checkout...</p>
        
        
        <a href="http://molibrary.in/dashboard" class="secondary-button">Return to Home</a>
        
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>If you have any questions, please contact our support team.</p>
        </div>
    </div>
</body>
</html>