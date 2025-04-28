$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#reservedTable').DataTable({
        order: [
            [2, 'desc']
        ],
        language: {
            search: "Search records:",
            zeroRecords: "No reservation records found"
        },
        responsive: true
    });

    // Handling Return button click
    $(document).on('click', '.btn-reserve', function() {
        let button = $(this);
        let return_url = button.data('url');
        button.replaceWith('<span class="badge bg-info fs-6">Requested</span>');

        $.ajax({
            url: return_url,
            type: 'POST',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Sent the return request successfully',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error returning book: ' + response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error processing request. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    
});