<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Availability Notification</title>
    <link rel="stylesheet" href="{{ url('css/mail/book_available.css') }}">
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>📚 Your Reserved Book is Available!</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $reservation->user->name }},</p>
            
            <p>Great news! The book you requested is now available for pickup at our library.</p>
            
            <div class="book-details">
                <div class="book-title">{{ $reservation->book->title }}</div>
                <p><strong>Request ID:</strong> #molibrary_{{ $reservation->id }}</p>
                <p><strong>ISBN:</strong> {{ $reservation->book->isbn ?? 'N/A' }}</p>
                <p><strong>Author:</strong> {{ $reservation->book->author->name ?? 'Unknown Author' }}</p>
                <p><strong>Reservation Date:</strong> {{ \Carbon\Carbon::parse($reservation->borrow_date)->format('j M, Y - h:i A') }}</p>
                
                <div class="available-badge">AVAILABLE NOW</div>
            </div>
            
            
            
            <p><strong>Act quickly!</strong> This book is being held for you, but will be released to other patrons if not claimed within the time period shown above.</p>
            
            <center>
                <a href="http://molibrary.in/reserved-books" class="button">Borrow Now</a>
            </center>
            
            <div class="important-note">
                <p><strong>Please note:</strong> You need to bring your library card or valid ID when you come to pick up your book. The book will be held at the circulation desk under your name.</p>
            </div>
            
            <div class="contact">
                <p>Library Hours:</p>
                <p>Monday - Friday: 9:00 AM - 8:00 PM<br>
                Saturday: 10:00 AM - 6:00 PM<br>
                Sunday: 12:00 PM - 5:00 PM</p>
                
                <p>If you have any questions, feel free to contact us:</p>
                <p>📞 Phone: +91 999999999<br>
                ✉️ Email: molibrary@gmail.com</p>
            </div>
        </div>
        
        <div class="footer">
            <p>{{ $reservation->library->name ?? 'MoLibrary' }} | {{ $reservation->library->location ?? 'Main Branch' }}</p>
            <p>© 2025 MoLibrary. All rights reserved.</p>
        </div>
    </div>
</body>
</html>