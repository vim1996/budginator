
document.getElementById('delete-budget').addEventListener('click', handleDeleteBudget);
function handleDeleteBudget(event) {
    event.preventDefault();
    event.stopPropagation();
    showModal();
}

function deleteBudget(budgetId) {
    // Redirect the user to delete_budget.php with the budgetId as a query parameter
    window.location.href = '../../Javascript/js_php/delete_budget.php?budgetId=' + budgetId;
}

// Function to show the modal with dynamic content and a callback function
function showModal() {
    var popupMenu = document.getElementById('popupMenu');
    popupMenu.style.display = 'none';
    var confirmBtn = document.getElementById('confirmBtn');
    var cancelBtn = document.getElementById('cancelBtn');
    var customModal = document.getElementById('customModal');
    var budgetId = document.getElementById('modal_data').getAttribute('data-budget-id');

    customModal.style.display = 'block';

    // Handle confirmation
    confirmBtn.addEventListener('click', function () {
        deleteBudget(budgetId); // Call the deleteBudget function directly
        closeModal();
    });

    // Close the modal if canceled
    cancelBtn.addEventListener('click', closeModal);

    // Close the modal when the close button is clicked
    var closeModalBtn = document.getElementById('closeModalBtn');
    closeModalBtn.addEventListener('click', closeModal);

    // Close the modal if clicked outside
    document.addEventListener('click', function (event) {
        if (event.target !== customModal && !customModal.contains(event.target)) {
            closeModal();
        }
    });

    // Close the modal function
    function closeModal() {
        customModal.style.display = 'none';
        // Remove event listeners to avoid memory leaks
        confirmBtn.removeEventListener('click', closeModal);
        cancelBtn.removeEventListener('click', closeModal);
        closeModalBtn.removeEventListener('click', closeModal);
        document.removeEventListener('click', closeModal);
    }
}

function togglePopupMenu() {
    var popupMenu = document.getElementById('popupMenu');
    popupMenu.style.display = 'unset';
}

function closeMenuIfClickedOutside(event) {
    var popupMenu = document.getElementById('popupMenu');
    if (event.target !== popupMenu && !popupMenu.contains(event.target)) {
        popupMenu.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('gearIcon').addEventListener('click', function (event) {
        event.stopPropagation(); // Prevent the click event from reaching the document
        togglePopupMenu();
    });

    // Close the menu if clicked outside
    document.addEventListener('click', closeMenuIfClickedOutside);
});