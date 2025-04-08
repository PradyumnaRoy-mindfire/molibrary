<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1519681393784-d120267933ba') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }
        .profile-icon {
            font-size: 5rem;
            color: #4361ee;
        }
        .nav-pills .nav-link.active {
            background-color: #4361ee;
        }
        .nav-pills .nav-link {
            color: #2c3e50;
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center p-3">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card p-4 p-sm-5 shadow-lg" style="background-color: rgba(255, 255, 255, 0.95); border-radius: 15px;">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="profile-icon">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <h1 class="card-title fs-2 fw-bold mt-3">{{ auth()->user()->name }}</h1>
                            <p class="text-muted">{{ auth()->user()->email }}</p>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-4 mb-md-0">
                                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <button class="nav-link active" id="profile-tab" data-bs-toggle="pill" data-bs-target="#profile" type="button" role="tab">
                                        <i class="bi bi-person-fill me-2"></i>Profile
                                    </button>
                                    <button class="nav-link" id="edit-tab" data-bs-toggle="pill" data-bs-target="#edit" type="button" role="tab">
                                        <i class="bi bi-pencil-fill me-2"></i>Edit Profile
                                    </button>
                                    <button class="nav-link" id="password-tab" data-bs-toggle="pill" data-bs-target="#password" type="button" role="tab">
                                        <i class="bi bi-lock-fill me-2"></i>Reset Password
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="tab-content" id="v-pills-tabContent">
                                    <!-- Profile View Tab -->
                                    <div class="tab-pane fade show active" id="profile" role="tabpanel">
                                        <h3 class="mb-4 fw-bold"><i class="bi bi-info-circle-fill me-2"></i>Personal Information</h3>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Full Name</label>
                                                <p>{{ auth()->user()->name }}</p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Email Address</label>
                                                <p>{{ auth()->user()->email }}</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Phone Number</label>
                                                <p>{{ auth()->user()->phone ?? 'Not provided' }}</p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Role</label>
                                                <p class="text-capitalize">{{ auth()->user()->role }}</p>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Address</label>
                                            <p>{{ auth()->user()->address ?? 'Not provided' }}</p>
                                        </div>
                                        @if(auth()->user()->role === 'librarian')
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Library</label>
                                            <p>{{ auth()->user()->library->name ?? 'Not assigned' }}</p>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Edit Profile Tab -->
                                    <div class="tab-pane fade" id="edit" role="tabpanel">
                                        <h3 class="mb-4 fw-bold"><i class="bi bi-pencil-fill me-2"></i>Edit Profile</h3>
                                        <form action="{{ route('profile-update') }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="edit_name" class="form-label fw-bold">Full Name</label>
                                                    <input type="text" class="form-control" name="name" id="edit_name" value="{{ auth()->user()->name }}">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="edit_phone" class="form-label fw-bold">Phone Number</label>
                                                    <input type="text" class="form-control" name="phone" id="edit_phone" value="{{ auth()->user()->phone }}">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_address" class="form-label fw-bold">Address</label>
                                                <input type="text" class="form-control" name="address" id="edit_address" value="{{ auth()->user()->address }}">
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-3">
                                                <i class="bi bi-check-circle-fill me-2"></i>Update Profile
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Reset Password Tab -->
                                    <div class="tab-pane fade" id="password" role="tabpanel">
                                        <h3 class="mb-4 fw-bold"><i class="bi bi-lock-fill me-2"></i>Reset Password</h3>
                                        <form action="{{ route('password-update') }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label for="current_password" class="form-label fw-bold">Current Password</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" name="current_password" id="current_password">
                                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                                        <i class="bi bi-eye-slash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="new_password" class="form-label fw-bold">New Password</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" name="new_password" id="new_password">
                                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                                        <i class="bi bi-eye-slash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="new_password_confirmation" class="form-label fw-bold">Confirm New Password</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation">
                                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                                        <i class="bi bi-eye-slash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-3">
                                                <i class="bi bi-key-fill me-2"></i>Change Password
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password visibility toggle
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                }
            });
        });
    </script>
</body>
</html>