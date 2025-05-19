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


    $('#name').on('input', function() {
        $('.nameError').text('');
    });
    $('#email').on('input', function() {
        $('.emailError').text('');
    });
    $('#password').on('input', function() {
        $('.passwordError').text('');
    });
    $('#role').on('input', function() {
        $('.roleError').text('');
    });
    $('#phone').on('input', function() {
        $('.phoneError').text('');
    });
    $('#address').on('input', function() {
        $('.addressError').text('');
    });
});