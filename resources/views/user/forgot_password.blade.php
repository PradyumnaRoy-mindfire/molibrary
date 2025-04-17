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

<body class="d-flex justify-content-center align-items-center p-3">
    <div class="container d-flex justify-content-center align-items-center p-3" style="min-height: 100vh;">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
            <div class="card p-4 shadow-lg" style="background-color: rgba(255, 255, 255, 0.95); border-radius: 15px;">
                <h3 class="text-center mb-4 fw-bold">Forgot Password</h3>
                <div id="alert-box"></div>
                {{-- Step 1: Email & Role --}}
                <form id="step1-form">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email address</label>
                        <input type="email" id="email" name="email" class="form-control form-control-lg" required placeholder="Enter email">
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label fw-bold">Select Role</label>
                        <select class="form-select form-select-lg" id="role" name="role" required>
                            <option value="" selected disabled>Choose role</option>
                            <option value="super_admin">Super Admin</option>
                            <option value="library_admin">Library Admin</option>
                            <option value="librarian">Librarian</option>
                            <option value="member">Member</option>
                        </select>
                    </div>

                    <button type="button" class="btn btn-primary w-100 fw-bold" onclick="sendOTP()">Send OTP</button>
                </form>

                {{-- Step 2: OTP --}}
                <form id="step2-form" class="mt-4 d-none">
                    <label for="otp" class="form-label fw-bold">Enter OTP</label>
                    <input type="text" id="otp" class="form-control form-control-lg" placeholder="Enter OTP">
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small id="timer" class="text-muted"></small>
                        <button type="button" class="btn btn-link p-0" onclick="resendOTP()" id="resendBtn" disabled>Resend OTP</button>
                    </div>
                    <button type="button" class="btn btn-success w-100 mt-3 fw-bold" onclick="verifyOTP()">Verify OTP</button>
                </form>

                {{-- Step 3: Reset Password --}}
                <form id="step3-form" class="mt-4 d-none">
                    <label for="password" class="form-label fw-bold">New Password</label>
                    <input type="password" name="password" id="password" class="form-control form-control-lg mb-3" required>

                    <label for="password_confirmation" class="form-label fw-bold">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-lg mb-3" required>

                    <button type="submit" class="btn btn-dark w-100 fw-bold" >Reset Password</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let timerInterval;
        let seconds = 60;
        let email = '';
        let role = '';

        // Function to send OTP 
        function sendOTP() {
            email = document.getElementById('email').value;
            role = document.getElementById('role').value;

            if (!email || !role) {
                showAlert('Please enter email and select a role.', 'danger');
                return;
            }

            fetch("{{ route('check.user') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        email,
                        role
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        // Proceed with sending OTP since user exists
                        showAlert('User verified! Sending OTP...', 'success');
                        setTimeout(() => {
                            document.getElementById('step1-form').classList.add('d-none');
                            document.getElementById('step2-form').classList.remove('d-none');

                            startTimer();
                        }, 1000);

                        

                    } else {
                        showAlert('User not found with the given email and role.', 'danger');
                    }
                })
                .catch(error => {
                    console.error(error);
                    showAlert('Something went wrong. Please try again.', 'danger');
                });
        }



        function startTimer() {
            seconds = 60;
            document.getElementById('resendBtn').disabled = true;
            timerInterval = setInterval(() => {
                if (seconds <= 0) {
                    clearInterval(timerInterval);
                    document.getElementById('timer').textContent = '';
                    document.getElementById('resendBtn').disabled = false;
                } else {
                    document.getElementById('timer').textContent = `Resend OTP in ${seconds--}s`;
                }
            }, 1000);
        }

        function resendOTP() {
            console.log('Resending OTP...');
            startTimer(); // Restart timer
        }

        function verifyOTP() {
            const otp = document.getElementById('otp').value;
            fetch("{{ route('verify.otp') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        otp
                    }),
                })
                .then(res => res.json())
                .then(otpRes => {
                    if (otpRes.success) {
                        document.getElementById('step2-form').classList.add('d-none');
                        document.getElementById('step3-form').classList.remove('d-none');
                        showAlert('OTP verified successfully', 'success')
                        startTimer();
                    } else {
                        showAlert('OTP does not match.', 'danger');
                    }
                });
        }

        function showAlert(message, type = 'danger') {
            const alertBox = document.getElementById('alert-box');
            alertBox.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        }
        
        document.getElementById("step3-form").addEventListener("submit", function(e) {
            e.preventDefault();
            resetPassword();
        });

        function resetPassword() {
            const password = document.getElementById('password').value;
            const password_confirmation = document.getElementById('password_confirmation').value;
            fetch("{{ route('reset.password') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        email,
                        role,
                        password,
                        password_confirmation
                    }),
                })
                .then(res => res.json())
                .then(resetRes => {
                    if (resetRes.success) {
                        document.getElementById('step3-form').classList.add('d-none');
                        showAlert('Password reset successfully', 'success')
                        window.location.href = "{{ route('login') }}";
                    } else {
                        showAlert('Something went wrong. Please try again.', 'danger');
                    }
                });
        }
        
    </script>
</body>

</html>