$(document).ready(function () {
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

    // Handling borrow button click
    // $(document).on('click', '.btn-borr', function() {
    //     let button = $(this);
    //     let return_url = button.data('url');
    //     button.replaceWith('<span class="badge bg-info fs-6">Requested</span>');

    //     $.ajax({
    //         url: return_url,
    //         type: 'POST',
    //         success: function(response) {
    //             if (response.success) {
    //                 Swal.fire({
    //                     title: 'Success!',
    //                     text: 'Sent the return request successfully',
    //                     icon: 'success',
    //                     confirmButtonText: 'OK'
    //                 });
    //             } else {
    //                 Swal.fire({
    //                     title: 'Error!',
    //                     text: 'Error borrowing book: ' + response.message,
    //                     icon: 'error',
    //                     confirmButtonText: 'OK'
    //                 });
    //             }
    //         },
    //         error: function(xhr) {
    //             Swal.fire({
    //                 title: 'Error!',
    //                 text: 'Error processing request. Please try again.',
    //                 icon: 'error',
    //                 confirmButtonText: 'OK'
    //             });
    //         }
    //     });
    // });


    // Handling Cancel button click
    $(document).on('click', '.btn-cancel', function () {
        let button = $(this);
        let cancel_url = button.data('url');
        
        const row = $(this).closest('tr');
        const bookTitle = row.find('td').eq(0).text();

        Swal.fire({
            title: 'Cancel Reservation?',
            html: `Do you want to cancel the reservation for <strong>${bookTitle}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            reverseButtons: true,
            confirmButtonText: 'Yes, Cancel it!',
            cancelButtonText: 'Back'
        }).then((result) => {
            if (result.isConfirmed) {
                button.replaceWith('<span class="badge bg-danger fs-6">Reservation Cancelled</span>');
                $.ajax({
                    url: cancel_url,
                    type: 'POST',
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Reservation cancelled successfully!!',
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
                    error: function (xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Error processing request. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });

    });

});