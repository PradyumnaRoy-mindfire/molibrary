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
                    $('#role').append('<option value="" disabled selected>Select role</option>');
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