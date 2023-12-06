<?php 
include_once("../../main.php");
if(isset($_GET["budgetId"])){
    $budget_id = $_GET['budgetId'];
    $month = $_GET['month'];
    $where = "budget_id = " . $budget_id . " AND startdate LIKE '" . $month . "%'";
    $budgetItems = new BudgetItem($con);
    if($budgetItems->afstemBudgetItem($where)){
        header('Location: ../../html/budgets/budget_page.php?afstemt=' . $month . '&budget=' . $budget_id);
    }else{
        header('Location: ../../html/budgets/budget_month_page.php?month=' . $month . '&budget=' . $budget_id . '&afstemt=false');
    }
}else{
    echo "Intet sket";
}

?>