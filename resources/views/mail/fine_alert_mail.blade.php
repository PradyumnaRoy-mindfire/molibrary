<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Fine Notification</title>
    <link rel="stylesheet" href="{{ url('css/mail/fine_alert_mail.css') }}">
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