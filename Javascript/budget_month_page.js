
document.getElementById('afstem').addEventListener('click', handleAfstem);
function handleAfstem(event) {
    event.preventDefault();
    event.stopPropagation();
    showModal();
}

function afstemMonth(budgetId, month) {
    // Redirect the user to delete_budget.php with the budgetId as a query parameter
    window.location.href = '../../Javascript/js_php/afstem_month.php?budgetId=' + budgetId + '&month=' + month;
}

// Function to show the modal with dynamic content and a callback function
function showModal() {
    var confirmBtn = document.getElementById('confirmBtn');
    var cancelBtn = document.getElementById('cancelBtn');
    var customModal = document.getElementById('customModal');
    var budgetId = document.getElementById('modal_data').getAttribute('data-budget-id');
    var month = document.getElementById('modal_data').getAttribute('data-budget-month');

    customModal.style.display = 'block';

    // Handle confirmation
    confirmBtn.addEventListener('click', function () {
        afstemMonth(budgetId, month); // Call the afstemMonth function directly
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