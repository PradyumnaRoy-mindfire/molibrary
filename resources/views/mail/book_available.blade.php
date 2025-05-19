<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Availability Notification</title>
    <link rel="stylesheet" href="{{ url('css/mail/book_available.css') }}">
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
            background-color: #166534;
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
            border-left: 4px solid #16a34a;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .book-title {
            font-weight: bold;
            color: #166534;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .availability-details {
            background-color: #ecfdf5;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            border: 1px solid #a7f3d0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .available-badge {
            font-size: 16px;
            font-weight: bold;
            color: white;
            text-align: center;
            margin: 15px 0;
            padding: 8px;
            background-color: #16a34a;
            border-radius: 6px;
            display: inline-block;
        }

        .countdown {
            font-size: 24px;
            font-weight: bold;
            color: #b91c1c;
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            background-color: #fef2f2;
            border-radius: 6px;
            border: 1px dashed #f87171;
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
            background-color: #16a34a;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
            margin-top: 15px;
        }

        .button:hover {
            background-color: #15803d;
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