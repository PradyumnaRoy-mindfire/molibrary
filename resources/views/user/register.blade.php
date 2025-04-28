<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background: url('https://png.pngtree.com/background/20230527/original/pngtree-an-old-bookcase-in-a-library-picture-image_2760144.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center p-3">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="card p-4 p-sm-5 shadow-lg" style="background-color: rgba(255, 255, 255, 0.95); border-radius: 15px;">
                    <div class="card-body">
                        <h1 class="card-title text-center mb-4 fs-2 fw-bold">
                            <i class="bi bi-person-plus-fill me-2"></i>Register
                        </h1>
                        
                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold">
                                    <i class="bi bi-person-fill me-1"></i>Full Name <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" placeholder="Enter your name">
                                </div>
                                @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label fw-bold">
                                    <i class="bi bi-envelope-fill me-1"></i>Email Address <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" placeholder="Enter email">
                                </div>
                                @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="phone" class="form-label fw-bold">
                                    <i class="bi bi-telephone-fill me-1"></i>Phone Number <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                    <input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Enter phone number">
                                </div>
                                @error('phone')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="role" class="form-label fw-bold">
                                    <i class="bi bi-person-badge-fill me-1"></i>Register As <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text"><i class="bi bi-people"></i></span>
                                    <select class="form-select" id="role" name="role">
                                        <option value="" disabled selected>--Select Role--</option>
                                        <option value="librarian">Librarian</option>
                                        <option value="member">Member</option>
                                    </select>
                                </div>
                                @error('role')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4" id="librarySelect" style="display: none;">
                                <label for="library_id" class="form-label fw-bold">
                                    <i class="bi bi-building-fill me-1"></i>Library <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text"><i class="bi bi-book"></i></span>
                                    <select class="form-select" id="library_id" name="library_id">
                                        <option value="" disabled selected>-- Select Library --</option>
                                        @foreach ($libraries as $library)
                                        <option value="{{ $library->id }}">{{ $library->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-danger mt-1" id="libraryError"></span>
                            </div>

                            <div class="mb-4">
                                <label for="address" class="form-label fw-bold">
                                    <i class="bi bi-house-door-fill me-1"></i>Home Address
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text"><i class="bi bi-house"></i></span>
                                    <input type="text" class="form-control" name="address" id="address" value="{{ old('address') }}" placeholder="Enter address">
                                </div>
                                @error('address')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-bold">
                                    <i class="bi bi-lock-fill me-1"></i>Create Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <i class="bi bi-eye-slash"></i>
                                    </button>
                                </div>
                                @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 py-2 fw-bold mt-3" id="register">
                                <i class="bi bi-person-plus me-2"></i>Register
                            </button>

                            <p class="mt-4 mb-0 text-center">
                                Already have an account? <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Login here</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            function toggleLibrarySelect() {
                if ($('#role').val() === 'librarian') {
                    $('#librarySelect').slideDown();
                    $('#register').html('<i class="bi bi-person-plus me-2"></i>Apply');
                } else {
                    $('#librarySelect').slideUp();
                    $('#register').html('<i class="bi bi-person-plus me-2"></i>Register');
                }
            }

            $('#role').change(function() {
                toggleLibrarySelect();
            });

            // Password visibility toggle
            $('#togglePassword').click(function() {
                const passwordField = $('#password');
                const icon = $(this).find('i');
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('bi-eye-slash').addClass('bi-eye');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('bi-eye').addClass('bi-eye-slash');
                }
            });

            toggleLibrarySelect();
        });
    </script>
</body>
</html>