
$(document).ready(function () {
    // Attach a click event handler to each "favorite" icon
    $('.bi-heart').on('click', function () {
        // Toggle the display of "favorite" and its immediate sibling "favorite_picked"
        $(this).toggle();
        $(this).siblings('.bi-heart-fill').toggle();
        // Get the budget ID from the value attribute
        var budgetId = $(this).attr('value');

        // Make an AJAX request to update the database
        $.ajax({
            type: 'POST',
            url: '../Javascript/js_php/update_favorite.php',
            data: { budgetId: budgetId },
            success: function(response) {
                // Handle the response if needed
                console.log(response);
            },
            error: function(error) {
                // Handle the error if needed
                console.error(error);
            }
        });
    });

    // Attach a click event handler to each "favorite_picked" icon
    $('.bi-heart-fill').on('click', function () {
        // Toggle the display of "favorite_picked" and its immediate sibling "favorite"
        $(this).toggle();
        $(this).siblings('.bi-heart').toggle();
        // Get the budget ID from the value attribute
        var budgetId = $(this).attr('value');

        // Make an AJAX request to update the database
        $.ajax({
            type: 'POST',
            url: '../Javascript/js_php/update_favorite.php',
            data: { budgetId: budgetId },
            success: function(response) {
                // Handle the response if needed
                console.log(response);
            },
            error: function(error) {
                // Handle the error if needed
                console.error(error);
            }
        });
    });
});
