<?php 
include "../../main.php";

$email = $_SESSION['email']; // Get the users email
$budgetId = $_GET['budget'];
$budget = getBudget($con,$budgetId); // Get all budgets connected to the user

if(!$budget['fixed']){
    include 'fixedN_budget.php';
}else{
    include 'fixed_budget.php';
}
?>