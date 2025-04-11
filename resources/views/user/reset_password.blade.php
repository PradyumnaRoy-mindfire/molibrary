<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background: url('https://png.pngtree.com/background/20230527/original/pngtree-an-old-bookcase-in-a-library-picture-image_2760144.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }

        .countdown {
            color: #dc3545;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <form class="mt-4 d-none" method="POST" action="{{ route('check-user') }}">
        @csrf
        <label for="password" class="form-label fw-bold">New Password</label>
        <input type="password" name="password" id="password" class="form-control form-control-lg mb-3" required>

        <label for="password_confirmation" class="form-label fw-bold">Confirm New Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-lg mb-3" required>

        <button type="submit" class="btn btn-dark w-100 fw-bold">Reset Password</button>
    </form>
</body>

</html>