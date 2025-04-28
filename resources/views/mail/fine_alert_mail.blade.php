<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Fine Notification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .email-container {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color: #c2410c;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .logo {
            height: 60px;
            margin-bottom: 10px;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
        }
        .book-details {
            background-color: white;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #f97316;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .book-title {
            font-weight: bold;
            color: #c2410c;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .fine-details {
            background-color: #fffbeb;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            border: 1px solid #fcd34d;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .fine-amount {
            font-size: 24px;
            font-weight: bold;
            color: #c2410c;
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            background-color: #fff7ed;
            border-radius: 6px;
        }
        .footer {
            background-color: #f1f5f9;
            padding: 15px;
            text-align: center;
            font-size: 14px;
            color: #64748b;
        }
        .button {
            display: inline-block;
            background-color: #f97316;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
            margin-top: 15px;
        }
        .button:hover {
            background-color: #ea580c;
        }
        .date-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            border-bottom: 1px dashed #e0e0e0;
            padding-bottom: 8px;
        }
        .date-label {
            font-weight: bold;
            color: #64748b;
        }
        .date-value {
            color: #334155;
        }
        .important-note {
            font-size: 14px;
            color: #64748b;
            border-top: 1px solid #e0e0e0;
            margin-top: 20px;
            padding-top: 15px;
        }
        .contact {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>⚠️ Fine Notification</h1>
        </div>
        
        <div class="content">
            

            <p>Dear {{ $borrowRequest->user->name }},</p>
            
            <p>Our records indicate that a book you borrowed was returned after its due date. As per library policy, a fine has been applied to your account.</p>
            
            <div class="book-details">
                <div class="book-title">{{ $borrowRequest->book->title }}</div>
                <p><strong>ISBN:</strong> {{ $borrowRequest->book->isbn }}</p>
                <p><strong>Author:</strong> {{ $borrowRequest->book->author->name }}</p>
            </div>
            
            <div class="fine-details">
                <div class="date-row">
                    <span class="date-label">Borrow Date:</span>
                    <span class="date-value">{{ \Carbon\Carbon::parse($borrowRequest->borrow_date)->format('j M, Y - h:i A') }}</span>
                </div>
                <div class="date-row">
                    <span class="date-label">Due Date:</span>
                    <span class="date-value">{{ \Carbon\Carbon::parse($borrowRequest->due_date)->format('j M, Y - h:i A') }}</span>
                </div>
                <div class="date-row">
                    <span class="date-label">Return Date:</span>
                    <span class="date-value">{{ \Carbon\Carbon::parse($borrowRequest->return_date)->format('j M, Y - h:i A') }}</span>
                </div>
                
                
                <div class="fine-amount">
                    Fine Amount: ₹{{ number_format($borrowRequest->fine->amount, 2) }}
                </div>
            </div>
            
            <p>Please settle this fine at your earliest convenience to maintain your borrowing privileges at the library.</p>
            
            <center>
                <a href="http://molibrary.in/borrowing-history" class="button">Pay Fine Now</a>
            </center>
            
            <div class="important-note">
                <p><strong>Please note:</strong> Unpaid fines may affect your ability to borrow additional materials. If you believe this fine was applied in error, please contact our library staff.</p>
            </div>
            
            <div class="contact">
                <p>If you have any questions, feel free to contact us:</p>
                <p>📞 Phone: +91 999999999<br>
                ✉️ Email: molibrary@gmail.com</p>
            </div>
        </div>
        
        <div class="footer">
            <p>{{ $borrowRequest->library->name }} | {{ $borrowRequest->library->location }}</p>
            <p>© 2025 MoLibrary. All rights reserved.</p>
        </div>
    </div>
</body>
</html>