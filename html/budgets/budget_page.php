<?php 
include "../../main.php";

$email = $_SESSION['email']; // Get the users email
if(isset($_GET['updated'])){
    $updated = $_GET['updated'];
}else{
    $updated = false;
}
$budgetId = $_GET['budget'];
$budget = getBudget($con,$budgetId); // Get all budgets connected to the user

if(!$budget['fixed']){
    include 'fixedN_budget.php';
}else{
    include 'fixed_budget.php';
}
?>