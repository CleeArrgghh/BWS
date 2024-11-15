
$(document).on('click', '.view-services-btn', function () {
    var categoryId = $(this).data('category-id');

    $('#modal-services-content').html('<p>Loading services...</p>');

    // Make an AJAX request to get services for the selected category
    $.ajax({
        url: 'fetch_services.php', 
        method: 'POST',
        data: { categoryId: categoryId },
        success: function (response) {
            // Load the services into the modal content
            $('#modal-services-content').html(response);
        },
        error: function () {
            $('#modal-services-content').html('<p>An error occurred while loading services. Please try again.</p>');
        }
    });
});


$(document).ready(function() {
    $('.view-services-btn').on('click', function() {
        var categoryId = $(this).data('category-id');
        
        $.ajax({
            url: 'fetch_services.php',
            type: 'POST',
            data: { category_id: categoryId },
            success: function(response) {
                $('#modal-services-content').html(response);
            },
            error: function(xhr, status, error) {
                $('#modal-services-content').html('<p>Error loading services.</p>');
            }
        });
    });
});


