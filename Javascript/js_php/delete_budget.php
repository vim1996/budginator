<?php 
include_once("../../main.php");
if(isset($_GET["budgetId"])){
    $budget_id = $_GET['budgetId'];
    
    $budget = new Budget($con);
    if($budget->deleteBudget($budget_id)){
        header('Location: ../../html/budgets.php?delete=' . $budget_id);
    }

}else{
    echo "Intet get";
}

?>