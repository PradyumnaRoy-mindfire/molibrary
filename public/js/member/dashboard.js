$(document).ready(function() {
    $('.membership-card').on('click', function() {
        const route = $(this).data('route');

        $('#dynamic-content').html('<p>Loading details...</p>');

        $.ajax({
            url: route,
            type: 'GET',
            success: function(response) {
                $('#dynamic-content').html(response);
            },
            error: function() {
                $('#dynamic-content').html('<p class="text-danger">Failed to load details.</p>');
            }
        });
    });

    $('.activeLibrary-card').on('click', function() {
        const route = $(this).data('route');

        $('#dynamic-content').html('<p>Loading details...</p>');

        $.ajax({
            url: route,
            type: 'GET',
            success: function(response) {
                $('#dynamic-content').html(response);
                $('#library-table').DataTable({
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

    $('.inactive-card').on('click', function() {
        const route = $(this).data('route');

        $('#dynamic-content').html('<p>Loading details...</p>');

        $.ajax({
            url: route,
            type: 'GET',
            success: function(response) {
                $('#dynamic-content').html(response);
            },
            error: function() {
                $('#dynamic-content').html('<p class="text-danger">Failed to load details.</p>');
            }
        });
    });


    const defaultRoute = $('.fines-card').first().data('route'); 
    if (defaultRoute) {
        loadFineData(defaultRoute);
    }

    $('.fines-card').on('click', function() {
        const route = $(this).data('route');
        loadFineData(route);
    });

});


function loadFineData(route) {
    $('#dynamic-content').html('<p>Loading details...</p>');

    $.ajax({
        url: route,
        type: 'GET',
        success: function(response) {
            $('#dynamic-content').html(response);
            $('#users-table').DataTable({
                order: [
                    [4, 'desc']
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
}
