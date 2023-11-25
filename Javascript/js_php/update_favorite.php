<?php 
include "../../main.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the budget ID from the POST data
    $budgetId = $_POST['budgetId'];

    // Update the 'favorite' column in the database
    $query = "UPDATE budget SET favorite = CASE WHEN favorite = 0 THEN 1 ELSE 0 END WHERE budget_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $budgetId);
    $stmt->execute();
    // Close the statement and database connection
    $stmt->close();
} else {
    echo "Invalid request method";
}
?>