$(document).ready(function() {
    $('.table').DataTable();
});

$(document).ready(function() {
    $('.btn-success, .btn-danger').on('click', function() {
        const button = $(this);
        const url = button.data('url');
        const buttonRow = button.closest('tr');

        $.ajax({
            url: url,
            type: 'Post',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                buttonRow.find('.btn').prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {

                    const statusClass = response.status === 'approved' ? 'text-success' : 'text-danger';
                    const statusIcon = response.status === 'approved' ?
                        '<i class="fas fa-check-circle"></i>' :
                        '<i class="fas fa-times-circle"></i>';

                    // put the button group with status text
                    const statusHtml = `<span class="${statusClass} fw-bold">${statusIcon} ${response.status.charAt(0).toUpperCase() + response.status.slice(1)}</span>`;
                    buttonRow.find('.btn-group, .action-buttons').replaceWith(statusHtml);


                    Swal.fire({
                        icon: response.status === 'approved' ? 'success' : 'error',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    })
                } else {
                    // Re-enabling buttons on error
                    buttonRow.find('.btn').prop('disabled', false);
                }
            },
            error: function() {
                buttonRow.find('.btn').prop('disabled', false);
            }
        });
    });
});