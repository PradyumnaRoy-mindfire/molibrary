$(document).ready(function() {
    $('.totalRevenue').on('click', function() {
        const route = $(this).data('route');

        $('#dynamic-content').html('<p>Loading details...</p>');

        $.ajax({
            url: route,
            type: 'GET',
            success: function(response) {
                $('#dynamic-content').html(response);
                $('#users-table').DataTable({
                    order: [
                        [3, 'desc']
                    ],
                    language: {
                        search: "Search payments:",
                        zeroRecords: "No borrowing records found"
                    },
                    responsive: true
                });
            },
            error: function() {
                $('#dynamic-content').html('<p class="text-danger mt-5">Failed to load details.</p>');
            }
        });
    });

    $('.choices-card').on('click', function() {
        const route = $(this).data('route');

        $('#dynamic-content').html('<p>Loading details...</p>');

        $.ajax({
            url: route,
            type: 'GET',
            success: function(response) {
                $('#dynamic-content').html(response);
                $('#users-table').DataTable({
                    order: [
                        [3, 'desc']
                    ],
                    language: {
                        search: "Search Library:",
                        zeroRecords: "No borrowing records found"
                    },
                    responsive: true
                });
            },
            error: function() {
                $('#dynamic-content').html('<p class="text-danger">Failed to load details.</p>');
            }
        });
    });

    $('.popular-libraries').on('click', function() {
        const route = $(this).data('route');

        $('#dynamic-content').html('<p>Loading details...</p>');

        $.ajax({
            url: route,
            type: 'GET',
            success: function(response) {
                $('#dynamic-content').html(response);
                $('#users-table').DataTable({
                    order: [
                        [3, 'desc']
                    ],
                    language: {
                        search: "Search Library:",
                        zeroRecords: "No borrowing records found"
                    },
                    responsive: true
                });
            },
            error: function() {
                $('#dynamic-content').html('<p class="text-danger">Failed to load details.</p>');
            }
        });
    });
});