<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Borrow Request Approved</title>
    <link rel="stylesheet" href="{{ url('css/mail/borrow_request.css') }}">
    
</head>
<body>
    <div class="email-container">
        
        <div class="content">
            <p>Dear {{ucfirst($borrowRequest->user->name)}},</p>
            
            <p><strong>Congratulations!</strong> Your book request has been approved. The requested item is now ready for pickup at our library.</p>
            
            <div class="book-details">
                <div class="book-title">{{$borrowRequest->book->title}}</div>
                <p><strong>Author:</strong> {{$borrowRequest->book->author->name}}</p>
                <p><strong>Request ID:</strong> #molibrary_{{ rand(100000,999999) }}</p>
                <p><strong>Available until:</strong> {{$borrowRequest->due_date}}</p>
                <p><strong>Library:</strong> {{$borrowRequest->library->name}}</p>
            </div>
            
            <p>You can pick up your book at the circulation desk during our regular hours. Please remember to bring your library card or a valid ID.</p>
            
            <p>Library Hours:<br>
            Monday - Friday: 9:00 AM - 8:00 PM<br>
            Saturday: 10:00 AM - 6:00 PM<br>
            Sunday: 12:00 PM - 5:00 PM</p>
            
            <center>
                <a href="http://molibrary.in/borrowing-history" class="button">View My Account</a>
            </center>
            
            <div class="important-note">
                <p><strong>Please note:</strong> The book will be held for 7 days. If not picked up within this period, the membership will be canceled.</p>
            </div>
            
            <div class="contact">
                <p>If you have any questions, feel free to contact us:</p>
                <p>📞 Phone: +91 999999999<br>
                ✉️ Email: molibrary@gmail.com</p>
            </div>
        </div>
        
        <div class="footer">
            <p>{{$borrowRequest->library->name}} | {{$borrowRequest->library->location}}</p>
            <p>© 2025 MoLibrary. All rights reserved.</p>
        </div>
    </div>
</body>
</html>