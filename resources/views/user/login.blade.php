<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Login</title>
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
                        <h1 class="card-title text-center mb-4 fs-2 fw-bold">Login</h1>

                        @if(session('loginFailed'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            {{ session('loginFailed') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        @if (session('resetPasswordSuccess'))
                        <x-alert type="success">
                            {{ session('resetPasswordSuccess') }}
                        </x-alert>
                        @endif

                        <form action="{{ route('login') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text"><i class="bi bi-envelope-fill fs-5"></i></span>
                                    <input type="email" class="form-control form-control-lg" name="email" id="email" value="{{ old('email') }}" placeholder="Enter email" required>
                                </div>

                                @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Role Dropdown (Hidden by Default) -->
                            <div class="mb-4" id="roleDiv" style="display: none;">
                                <select class="form-select form-select-lg" id="role" name="role">
                                    <option value="" disabled selected>Login As</option>
                                    <!-- Roles will be dynamically populated -->
                                </select>
                                @error('role')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text"><i class="bi bi-lock-fill fs-5"></i></span>
                                    <input type="password" class="form-control form-control-lg" name="password" id="password" placeholder="Enter password" required>
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <i class="bi bi-eye-slash fs-5"></i>
                                    </button>
                                </div>
                                @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember" style="width: 1.2em; height: 1.2em;">
                                    <label class="form-check-label ms-2" for="remember">Remember Me</label>
                                </div>
                                <a href="{{ route('forgot-password') }}" class="text-decoration-none">Forgot Password?</a>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 py-2 fw-bold">Login</button>

                            <p class="mt-4 mb-0 text-center">
                                Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none fw-bold">Register here</a>
                            </p>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>
    <script>
        let loginUrl = "{{ route('check.email.roles') }}";
        let csrf = '{{ csrf_token() }}';
    </script>
    <script src="{{ url('js/login.js') }}"></script>
</body>

</html>