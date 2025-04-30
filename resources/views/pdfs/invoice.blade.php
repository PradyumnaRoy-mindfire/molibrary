<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 12px;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table td, table th {
            padding: 10px;
            vertical-align: top;
            text-align: left;
        }
        .header-table {
            margin-bottom: 40px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2b5797;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #2b5797;
            text-align: right;
        }
        .meta-table {
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .meta-table th {
            background-color: #f8f8f8;
            font-weight: bold;
        }
        .company-details, .customer-details {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            color: #2b5797;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .items-table {
            margin-top: 30px;
            border: 1px solid #ddd;
        }
        .items-table th {
            background-color: #f8f8f8;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        .items-table td {
            border-bottom: 1px solid #ddd;
        }
        .amount-column {
            text-align: right;
        }
        .total-table {
            border: none;
            width: 40%;
            margin-left: 60%;
        }
        .total-table td {
            padding: 5px 10px;
        }
        .total-row {
            font-weight: bold;
        }
        .grand-total {
            background-color: #f8f8f8;
            border-top: 2px solid #ddd;
            font-weight: bold;
            font-size: 14px;
        }
        .payment-info {
            margin: 30px 0;
            padding: 15px;
            background-color: #f8f8f8;
            border-left: 5px solid #2b5797;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .thank-you {
            font-size: 16px;
            color: #2b5797;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .color-green {
            color: #27ae60;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table class="header-table">
            <tr>
                <td width="50%">
                    <div class="logo">MoLibrary</div>
                </td>
                <td width="50%">
                    <div class="invoice-title">INVOICE</div>
                </td>
            </tr>
        </table>

        <table class="meta-table">
            <tr>
                <th width="33%">INVOICE NUMBER</th>
                <th width="33%">DATE ISSUED</th>
                <th width="33%">STATUS</th>
            </tr>
            <tr>
                <td>{{ $invoice_number }}</td>
                <td>{{ $date }}</td>
                <td class="color-green">PAID</td>
            </tr>
        </table>

        <table>
            <tr>
                <td width="50%" style="vertical-align: top;">
                    <div class="company-details">
                        <div class="section-title">FROM</div>
                        <p>
                            <strong>MoLibrary Ltd.</strong><br>
                            SRB Tower, Patia, Infocity<br>
                            Bhubaneshwar, Odisha, 751030<br>
                            contact@molibrary.in
                        </p>
                    </div>
                </td>
                <td width="50%" style="vertical-align: top;">
                    <div class="customer-details">
                        <div class="section-title">BILLED TO</div>
                        <p>
                            <strong>{{ $name }}</strong><br>
                            {{ $email }}
                        </p>
                        <div class="section-title" style="margin-top: 15px;">PAYMENT METHOD</div>
                        <p>Card</p>
                    </div>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <tr>
                <th width="70%">DESCRIPTION</th>
                <th width="30%" class="amount-column">AMOUNT</th>
            </tr>
            <tr>
                <td>{{ $payment->description }}</td>
                <td class="amount-column">Rs.{{ number_format($amount, 2) }}</td>
            </tr>
        </table>

        <table class="total-table">
            <tr class="total-row">
                <td>SUBTOTAL</td>
                <td class="amount-column">Rs.{{ number_format($amount, 2) }}</td>
            </tr>
            <tr>
                <td>TAX (0%)</td>
                <td class="amount-column">Rs.0.00</td>
            </tr>
            <tr class="grand-total">
                <td>TOTAL</td>
                <td class="amount-column">Rs.{{ number_format($amount, 2) }}</td>
            </tr>
        </table>

        <div class="payment-info">
            <div class="section-title">PAYMENT INFORMATION</div>
            <p>This invoice was paid using a card on {{ $date }}.</p>
        </div>

        <div class="footer">
            <div class="thank-you">Thank you for your business!</div>
            <p>If you have any questions about this invoice, please contact our support team.</p>
            <p>http://molibrary.in | support@molibrary.gmail.com | +91 999999999</p>
        </div>
    </div>
</body>
</html>