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
});