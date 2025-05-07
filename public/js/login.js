$('#email').on('keyup', function() {
    var email = $(this).val();

    if (email.length > 0) {
        $.ajax({
            url: loginUrl,
            method: "POST",
            data: {
                email: email,
                _token: csrf
            },
            success: function(response) {
                if (response.roles.length > 1) {
                    $('#roleDiv').show();
                    $('#role').empty();
                    $('#role').append('<option value="" disabled selected>Login As</option>');
                    $.each(response.roles, function(index, role) {
                        $('#role').append('<option value="' + role + '">' + role.replace('_', ' ').toUpperCase() + '</option>');
                    });
                } else {
                    $('#roleDiv').hide();
                    $('#role').empty();
                }
            },
            error: function() {
                 
            }
        });
    } else {
        $('#roleDiv').hide();
        $('#role').empty();
    }
});


$('#loginForm').on('submit', function (e) {
    const roleDivVisible = $('#roleDiv').is(':visible');
    const selectedRole = $('#role').val();

    if (roleDivVisible && !selectedRole) {
        e.preventDefault(); // Stoping form submission if the role is not selected
        $('.roleError').text('Please select a role.');
        $('#role').focus();
    }
});

// Toggle thepassword Visibility
$('#togglePassword').on('click', function() {
    const passwordInput = $('#password');
    const icon = $(this).find('i');

    if (passwordInput.attr('type') === 'password') {
        passwordInput.attr('type', 'text');
        icon.removeClass('bi-eye-slash').addClass('bi-eye');
    } else {
        passwordInput.attr('type', 'password');
        icon.removeClass('bi-eye').addClass('bi-eye-slash');
    }
});

$('#password').on('input', function() {
    $('.passwordError').text('');
})
$('#email').on('input', function() {
    $('.emailError').text('');
})
$('#role').on('input', function() {
    $('.roleError').text('');
})

if(window.successMessage){
    Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    Toast.fire({
        icon: 'success',
        title: window.successMessage
    })
}